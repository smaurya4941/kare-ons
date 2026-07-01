<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::all();
        return view('admin.payment_methods.index', compact('methods'));
    }

    public function edit(PaymentMethod $payment_method)
    {
        return view('admin.payment_methods.form', ['method' => $payment_method]);
    }

    public function update(Request $request, PaymentMethod $payment_method)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'instructions' => 'nullable|string',
        ]);
        
        $payment_method->update($validated);
        return redirect()->route('admin.payment_methods.index')->with('success', 'Payment method updated successfully.');
    }
}
