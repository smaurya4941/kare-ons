{{--
    Live-search results dropdown.

    Renders the Alpine-driven suggestions panel. Must be placed inside an element
    with `x-data="productSearch(...)"`. Positioning/width is supplied by the caller
    via $panelClass so the same markup works for the header (expanding) and the
    shop filter (inline) search boxes.

    @param string $panelClass  Positioning + width utility classes for the panel.
--}}
@php($panelClass = $panelClass ?? 'right-0 mt-2 w-[78vw] max-w-sm md:w-80')

<div
    x-show="showDropdown"
    x-cloak
    x-transition
    class="absolute z-50 bg-white border border-outline-variant rounded-xl shadow-xl overflow-hidden {{ $panelClass }}"
>
    {{-- Loading state --}}
    <div x-show="loading && !hasResults" class="px-4 py-6 text-center text-sm text-on-surface-variant">
        <span class="material-symbols-outlined animate-spin align-middle text-[20px]">progress_activity</span>
        <span class="align-middle ml-1">Searching…</span>
    </div>

    {{-- Empty state --}}
    <div x-show="!loading && !hasResults" class="px-4 py-6 text-center text-sm text-on-surface-variant">
        No products found for “<span x-text="query.trim()"></span>”.
    </div>

    {{-- Results --}}
    <ul x-show="hasResults" class="max-h-[60vh] overflow-y-auto py-1" role="listbox">
        <template x-for="(item, index) in results" :key="item.id">
            <li>
                <a
                    :href="item.url"
                    class="flex items-center gap-3 px-3 py-2.5 transition-colors"
                    :class="highlighted === index ? 'bg-herbal-light' : 'hover:bg-surface-container'"
                    @mouseenter="highlighted = index"
                    role="option"
                    :aria-selected="highlighted === index"
                >
                    {{-- Thumbnail --}}
                    <span class="w-11 h-11 flex-shrink-0 rounded-lg bg-surface-container overflow-hidden flex items-center justify-center">
                        <template x-if="item.image">
                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" loading="lazy">
                        </template>
                        <template x-if="!item.image">
                            <span class="material-symbols-outlined text-outline text-[22px]">image</span>
                        </template>
                    </span>

                    {{-- Name + category --}}
                    <span class="flex-1 min-w-0">
                        <span class="block text-sm font-medium text-on-surface truncate" x-text="item.name"></span>
                        <span class="block text-xs text-on-surface-variant truncate" x-text="item.category"></span>
                    </span>

                    {{-- Price --}}
                    <span class="text-right flex-shrink-0">
                        <span class="block text-sm font-bold text-herbal-deep" x-text="item.price"></span>
                        <template x-if="item.on_sale">
                            <span class="block text-[11px] text-on-surface-variant line-through" x-text="item.original_price"></span>
                        </template>
                    </span>
                </a>
            </li>
        </template>
    </ul>

    {{-- View-all footer --}}
    <button
        x-show="hasResults"
        type="button"
        @click="submitSearch()"
        class="w-full text-center text-sm font-medium text-brand-forest hover:bg-herbal-light border-t border-outline-variant py-2.5 transition-colors"
    >
        View all <span x-text="total"></span> result<span x-show="total !== 1">s</span>
    </button>
</div>
