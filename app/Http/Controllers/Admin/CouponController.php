<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::latest();

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        $coupons = $query->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                 => 'required|string|max:50|unique:coupons,code',
            'type'                 => 'required|in:percentage,flat',
            'value'                => 'required|numeric|min:0|max:100000',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'          => 'nullable|integer|min:1',
            'starts_at'            => 'nullable|date',
            'expires_at'           => 'nullable|date|after_or_equal:starts_at',
            'status'               => 'boolean',
        ]);

        $validated['code']   = strtoupper(trim($validated['code']));
        $validated['status'] = $request->boolean('status', true);
        $validated['minimum_order_amount'] = $validated['minimum_order_amount'] ?? 0;

        try {
            Coupon::create($validated);
            return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Failed to create coupon due to an unexpected error.');
        }
    }

    public function show(Coupon $coupon)
    {
        $coupon->load('usages');
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code'                 => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'                 => 'required|in:percentage,flat',
            'value'                => 'required|numeric|min:0|max:100000',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'          => 'nullable|integer|min:1',
            'starts_at'            => 'nullable|date',
            'expires_at'           => 'nullable|date|after_or_equal:starts_at',
            'status'               => 'boolean',
        ]);

        $validated['code']   = strtoupper(trim($validated['code']));
        $validated['status'] = $request->boolean('status');
        $validated['minimum_order_amount'] = $validated['minimum_order_amount'] ?? 0;

        try {
            $coupon->update($validated);
            return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Failed to update coupon due to an unexpected error.');
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
            return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted.');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to delete coupon due to an unexpected error.');
        }
    }
}
