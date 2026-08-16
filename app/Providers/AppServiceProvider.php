<?php

namespace App\Providers;

use App\Listeners\SendWelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Require custom helper file manually so we don't depend on composer dump-autoload on live servers
        if (file_exists(app_path('Helpers/SettingsHelper.php'))) {
            require_once app_path('Helpers/SettingsHelper.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // -----------------------------------------------------------------------
        // Dynamic Mail Configuration from Database
        // -----------------------------------------------------------------------
        try {
            if ($host = setting('smtp_host')) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.host', $host);
                // An admin-configured SMTP host means they intend to send via SMTP,
                // so switch the active mailer away from the default (e.g. "log").
                \Illuminate\Support\Facades\Config::set('mail.default', 'smtp');
            }
            if ($port = setting('smtp_port')) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.port', $port);
            }
            if ($user = setting('smtp_user')) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.username', $user);
            }
            if ($password = setting('smtp_password')) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.password', $password);
            }
            if ($encryption = setting('smtp_encryption')) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.encryption', $encryption);
            }
            if ($fromAddress = setting('smtp_from_address')) {
                \Illuminate\Support\Facades\Config::set('mail.from.address', $fromAddress);
                \Illuminate\Support\Facades\Config::set('mail.from.name', setting('site_name', config('app.name')));
            }
        } catch (\Exception $e) {
            // Ignore if DB/table doesn't exist yet (e.g. during migrations)
        }
        // -----------------------------------------------------------------------
        // Password strength rules for production
        // -----------------------------------------------------------------------
        Password::defaults(function () {
            return Password::min(6);
        });

        // -----------------------------------------------------------------------
        // Password reset / email verification links branch on which surface
        // triggered them: a request made through the API (Next.js frontend)
        // links back to the frontend; the Blade storefront/admin keep their
        // original Blade routes. Both notifications are sent synchronously
        // within the request that triggers them, so request()->is('api/*')
        // reliably reflects the origin.
        // -----------------------------------------------------------------------
        VerifyEmail::createUrlUsing(function ($notifiable) {
            if (request()?->is('api/*')) {
                return URL::temporarySignedRoute(
                    'api.v1.auth.verify-email',
                    now()->addMinutes(60),
                    ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
                );
            }

            return URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
            );
        });

        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            if (request()?->is('api/*')) {
                return config('app.frontend_url').'/reset-password?token='.$token.'&email='.urlencode($notifiable->getEmailForPasswordReset());
            }

            return url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        });

        // Brand the password-reset email with the same markdown theme/copy
        // style used by the rest of the transactional emails, instead of the
        // generic default Laravel notification template.
        ResetPassword::toMailUsing(function ($notifiable, string $url) {
            return (new \Illuminate\Notifications\Messages\MailMessage())
                ->subject('Reset Your '.setting('site_name').' Password')
                ->markdown('emails.users.reset_password', ['url' => $url, 'notifiable' => $notifiable]);
        });

        // -----------------------------------------------------------------------
        // Transactional emails triggered by domain events
        // -----------------------------------------------------------------------
        Event::listen(Registered::class, SendWelcomeEmail::class);

        // -----------------------------------------------------------------------
        // Rate Limiting
        // -----------------------------------------------------------------------
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Coupon apply endpoint: max 10 attempts per minute per IP
        RateLimiter::for('coupon', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Review submit: max 5 per hour per user
        RateLimiter::for('reviews', function (Request $request) {
            return Limit::perHour(5)->by($request->user()?->id ?: $request->ip());
        });

        // Live search autocomplete: fires on keystroke, so allow a generous
        // per-minute budget while still capping abusive scraping.
        RateLimiter::for('search', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Registration: 5 accounts per 10 minutes per IP — blocks scripted
        // bulk account creation without affecting a real signup burst.
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinutes(10, 5)->by($request->ip());
        });

        // Forgot-password / reset-password: 5 attempts per 10 minutes, keyed
        // by IP + the submitted email so one bad actor can't either spam a
        // single victim's inbox with reset emails or brute-force many
        // accounts from one IP.
        RateLimiter::for('password-reset', function (Request $request) {
            $email = Str::lower((string) $request->input('email'));

            return Limit::perMinutes(10, 5)->by($request->ip().'|'.$email);
        });

        // Contact form: 5 submissions per 10 minutes per IP — keeps the
        // admin inbox usable without blocking a legitimate follow-up message.
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinutes(10, 5)->by($request->ip());
        });

        // -----------------------------------------------------------------------
        // View Composers
        // -----------------------------------------------------------------------
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            try {
                if (!$view->offsetExists('headerCategories')) {
                    $headerCategories = \Illuminate\Support\Facades\Cache::rememberForever(
                        \App\Support\Cache\CacheKeys::HEADER_CATEGORIES,
                        function () {
                            return \App\Models\Category::where('status', true)->whereNull('parent_id')->with('children')->orderBy('sort_order')->take(5)->get();
                        }
                    );
                    $view->with('headerCategories', $headerCategories);
                }
                if (!$view->offsetExists('footerPages')) {
                    $footerPages = \Illuminate\Support\Facades\Cache::rememberForever(
                        \App\Support\Cache\CacheKeys::FOOTER_PAGES,
                        function () {
                            return \App\Models\Page::where('status', true)->orderBy('title')->get();
                        }
                    );
                    $view->with('footerPages', $footerPages);
                }
            } catch (\Exception $e) {}
        });
    }
}
