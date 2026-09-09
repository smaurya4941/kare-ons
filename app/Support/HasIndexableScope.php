<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

/**
 * Shared `is_indexable` handling for content models (Product, Category, Blog,
 * Page). The `is_indexable` column is added by
 * 2026_08_29_140000_add_seo_fields_to_products_and_categories_tables and
 * 2026_08_29_143523_add_seo_parity_to_blogs_and_pages_tables — if those
 * migrations have not run yet on an environment, `scopeIndexable()` silently
 * degrades to "no noindex filter" instead of throwing
 * "Unknown column 'is_indexable'" (which used to 500 the sitemap).
 */
trait HasIndexableScope
{
    /** Per-table memo so we don't hit information_schema on every query. */
    protected static array $isIndexableColumnCache = [];

    protected function isIndexableColumnPresent(): bool
    {
        $table = $this->getTable();

        return static::$isIndexableColumnCache[$table]
            ??= Schema::hasColumn($table, 'is_indexable');
    }

    /**
     * Constrain a query to records that are safe to expose to search engines
     * (the model's own "active" condition, plus `is_indexable = true` when
     * that column exists).
     */
    public function scopeIndexable(Builder $query): Builder
    {
        $this->applyActiveConstraint($query);

        if ($this->isIndexableColumnPresent()) {
            $query->where('is_indexable', true);
        }

        return $query;
    }

    /**
     * The model's baseline "publicly visible" constraint. Override per model
     * (e.g. Blog uses its own `published()` scope).
     */
    protected function applyActiveConstraint(Builder $query): void
    {
        $query->where('status', true);
    }
}
