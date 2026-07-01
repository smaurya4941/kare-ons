<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTimeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $query->where(function($q) use ($term) {
                $q->where('order_number', 'like', $term)
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', $term));
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'address', 'timelines']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status'  => 'required|in:pending,confirmed,packed,shipped,delivered,returned,cancelled',
            'refund_status' => 'required|in:none,pending,partial,refunded',
            'notes'         => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->order_status;
            $newStatus = $validated['order_status'];

            $order->update([
                'order_status' => $newStatus,
                'refund_status' => $validated['refund_status'],
            ]);

            // Handle inventory restocking on cancellation or return
            if (in_array($newStatus, ['cancelled', 'returned']) && !in_array($oldStatus, ['cancelled', 'returned'])) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock_quantity', $item->quantity);
                        
                        \App\Models\InventoryTransaction::create([
                            'product_id' => $item->product_id,
                            'user_id' => auth()->id(),
                            'type' => $newStatus === 'cancelled' ? 'order_cancellation' : 'return',
                            'quantity' => $item->quantity, // Positive for restocking
                            'reference_id' => $order->order_number,
                            'notes' => "Stock restored due to order {$newStatus}.",
                        ]);
                    }
                }
            }

            // Add timeline entry if status changed or notes provided
            if ($oldStatus !== $newStatus || !empty($validated['notes'])) {
                OrderTimeline::create([
                    'order_id' => $order->id,
                    'status' => $newStatus,
                    'notes' => $validated['notes']
                ]);
            }

            // Send Shipped Email Notification
            if ($newStatus === 'shipped' && $oldStatus !== 'shipped' && $order->user) {
                \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\OrderShipped($order));
            }

            DB::commit();
            return back()->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->with('error', 'Failed to update order due to an unexpected error.');
        }
    }

    public function printInvoice(Order $order)
    {
        $order->load(['user', 'items.product', 'address']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.prints.invoice', compact('order'));
        return $pdf->stream('Invoice_' . $order->order_number . '.pdf');
    }

    public function printPackingSlip(Order $order)
    {
        $order->load(['items.product', 'address']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.prints.packing_slip', compact('order'));
        return $pdf->stream('PackingSlip_' . $order->order_number . '.pdf');
    }

    public function printShippingLabel(Order $order)
    {
        $order->load(['address']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.prints.shipping_label', compact('order'))
            ->setPaper([0, 0, 283.46, 425.2], 'portrait'); // Example: 100x150mm standard shipping label size
        return $pdf->stream('ShippingLabel_' . $order->order_number . '.pdf');
    }

    public function destroy(Order $order)
    {
        // Safety: only allow deleting cancelled orders
        if ($order->order_status !== 'cancelled') {
            return back()->with('error', 'Only cancelled orders can be deleted.');
        }

        try {
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to delete order due to an unexpected error.');
        }
    }
}
