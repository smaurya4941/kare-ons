/**
 * Live search autocomplete for the header search box.
 *
 * Handles: open/close of the search panel, debounced fetching against
 * `search.suggest`, keyboard navigation (arrow keys / enter / escape),
 * request cancellation, and basic error handling.
 *
 * Usage in Blade:
 *   <div x-data="productSearch({ endpoint: '{{ route('search.suggest') }}', shopUrl: '{{ route('shop.index') }}' })">
 */
export default function productSearch({ endpoint, shopUrl, minChars = 2, debounce = 250, initial = '' } = {}) {
    return {
        endpoint,
        shopUrl,
        minChars,

        open: false,        // panel (mobile/expandable input) visible
        query: initial,
        results: [],
        total: 0,
        loading: false,
        highlighted: -1,    // index of keyboard-focused result (-1 = none)

        _timer: null,
        _controller: null,  // AbortController for the in-flight request

        /** Open the search field and focus the input on the next tick. */
        openSearch() {
            this.open = true;
            this.$nextTick(() => this.$refs.input?.focus());
        },

        /** Close the panel and reset transient UI state (keeps the typed query). */
        closeSearch() {
            this.open = false;
            this.highlighted = -1;
        },

        /**
         * Inline mode (search box embedded in a filter form): opening the dropdown
         * on focus, and fetching immediately if there's already a usable query.
         */
        onFocus() {
            this.open = true;
            if (this.query.trim().length >= this.minChars && !this.hasResults) {
                this.fetchResults(this.query.trim());
            }
        },

        /** Whether the dropdown should be shown. */
        get showDropdown() {
            return this.open && this.query.trim().length >= this.minChars;
        },

        get hasResults() {
            return this.results.length > 0;
        },

        /** Debounced entrypoint bound to the input's `@input`. */
        onInput() {
            this.highlighted = -1;
            clearTimeout(this._timer);

            const term = this.query.trim();
            if (term.length < this.minChars) {
                this.results = [];
                this.total = 0;
                this.loading = false;
                this._abort();
                return;
            }

            this._timer = setTimeout(() => this.fetchResults(term), this.debounce);
        },

        debounce,

        async fetchResults(term) {
            // Cancel any in-flight request so responses can't arrive out of order.
            this._abort();
            this._controller = new AbortController();
            this.loading = true;

            try {
                const url = `${this.endpoint}?q=${encodeURIComponent(term)}`;
                const response = await fetch(url, {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    signal: this._controller.signal,
                });

                if (!response.ok) {
                    // 429 (rate limit) / 422 / 5xx — fail quietly to an empty list.
                    this.results = [];
                    this.total = 0;
                    return;
                }

                const data = await response.json();

                // Guard against a stale response for a query the user has since edited.
                if (data.query !== undefined && data.query.trim() !== this.query.trim()) {
                    return;
                }

                this.results = data.results ?? [];
                this.total = data.total ?? this.results.length;
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('Search failed:', error);
                    this.results = [];
                    this.total = 0;
                }
            } finally {
                // Only clear loading if this is still the active controller.
                if (!this._controller || this._controller.signal.aborted === false) {
                    this.loading = false;
                }
            }
        },

        // --- Keyboard navigation -------------------------------------------------

        highlightNext() {
            if (!this.hasResults) return;
            this.highlighted = (this.highlighted + 1) % this.results.length;
        },

        highlightPrev() {
            if (!this.hasResults) return;
            this.highlighted =
                this.highlighted <= 0 ? this.results.length - 1 : this.highlighted - 1;
        },

        /** Enter: go to the highlighted product, else run a full search. */
        onEnter() {
            if (this.highlighted >= 0 && this.results[this.highlighted]) {
                window.location.href = this.results[this.highlighted].url;
                return;
            }
            this.submitSearch();
        },

        /**
         * Enter handler for inline mode. If a suggestion is highlighted we jump
         * straight to it; otherwise we let the surrounding filter form submit
         * naturally (preserving category/price filters) by not preventing default.
         */
        onEnterInline(event) {
            if (this.highlighted >= 0 && this.results[this.highlighted]) {
                event.preventDefault();
                window.location.href = this.results[this.highlighted].url;
            }
        },

        /** Navigate to the full shop results page for the current query. */
        submitSearch() {
            const term = this.query.trim();
            if (term.length === 0) return;
            window.location.href = `${this.shopUrl}?search=${encodeURIComponent(term)}`;
        },

        _abort() {
            if (this._controller) {
                this._controller.abort();
                this._controller = null;
            }
        },
    };
}
