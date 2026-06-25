<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-surface-container-lowest py-24">
        
        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-surface shadow-md overflow-hidden sm:rounded-lg border border-outline-variant carbon-border">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-semibold text-on-surface">Create an Account</h2>
                <p class="text-sm text-secondary mt-2">Join Kare ONS Herbals today.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block font-medium text-sm text-on-surface">Full Name</label>
                    <input id="name" class="block mt-1 w-full border-outline-variant focus:border-primary focus:ring-primary rounded-md shadow-sm" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-medium text-sm text-on-surface">Email Address</label>
                    <input id="email" class="block mt-1 w-full border-outline-variant focus:border-primary focus:ring-primary rounded-md shadow-sm" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                
                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block font-medium text-sm text-on-surface">Phone Number</label>
                    <input id="phone" class="block mt-1 w-full border-outline-variant focus:border-primary focus:ring-primary rounded-md shadow-sm" type="tel" name="phone" value="{{ old('phone') }}" required />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-medium text-sm text-on-surface">Password</label>
                    <input id="password" class="block mt-1 w-full border-outline-variant focus:border-primary focus:ring-primary rounded-md shadow-sm"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block font-medium text-sm text-on-surface">Confirm Password</label>
                    <input id="password_confirmation" class="block mt-1 w-full border-outline-variant focus:border-primary focus:ring-primary rounded-md shadow-sm"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a class="underline text-sm text-secondary hover:text-on-surface rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-on-primary-fixed-variant focus:bg-on-primary-fixed-variant active:bg-on-primary-fixed-variant focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
