{{--
    Admin notification bell + dropdown.
    Uses Alpine to poll the JSON feed so the badge stays fresh without a full
    page reload. Falls back gracefully to the notifications page.
--}}
<div
    x-data="notificationBell()"
    x-init="init()"
    @click.outside="open = false"
    class="relative"
>
    <button
        @click="toggle()"
        class="relative text-gray-500 hover:text-gray-700 focus:outline-none flex items-center"
        title="Notifications"
    >
        <span class="material-symbols-outlined">notifications</span>
        <template x-if="unreadCount > 0">
            <span
                class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-red-500 text-white text-[10px] font-bold rounded-full"
                x-text="unreadCount > 99 ? '99+' : unreadCount"
            ></span>
        </template>
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
        style="display: none;"
    >
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <p class="text-sm font-semibold text-gray-800">Notifications</p>
            <form action="{{ route('admin.notifications.read_all') }}" method="POST" x-show="unreadCount > 0">
                @csrf
                <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Mark all read</button>
            </form>
        </div>

        <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
            <template x-if="items.length === 0">
                <div class="px-4 py-10 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-4xl">notifications_off</span>
                    <p class="text-sm text-gray-400 mt-2">You're all caught up</p>
                </div>
            </template>

            <template x-for="n in items" :key="n.id">
                <a
                    :href="'{{ url('admin/notifications') }}/' + n.id + '/read'"
                    class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition"
                    :class="!n.is_read ? 'bg-indigo-50/40' : ''"
                >
                    <span
                        class="material-symbols-outlined text-[20px] mt-0.5 flex-shrink-0"
                        :class="colorClass(n.color)"
                        x-text="n.icon"
                    ></span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 truncate" x-text="n.title"></p>
                        <p class="text-xs text-gray-500 truncate" x-text="n.message"></p>
                        <p class="text-[11px] text-gray-400 mt-0.5" x-text="n.created_at"></p>
                    </div>
                    <template x-if="!n.is_read">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 mt-2 flex-shrink-0"></span>
                    </template>
                </a>
            </template>
        </div>

        <a href="{{ route('admin.notifications.index') }}" class="block px-4 py-3 text-center text-sm font-medium text-indigo-600 hover:bg-gray-50 border-t border-gray-100">
            View all notifications
        </a>
    </div>
</div>

<script>
    function notificationBell() {
        return {
            open: false,
            unreadCount: 0,
            items: [],
            feedUrl: '{{ route('admin.notifications.feed') }}',
            colorMap: {
                emerald: 'text-emerald-500',
                red: 'text-red-500',
                amber: 'text-amber-500',
                indigo: 'text-indigo-500',
                sky: 'text-sky-500',
                gray: 'text-gray-400',
            },
            init() {
                this.fetchFeed();
                // Poll every 30s while the tab is open.
                setInterval(() => {
                    if (!document.hidden) this.fetchFeed();
                }, 30000);
            },
            toggle() {
                this.open = !this.open;
                if (this.open) this.fetchFeed();
            },
            colorClass(color) {
                return this.colorMap[color] || this.colorMap.gray;
            },
            fetchFeed() {
                fetch(this.feedUrl, { headers: { 'Accept': 'application/json' } })
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        this.unreadCount = data.unread_count;
                        this.items = data.notifications;
                    })
                    .catch(() => { /* silent — badge just won't update this cycle */ });
            },
        };
    }
</script>
