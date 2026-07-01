@extends('admin.layouts.app')

@section('title', 'Shipping Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-gray-500">Manage delivery zones, shipping charges, COD fees, and free shipping thresholds.</p>
    </div>
    <a href="{{ route('admin.shipping.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition text-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">add</span> Add Zone
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Zone Name</th>
                <th class="px-6 py-4 font-medium">Base Charge</th>
                <th class="px-6 py-4 font-medium">Free Above</th>
                <th class="px-6 py-4 font-medium">COD Charge</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($zones as $zone)
            <tr class="hover:bg-gray-50 {{ $zone->is_default ? 'bg-indigo-50/30' : '' }}">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <p class="text-[12px] font-semibold text-gray-800">{{ $zone->name }}</p>
                        @if($zone->is_default)
                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[9px] font-bold uppercase rounded-full">Default</span>
                        @endif
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1 truncate max-w-xs" title="{{ $zone->coverage }}">
                        {{ $zone->coverage ?: 'Applies to all unmapped areas' }}
                    </p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-[12px] font-medium text-gray-700">₹{{ number_format($zone->base_charge, 2) }}</span>
                </td>
                <td class="px-6 py-4">
                    @if($zone->free_shipping_threshold)
                        <span class="text-[12px] font-medium text-emerald-600">₹{{ number_format($zone->free_shipping_threshold, 2) }}</span>
                    @else
                        <span class="text-[10px] text-gray-400">N/A</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="text-[12px] font-medium text-gray-700">₹{{ number_format($zone->cod_charge, 2) }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $zone->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $zone->is_active ? 'Active' : 'Disabled' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.shipping.edit', $zone->id) }}" class="text-indigo-600 hover:underline text-[11px] font-medium mr-3">Edit</a>
                    <form action="{{ route('admin.shipping.destroy', $zone->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this shipping zone?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-[11px] font-medium" {{ $zone->is_default ? 'disabled title="Cannot delete default zone"' : '' }}>Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">No shipping zones configured.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
