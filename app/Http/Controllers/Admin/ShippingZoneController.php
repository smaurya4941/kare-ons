<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::orderBy('is_default', 'desc')->orderBy('name')->get();
        return view('admin.shipping.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.shipping.form', ['zone' => new ShippingZone()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'coverage' => 'nullable|string',
            'base_charge' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'cod_charge' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_default'] = $request->boolean('is_default');

        if ($validated['is_default']) {
            ShippingZone::where('id', '>', 0)->update(['is_default' => false]);
        }

        ShippingZone::create($validated);
        return redirect()->route('admin.shipping.index')->with('success', 'Shipping zone created successfully.');
    }

    public function edit(ShippingZone $shipping_zone)
    {
        return view('admin.shipping.form', ['zone' => $shipping_zone]);
    }

    public function update(Request $request, ShippingZone $shipping_zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'coverage' => 'nullable|string',
            'base_charge' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'cod_charge' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_default'] = $request->boolean('is_default');

        if ($validated['is_default']) {
            ShippingZone::where('id', '!=', $shipping_zone->id)->update(['is_default' => false]);
        }

        $shipping_zone->update($validated);
        return redirect()->route('admin.shipping.index')->with('success', 'Shipping zone updated successfully.');
    }

    public function destroy(ShippingZone $shipping_zone)
    {
        $shipping_zone->delete();
        return redirect()->route('admin.shipping.index')->with('success', 'Shipping zone deleted successfully.');
    }
}
