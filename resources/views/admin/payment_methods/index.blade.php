@extends('admin.layouts.app')

@section('title', 'Payment Methods')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-gray-500">Enable or disable payment gateways and manage instructions.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Method</th>
                <th class="px-6 py-4 font-medium">Code</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($methods as $method)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <p class="text-[12px] font-semibold text-gray-800">{{ $method->name }}</p>
                    <p class="text-[10px] text-gray-500 mt-1 truncate max-w-sm" title="{{ $method->instructions }}">{{ $method->instructions }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-mono">{{ $method->code }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $method->status ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $method->status ? 'Enabled' : 'Disabled' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.payment_methods.edit', $method->id) }}" class="text-indigo-600 hover:underline text-[11px] font-medium">Edit / Configure</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
