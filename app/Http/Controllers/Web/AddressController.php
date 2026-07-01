<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        // Order by default first, then latest
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->latest()->get();
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
        ]);

        $isDefault = $request->has('is_default');

        if ($isDefault || Auth::user()->addresses()->count() === 0) {
            Auth::user()->addresses()->update(['is_default' => false]);
            $isDefault = true;
        }

        $validated['user_id'] = Auth::id();
        $validated['country'] = 'India'; // Defaulting as per KareOns spec
        $validated['is_default'] = $isDefault;

        Address::create($validated);

        return redirect()->route('addresses.index')->with('success', 'Address added successfully.');
    }

    public function edit(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        return view('addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
        ]);

        $isDefault = $request->has('is_default');

        if ($isDefault) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $validated['is_default'] = $isDefault;
        $address->update($validated);

        return redirect()->route('addresses.index')->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $latestAddress = Auth::user()->addresses()->latest()->first();
            if ($latestAddress) {
                $latestAddress->update(['is_default' => true]);
            }
        }

        return redirect()->route('addresses.index')->with('success', 'Address deleted successfully.');
    }
}
