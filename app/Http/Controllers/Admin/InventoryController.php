<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filter = $request->get('filter'); // 'low_stock', 'out_of_stock'

        $query = Product::query()
            ->withSum(['orderItems as reserved_stock' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->whereIn('order_status', ['pending', 'confirmed', 'packed']);
                });
            }], 'quantity');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($filter === 'low_stock') {
            $query->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0);
        } elseif ($filter === 'out_of_stock') {
            $query->where('stock_quantity', '<=', 0);
        }

        $products = $query->paginate(20);

        // Calculate summary stats
        $lowStockCount = Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count();
        $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();
        $totalStock = Product::sum('stock_quantity'); // Available stock

        return view('admin.inventory.index', compact('products', 'search', 'filter', 'lowStockCount', 'outOfStockCount', 'totalStock'));
    }

    public function history(Product $product)
    {
        $transactions = InventoryTransaction::with('user')
            ->where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.inventory.history', compact('product', 'transactions'));
    }

    public function storeAdjustment(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => 'required|in:purchase,adjustment',
            'quantity' => 'required|integer|not_in:0',
            'notes' => 'nullable|string',
        ]);

        $product->increment('stock_quantity', $validated['quantity']);

        InventoryTransaction::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }
}
