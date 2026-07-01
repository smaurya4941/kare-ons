<x-customer-layout>
    <x-slot name="title">Add New Address</x-slot>

    <div class="space-y-6 max-w-2xl">
        <div class="flex items-center gap-4">
            <a href="{{ route('addresses.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-2xl font-bold text-on-surface">Add New Address</h1>
        </div>

        <form action="{{ route('addresses.store') }}" method="POST" class="bg-surface rounded-xl border border-outline-variant shadow-sm p-6 sm:p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">Full Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary shadow-sm">
                    @error('full_name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary shadow-sm">
                    @error('phone') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">Address Line 1 *</label>
                    <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary shadow-sm" placeholder="House/Flat No., Building Name">
                    @error('address_line_1') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">Address Line 2 (Optional)</label>
                    <input type="text" name="address_line_2" value="{{ old('address_line_2') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary shadow-sm" placeholder="Street, Locality, Area">
                    @error('address_line_2') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">City *</label>
                    <input type="text" name="city" value="{{ old('city') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary shadow-sm">
                    @error('city') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">State *</label>
                    <input type="text" name="state" value="{{ old('state') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary shadow-sm">
                    @error('state') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1">Pincode *</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary shadow-sm">
                    @error('postal_code') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_default" id="is_default" value="1" class="rounded border-outline-variant text-primary focus:ring-primary w-5 h-5 cursor-pointer">
                <label for="is_default" class="text-sm font-medium text-on-surface cursor-pointer select-none">Set as Default Delivery Address</label>
            </div>

            <div class="pt-4 border-t border-outline-variant flex justify-end gap-3">
                <a href="{{ route('addresses.index') }}" class="px-6 py-2.5 rounded-lg font-medium text-secondary hover:bg-surface-container transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors shadow-sm">Save Address</button>
            </div>
        </form>
    </div>
</x-customer-layout>
