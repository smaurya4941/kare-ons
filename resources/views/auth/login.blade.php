<x-app-layout>
<div class="min-h-screen flex items-center justify-center p-4 md:p-16 relative overflow-hidden bg-background">
    <!-- Subtle ambient background decoration -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 opacity-30 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[50vw] h-[50vw] rounded-full bg-primary-fixed blur-[120px]"></div>
        <div class="absolute bottom-[-20%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-surface-container blur-[100px]"></div>
    </div>
    
    <!-- Login Container -->
    <main class="w-full max-w-[480px] bg-white/70 backdrop-blur-md border border-soft-border rounded-xl shadow-sm p-8 md:p-12 relative z-10 flex flex-col gap-8">
        <!-- Header -->
        <header class="text-center flex flex-col gap-2">
            <a href="{{ route('home') }}" class="inline-block">
                <h1 class="font-display-lg text-4xl font-bold text-primary">Kare Ons Herbal</h1>
            </a>
            <p class="font-body-lg text-lg text-on-surface-variant mt-2">Pure Ayurvedic &amp; Herbal Products</p>
        </header>
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6">
            @csrf
            
            <!-- Email Input -->
            <div class="flex flex-col gap-2 group">
                <label class="font-label-sm text-xs font-medium text-on-surface-variant uppercase tracking-wider" for="email">Email Address</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-0 text-outline group-focus-within:text-primary transition-colors duration-300 pointer-events-none" style="font-variation-settings: 'FILL' 0;">mail</span>
                    <input class="w-full py-3 pl-8 border-0 border-b border-soft-border bg-transparent focus:ring-0 focus:border-primary transition-colors text-on-surface placeholder:text-outline-variant @error('email') border-error @enderror" 
                           id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
            
            <!-- Password Input -->
            <div class="flex flex-col gap-2 group">
                <div class="flex justify-between items-center">
                    <label class="font-label-sm text-xs font-medium text-on-surface-variant uppercase tracking-wider" for="password">Password</label>
                    @if (Route::has('password.request'))
                        <a class="font-label-sm text-xs font-medium text-primary hover:underline underline-offset-4" href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-0 text-outline group-focus-within:text-primary transition-colors duration-300 pointer-events-none" style="font-variation-settings: 'FILL' 0;">lock</span>
                    <input class="w-full py-3 pl-8 pr-10 border-0 border-b border-soft-border bg-transparent focus:ring-0 focus:border-primary transition-colors text-on-surface placeholder:text-outline-variant @error('password') border-error @enderror" 
                           id="password" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>
            
            <!-- Remember Me -->
            <div class="block">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-outline-variant text-primary shadow-sm focus:ring-primary" name="remember">
                    <span class="ms-2 text-sm text-on-surface-variant">{{ __('Remember me') }}</span>
                </label>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col gap-4 mt-4">
                <button type="submit" class="bg-primary hover:bg-primary/90 text-white w-full py-4 rounded-lg font-label-md text-sm font-medium uppercase tracking-wider flex items-center justify-center gap-2 transition-all active:scale-95 shadow-md hover:shadow-lg">
                    Sign In
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
                <p class="text-center font-body-md text-base text-on-surface-variant mt-4">
                    New to Kare Ons? 
                    <a class="font-headline-md text-base font-semibold text-primary hover:underline underline-offset-4 ml-1" href="{{ route('register') }}">Create an Account</a>
                </p>
            </div>
        </form>
    </main>
</div>
</x-app-layout>
