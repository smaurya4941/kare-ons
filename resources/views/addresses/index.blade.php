<x-customer-layout>
    <x-slot name="title">Manage Addresses</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-on-surface">My Addresses</h1>
            <a href="{{ route('addresses.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-[20px]">add</span>
                <span class="hidden sm:inline">Add New Address</span>
            </a>
        </div>

        @if($addresses->isEmpty())
            <div class="bg-surface rounded-xl border border-outline-variant p-12 text-center shadow-sm">
                <div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-outline text-[40px]">location_on</span>
                </div>
                <h3 class="text-lg font-bold text-on-surface mb-2">No Addresses Found</h3>
                <p class="text-secondary mb-6">You haven't saved any delivery addresses yet.</p>
                <a href="{{ route('addresses.create') }}" class="inline-flex items-center gap-2 border border-primary text-primary px-6 py-2.5 rounded-lg font-medium hover:bg-primary-fixed transition-colors">
                    Add Address
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($addresses as $address)
                    <div class="bg-surface rounded-xl border {{ $address->is_default ? 'border-primary shadow-md ring-1 ring-primary/20' : 'border-outline-variant shadow-sm' }} p-6 relative group transition-shadow">
                        <div class="absolute top-4 right-4 flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('addresses.edit', $address->id) }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container hover:bg-primary-fixed text-secondary hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Delete this address?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container hover:bg-error-container text-secondary hover:text-error transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                        
                        <div class="pr-20">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-bold text-lg text-on-surface">{{ $address->full_name }}</h3>
                                @if($address->is_default)
                                    <span class="bg-primary/10 text-primary border border-primary/20 font-bold px-2 py-0.5 rounded text-[10px] uppercase tracking-wider">Default</span>
                                @endif
                            </div>
                            <p class="text-sm font-medium text-secondary mb-3">{{ $address->phone }}</p>
                            
                            <p class="text-on-surface leading-relaxed text-sm">
                                {{ $address->address_line_1 }}<br>
                                @if($address->address_line_2) {{ $address->address_line_2 }}<br> @endif
                                {{ $address->city }}, {{ $address->state }} - <span class="font-semibold">{{ $address->postal_code }}</span><br>
                                {{ $address->country }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-customer-layout>
