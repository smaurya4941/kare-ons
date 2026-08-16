<?php

namespace App\Listeners;

use App\Mail\WelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Fires for every new account regardless of surface (Blade storefront
 * registration or the Next.js frontend via the API), since both paths
 * dispatch the same Illuminate\Auth\Events\Registered event.
 *
 * Not itself queued: WelcomeEmail already implements ShouldQueue, so calling
 * ->send() here just enqueues the mail job and returns immediately — wrapping
 * this listener in ShouldQueue too would only add a redundant queue hop.
 */
class SendWelcomeEmail
{
    public function handle(Registered $event): void
    {
        try {
            Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
        } catch (\Throwable $e) {
            Log::error('Welcome email failed: ' . $e->getMessage(), ['user_id' => $event->user->id]);
        }
    }
}
