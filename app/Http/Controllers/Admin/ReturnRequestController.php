<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function index()
    {
        $requests = ReturnRequest::with(['order', 'user'])->latest()->paginate(15);
        return view('admin.returns.index', compact('requests'));
    }

    public function show(ReturnRequest $return_request)
    {
        $return_request->load(['order.items.product', 'user']);
        return view('admin.returns.show', compact('return_request'));
    }

    public function update(Request $request, ReturnRequest $return_request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'admin_note' => 'nullable|string'
        ]);
        
        $return_request->update($validated);
        
        if ($validated['status'] === 'completed' && $return_request->type === 'refund') {
            $return_request->order->update(['refund_status' => 'refunded', 'order_status' => 'returned']);
        }
        
        return back()->with('success', 'Return request updated successfully.');
    }
}
