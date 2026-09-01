<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A manually-seeded 301/302 redirect from an old URL path to a new one.
 * Looked up by the Route::fallback() handler in routes/web.php whenever no
 * other route matches the incoming request, so legacy/changed slugs don't
 * simply 404 and drop any inbound links or search ranking they'd built up.
 *
 * There is no admin UI for these yet — add/update entries via
 * `php artisan tinker` or a direct DB insert, e.g.:
 *
 *   Redirect::create(['from_path' => 'product/old-slug', 'to_path' => 'product/new-slug']);
 */
class Redirect extends Model
{
    protected $fillable = [
        'from_path',
        'to_path',
        'status_code',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
        ];
    }
}
