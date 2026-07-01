<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $customers = $query
            ->withCount('orders')
            ->withSum('orders', 'grand_total')
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')->with('error', 'Invalid customer profile.');
        }

        $customer->load(['orders.items', 'addresses', 'wishlists.product']);
        
        return view('admin.customers.show', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        if ($customer->role !== 'customer') {
            return back()->with('error', 'Invalid customer profile.');
        }

        $validated = $request->validate([
            'notes'          => 'nullable|string',
            'reward_points'  => 'required|integer|min:0',
            'wallet_balance' => 'required|numeric|min:0',
            'status'         => 'required|boolean',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Customer profile updated successfully.');
    }
}
