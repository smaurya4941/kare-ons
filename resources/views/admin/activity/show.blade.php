@extends('admin.layouts.app')

@section('title', 'Activity Detail')

@php
    $old = data_get($activity->properties, 'old', []);
    $new = data_get($activity->properties, 'attributes', []);
    // Keys to display: union of changed keys (updated) or the snapshot (created/deleted).
    $keys = collect(array_keys($new))->merge(array_keys($old))->unique()->values();
    $format = function ($v) {
        if (is_null($v)) return '—';
        if (is_bool($v)) return $v ? 'true' : 'false';
        if (is_array($v)) return json_encode($v);
        return (string) $v;
    };
@endphp

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.activity.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to activity log
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Meta --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider">Action</p>
            <p class="text-sm font-semibold text-gray-800 mt-1">{{ $activity->description }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider">Event</p>
            <p class="text-sm text-gray-700 mt-1 capitalize">{{ $activity->event }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider">Performed by</p>
            <p class="text-sm text-gray-700 mt-1">{{ $activity->causer_name ?? 'System' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider">Record</p>
            <p class="text-sm text-gray-700 mt-1">{{ $activity->subject_label ?? '—' }}{{ $activity->subject_id ? ' #' . $activity->subject_id : '' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider">When</p>
            <p class="text-sm text-gray-700 mt-1">{{ $activity->created_at->format('M d, Y H:i:s') }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider">IP Address</p>
            <p class="text-sm text-gray-700 mt-1">{{ $activity->ip_address ?? '—' }}</p>
        </div>
        @if($activity->user_agent)
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider">User Agent</p>
            <p class="text-xs text-gray-500 mt-1 break-words">{{ $activity->user_agent }}</p>
        </div>
        @endif
    </div>

    {{-- Changes --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <p class="text-sm font-semibold text-gray-800">
                {{ $activity->event === 'updated' ? 'Changed fields' : 'Record snapshot' }}
            </p>
        </div>

        @if($keys->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-gray-400">No field-level data recorded for this entry.</div>
        @else
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Field</th>
                        @if($activity->event === 'updated')
                            <th class="px-6 py-3 font-medium">Old value</th>
                        @endif
                        <th class="px-6 py-3 font-medium">{{ $activity->event === 'updated' ? 'New value' : 'Value' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($keys as $key)
                    <tr>
                        <td class="px-6 py-3 text-sm font-medium text-gray-700">{{ $key }}</td>
                        @if($activity->event === 'updated')
                            <td class="px-6 py-3 text-sm text-red-600 break-words">{{ $format($old[$key] ?? null) }}</td>
                        @endif
                        <td class="px-6 py-3 text-sm text-emerald-700 break-words">{{ $format($new[$key] ?? ($old[$key] ?? null)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
