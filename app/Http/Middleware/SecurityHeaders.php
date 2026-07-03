<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds conservative, widely-compatible security response headers.
 *
 * Deliberately omits a Content-Security-Policy: a strict CSP would need to be
 * tuned against the Razorpay checkout, Google Fonts and inline scripts already
 * in the layout, and getting it wrong silently breaks payments. Add one
 * separately once those sources are enumerated.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Only advertise HSTS over HTTPS so local/http development is unaffected.
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
