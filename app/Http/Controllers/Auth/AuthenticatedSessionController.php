<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $oldSessionId = $request->session()->getId(); // Get session ID before regeneration
        
        $request->authenticate();

        $request->session()->regenerate();

        // Migrate Cart Items
        $userId = Auth::id();
        $guestItems = \App\Models\CartItem::where('session_id', $oldSessionId)->get();
        foreach ($guestItems as $item) {
            $existing = \App\Models\CartItem::where('user_id', $userId)->where('product_id', $item->product_id)->first();
            if ($existing) {
                $existing->quantity += $item->quantity;
                $existing->save();
                $item->delete();
            } else {
                $item->update(['user_id' => $userId, 'session_id' => null]);
            }
        }

        if ($request->user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
