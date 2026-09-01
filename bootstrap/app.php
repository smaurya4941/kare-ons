<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // Attach conservative security headers to every web response.
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        // Razorpay posts server-to-server and cannot send a CSRF token.
        $middleware->validateCsrfTokens(except: [
            'webhooks/razorpay',
        ]);

        // Every /api/* request gets the shared "api" rate limiter (60/min per
        // user or IP) defined in AppServiceProvider.
        $middleware->api(append: [
            'throttle:api',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Before rendering a 404 for a web request, check whether the path
        // has a manually-configured redirect (e.g. a product/category/page
        // slug that changed) so old inbound/indexed links 301 to the new
        // location instead of dying. See App\Models\Redirect.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return null;
            }

            $redirect = \App\Models\Redirect::where('from_path', trim($request->path(), '/'))->first();

            if ($redirect) {
                return redirect($redirect->to_path, $redirect->status_code);
            }

            return null;
        });

        // Render JSON (never a Blade error page) for every /api/* request,
        // with status codes matching the exception type.
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
            }

            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json(['message' => $e->getMessage() ?: 'This action is unauthorized.'], 403);
            }

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
                || $e instanceof \Symfony\Component\Routing\Exception\RouteNotFoundException
                || $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json(['message' => 'Resource not found.'], 404);
            }

            if ($e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
                return response()->json(['message' => 'Too many requests. Please try again later.'], 429);
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                return response()->json(
                    ['message' => $e->getMessage() ?: 'An error occurred.'],
                    $e->getStatusCode()
                );
            }

            report($e);

            return response()->json(['message' => 'An unexpected error occurred. Please try again.'], 500);
        });
    })->create();
