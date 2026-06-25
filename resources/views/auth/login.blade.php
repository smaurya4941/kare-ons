<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-surface-container-lowest py-24">
        
        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-surface shadow-md overflow-hidden sm:rounded-lg border border-outline-variant carbon-border">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-semibold text-on-surface">Welcome Back</h2>
                <p class="text-sm text-secondary mt-2">Sign in to your Kare ONS Herbals account.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-medium text-sm text-on-surface">Email Address</label>
                    <input id="email" class="block mt-1 w-full border-outline-variant focus:border-primary focus:ring-primary rounded-md shadow-sm" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center">
                        <label for="password" class="block font-medium text-sm text-on-surface">Password</label>
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-secondary hover:text-primary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                    <input id="password" class="block mt-1 w-full border-outline-variant focus:border-primary focus:ring-primary rounded-md shadow-sm"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-outline-variant text-primary shadow-sm focus:ring-primary" name="remember">
                        <span class="ms-2 text-sm text-secondary">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full justify-center inline-flex items-center px-6 py-3 bg-primary border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-on-primary-fixed-variant focus:bg-on-primary-fixed-variant active:bg-on-primary-fixed-variant focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Log in') }}
                    </button>
                </div>
                
                <div class="text-center mt-6">
                    <p class="text-sm text-secondary">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-medium text-primary hover:underline">Create one</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
