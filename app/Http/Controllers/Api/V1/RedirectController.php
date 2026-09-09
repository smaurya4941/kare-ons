<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\Request;

/**
 * Exposes the `redirects` table (see App\Models\Redirect) to headless
 * frontends. The Blade storefront gets this for free via the
 * NotFoundHttpException render hook in bootstrap/app.php, but that hook only
 * runs for non-api web requests — a Next.js/SPA frontend that resolves 404s
 * against this JSON API needs an explicit lookup instead.
 */
class RedirectController extends Controller
{
    public function lookup(Request $request)
    {
        $path = trim((string) $request->query('path', ''), '/');

        if ($path === '') {
            return response()->json(['message' => 'The path field is required.'], 422);
        }

        $redirect = Redirect::where('from_path', $path)->first();

        if (! $redirect) {
            return response()->json(['message' => 'No redirect found.'], 404);
        }

        return response()->json([
            'data' => [
                'to_path' => $redirect->to_path,
                'status_code' => $redirect->status_code,
            ],
        ]);
    }
}
