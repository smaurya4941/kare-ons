@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-gray-500">Manage customer reviews and testimonials shown on the homepage.</p>
    </div>
    <a href="{{ route('admin.testimonials.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition text-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">add</span> Add Testimonial
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Customer</th>
                <th class="px-6 py-4 font-medium">Rating</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($testimonials as $test)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 flex items-center gap-3">
                    @if($test->avatar)
                        <img src="{{ asset('storage/' . $test->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                            {{ substr($test->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-[12px] font-semibold text-gray-800">{{ $test->name }}</p>
                        <p class="text-[10px] text-gray-500">{{ $test->role }}</p>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex text-yellow-400">
                        @for($i = 0; $i < $test->rating; $i++)
                            <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        @endfor
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $test->status ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $test->status ? 'Active' : 'Hidden' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.testimonials.edit', $test->id) }}" class="text-indigo-600 hover:underline text-[11px] font-medium mr-3">Edit</a>
                    <form action="{{ route('admin.testimonials.destroy', $test->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this testimonial?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-[11px] font-medium">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No testimonials added yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
