@extends('admin.layouts.app')

@section('title', 'Activity Log')

@php
    $eventColors = [
        'created'  => 'bg-emerald-50 text-emerald-500',
        'updated'  => 'bg-indigo-50 text-indigo-500',
        'deleted'  => 'bg-red-50 text-red-500',
        'restored' => 'bg-amber-50 text-amber-500',
    ];
    $badgeColors = [
        'created'  => 'bg-emerald-100 text-emerald-700',
        'updated'  => 'bg-indigo-100 text-indigo-700',
        'deleted'  => 'bg-red-100 text-red-700',
        'restored' => 'bg-amber-100 text-amber-700',
    ];
@endphp

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <p class="text-sm text-gray-500">A chronological audit trail of admin actions across the store.</p>
    <form action="{{ route('admin.activity.clear') }}" method="POST" onsubmit="return confirm('Delete activity log entries older than 90 days?');">
        @csrf @method('DELETE')
        <input type="hidden" name="days" value="90">
        <button type="submit" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[18px]">delete_sweep</span> Prune &gt; 90 days
        </button>
    </form>
</div>

{{-- Filters --}}
<form action="{{ route('admin.activity.index') }}" method="GET" class="mb-6">
    <div class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search action or admin..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 flex-1 max-w-xs">
        <select name="event" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All events</option>
            @foreach(['created', 'updated', 'deleted', 'restored'] as $ev)
                <option value="{{ $ev }}" {{ request('event') === $ev ? 'selected' : '' }}>{{ ucfirst($ev) }}</option>
            @endforeach
        </select>
        <select name="type" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All types</option>
            @foreach($subjectTypes as $type)
                <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" title="From date">
        <input type="date" name="to" value="{{ request('to') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" title="To date">
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Filter</button>
        @if(request()->anyFilled(['search', 'event', 'type', 'from', 'to']))
            <a href="{{ route('admin.activity.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Clear</a>
        @endif
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-white border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Action</th>
                <th class="px-6 py-4 font-medium">Admin</th>
                <th class="px-6 py-4 font-medium">Type</th>
                <th class="px-6 py-4 font-medium">When</th>
                <th class="px-6 py-4 font-medium text-right">Details</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px] w-9 h-9 flex items-center justify-center rounded-full flex-shrink-0 {{ $eventColors[$log->event] ?? 'bg-gray-100 text-gray-400' }}">
                            {{ $log->icon }}
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-800">{{ $log->description }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wide {{ $badgeColors[$log->event] ?? 'bg-gray-100 text-gray-600' }}">{{ $log->event }}</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-700">{{ $log->causer_name ?? 'System' }}</p>
                    @if($log->ip_address)
                        <p class="text-xs text-gray-400">{{ $log->ip_address }}</p>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $log->subject_label ?? '—' }}</td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-600">{{ $log->created_at->diffForHumans() }}</p>
                    <p class="text-xs text-gray-400">{{ $log->created_at->format('M d, Y H:i') }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-right">
                    <a href="{{ route('admin.activity.show', $log->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-5xl">history</span>
                    <p class="text-gray-500 mt-3 font-medium">No activity recorded yet</p>
                    <p class="text-gray-400 text-sm">Admin actions like product edits, order changes and settings updates will appear here.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($logs->hasPages())
    <div class="mt-6">
        {{ $logs->links() }}
    </div>
@endif
@endsection
