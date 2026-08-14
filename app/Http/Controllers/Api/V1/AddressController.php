<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;

/**
 * Mirrors Web\AddressController — same default-address promotion/demotion rules.
 */
class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->orderBy('is_default', 'desc')->latest()->get();

        return AddressResource::collection($addresses);
    }

    public function store(AddressRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $isDefault = $request->boolean('is_default');

        if ($isDefault || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $isDefault = true;
        }

        $validated['user_id'] = $user->id;
        $validated['country'] = 'India';
        $validated['is_default'] = $isDefault;

        $address = Address::create($validated);

        return new AddressResource($address);
    }

    public function update(AddressRequest $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validated();
        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            $request->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $validated['is_default'] = $isDefault;
        $address->update($validated);

        return new AddressResource($address);
    }

    public function destroy(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $latestAddress = $request->user()->addresses()->latest()->first();
            if ($latestAddress) {
                $latestAddress->update(['is_default' => true]);
            }
        }

        return response()->json(['message' => 'Address deleted.']);
    }
}
