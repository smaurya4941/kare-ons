@extends('admin.layouts.app')

@section('title', 'Notifications')

@php
    $tabs = [
        ''          => 'All',
        'unread'    => 'Unread',
        'order'     => 'Orders',
        'low_stock' => 'Low Stock',
        'review'    => 'Reviews',
        'message'   => 'Messages',
    ];
    $colorClasses = [
        'emerald' => 'bg-emerald-50 text-emerald-500',
        'red'     => 'bg-red-50 text-red-500',
        'amber'   => 'bg-amber-50 text-amber-500',
        'indigo'  => 'bg-indigo-50 text-indigo-500',
        'sky'     => 'bg-sky-50 text-sky-500',
        'gray'    => 'bg-gray-100 text-gray-400',
    ];
@endphp

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <p class="text-sm text-gray-500">
            Store activity — new orders, low stock alerts, pending reviews and customer messages.
        </p>
    </div>
    <div class="flex items-center gap-2">
        @if($unreadCount > 0)
        <form action="{{ route('admin.notifications.read_all') }}" method="POST">
            @csrf
            <button type="submit" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">done_all</span> Mark all read
            </button>
        </form>
        @endif
        <form action="{{ route('admin.notifications.clear_read') }}" method="POST" onsubmit="return confirm('Delete all notifications that have been read?');">
            @csrf @method('DELETE')
            <button type="submit" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">delete_sweep</span> Clear read
            </button>
        </form>
    </div>
</div>

{{-- Filter tabs --}}
<div class="mb-6 flex flex-wrap gap-2">
    @foreach($tabs as $key => $label)
        <a href="{{ route('admin.notifications.index', array_filter(['filter' => $key])) }}"
           class="px-4 py-1.5 rounded-full text-sm font-medium transition {{ (string) $filter === (string) $key ? 'bg-brand-forest text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-50">
    @forelse($notifications as $notification)
        <div class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 transition {{ $notification->is_read ? '' : 'bg-indigo-50/40' }}">
            <span class="material-symbols-outlined text-[22px] w-10 h-10 flex items-center justify-center rounded-full flex-shrink-0 {{ $colorClasses[$notification->color] ?? $colorClasses['gray'] }}">
                {{ $notification->icon ?? 'notifications' }}
            </span>

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <p class="text-sm font-semibold text-gray-800">{{ $notification->title }}</p>
                    @unless($notification->is_read)
                        <span class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></span>
                    @endunless
                </div>
                @if($notification->message)
                    <p class="text-sm text-gray-600 mt-0.5">{{ $notification->message }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }} · {{ $notification->created_at->format('M d, Y H:i') }}</p>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
                @if($notification->url)
                    <a href="{{ route('admin.notifications.read', $notification->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                @endif
                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-600" title="Delete">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="px-6 py-16 text-center">
            <span class="material-symbols-outlined text-gray-300 text-5xl">notifications_off</span>
            <p class="text-gray-500 mt-3 font-medium">No notifications</p>
            <p class="text-gray-400 text-sm">New store activity will appear here.</p>
        </div>
    @endforelse
</div>

@if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
@endif
@endsection
