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
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        $customers = $query
            ->withCount('orders')
            ->withSum('orders', 'grand_total')
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        // Ensure we are viewing a customer
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')->with('error', 'Invalid customer profile.');
        }

        $customer->load(['orders.items', 'addresses']);
        
        return view('admin.customers.show', compact('customer'));
    }
}
