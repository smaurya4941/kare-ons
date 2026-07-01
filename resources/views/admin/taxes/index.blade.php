@extends('admin.layouts.app')

@section('title', 'Tax Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-gray-500">Manage GST and other tax slabs for products.</p>
    </div>
    <a href="{{ route('admin.taxes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition text-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">add</span> Add Tax Slab
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Tax Name</th>
                <th class="px-6 py-4 font-medium">Rate (%)</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($taxes as $tax)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <p class="text-[12px] font-semibold text-gray-800">{{ $tax->name }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-[12px] font-medium text-gray-700">{{ $tax->rate }}%</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $tax->status ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $tax->status ? 'Active' : 'Disabled' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.taxes.edit', $tax->id) }}" class="text-indigo-600 hover:underline text-[11px] font-medium mr-3">Edit</a>
                    <form action="{{ route('admin.taxes.destroy', $tax->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this tax slab?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-[11px] font-medium">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No tax slabs configured.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
