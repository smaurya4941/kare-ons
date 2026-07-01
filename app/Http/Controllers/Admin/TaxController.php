<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = Tax::orderBy('rate')->get();
        return view('admin.taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('admin.taxes.form', ['tax' => new Tax()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ]);

        Tax::create($validated);
        return redirect()->route('admin.taxes.index')->with('success', 'Tax slab created successfully.');
    }

    public function edit(Tax $tax)
    {
        return view('admin.taxes.form', compact('tax'));
    }

    public function update(Request $request, Tax $tax)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ]);

        $tax->update($validated);
        return redirect()->route('admin.taxes.index')->with('success', 'Tax slab updated successfully.');
    }

    public function destroy(Tax $tax)
    {
        $tax->delete();
        return redirect()->route('admin.taxes.index')->with('success', 'Tax slab deleted successfully.');
    }
}
