<x-app-layout>
<div class="min-h-screen flex items-center justify-center p-4 md:p-16 relative overflow-hidden bg-gradient-to-br from-[#f8f9fa] via-white to-[#f3f4f5]">
    <!-- Register Container -->
    <main class="w-full max-w-md z-10">
        <!-- Brand / Identity Context -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <h1 class="font-headline-lg text-4xl font-bold text-primary tracking-tight">Kare Ons Herbal</h1>
            </a>
            <p class="font-body-md text-base text-on-surface-variant mt-2">Pure Ayurveda, Elevated.</p>
        </div>
        
        <!-- Registration Card -->
        <div class="bg-white border border-soft-border rounded-xl shadow-sm p-8 md:p-10 transition-all duration-300 hover:border-outline-variant hover:shadow-lg relative overflow-hidden group">
            <!-- Subtle decorative top bar -->
            <div class="absolute top-0 left-0 w-full h-1 bg-surface-container-low group-hover:bg-primary transition-colors duration-500"></div>
            
            <div class="mb-8">
                <h2 class="font-headline-md text-2xl font-bold text-on-surface mb-2">Create Account</h2>
                <p class="font-body-md text-base text-on-surface-variant">Join the community for elevated learning and authentic Ayurvedic remedies.</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                
                <!-- Full Name -->
                <div>
                    <label class="block font-label-sm text-xs font-medium text-on-surface-variant uppercase tracking-wider mb-2" for="name">Full Name</label>
                    <div class="relative">
                        <input class="w-full border-0 border-b border-soft-border bg-transparent focus:ring-0 focus:border-primary transition-colors text-on-surface placeholder:text-outline py-2 @error('name') border-error @enderror" 
                               id="name" name="name" type="text" value="{{ old('name') }}" placeholder="e.g. Charaka Samhita" required autofocus autocomplete="name">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block font-label-sm text-xs font-medium text-on-surface-variant uppercase tracking-wider mb-2" for="email">Email Address</label>
                    <div class="relative">
                        <input class="w-full border-0 border-b border-soft-border bg-transparent focus:ring-0 focus:border-primary transition-colors text-on-surface placeholder:text-outline py-2 @error('email') border-error @enderror" 
                               id="email" name="email" type="email" value="{{ old('email') }}" placeholder="your@email.com" required autocomplete="username">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>
                
                <!-- Phone -->
                <div>
                    <label class="block font-label-sm text-xs font-medium text-on-surface-variant uppercase tracking-wider mb-2" for="phone">Phone Number</label>
                    <div class="relative">
                        <input class="w-full border-0 border-b border-soft-border bg-transparent focus:ring-0 focus:border-primary transition-colors text-on-surface placeholder:text-outline py-2 @error('phone') border-error @enderror"
                               id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="e.g. +91 98765 43210" required autocomplete="tel">
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>

                <!-- Password -->
                <div>
                    <label class="block font-label-sm text-xs font-medium text-on-surface-variant uppercase tracking-wider mb-2" for="password">Password</label>
                    <div class="relative">
                        <input class="w-full border-0 border-b border-soft-border bg-transparent focus:ring-0 focus:border-primary transition-colors text-on-surface placeholder:text-outline py-2 @error('password') border-error @enderror" 
                               id="password" name="password" type="password" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label class="block font-label-sm text-xs font-medium text-on-surface-variant uppercase tracking-wider mb-2" for="password_confirmation">Confirm Password</label>
                    <div class="relative">
                        <input class="w-full border-0 border-b border-soft-border bg-transparent focus:ring-0 focus:border-primary transition-colors text-on-surface placeholder:text-outline py-2 @error('password_confirmation') border-error @enderror" 
                               id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>
                
                <!-- Action Area -->
                <div class="pt-4">
                    <button class="w-full bg-primary text-white font-label-md text-sm font-medium py-3 px-6 rounded transition-colors duration-300 hover:bg-primary/90 flex justify-center items-center gap-2 active:scale-95 shadow-md" type="submit">
                        <span>Create Account</span>
                        <span aria-hidden="true" class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
            </form>
            
            <!-- Secondary Actions -->
            <div class="mt-8 text-center">
                <p class="font-body-md text-base text-on-surface-variant">
                    Already have an account? 
                    <a class="text-primary font-semibold hover:text-primary/80 transition-colors hover:underline underline-offset-4" href="{{ route('login') }}">Login</a>
                </p>
            </div>
        </div>
        
        <!-- Subtle Trust / Policy Link -->
        <div class="text-center mt-6">
            <p class="font-label-sm text-xs font-medium text-outline">
                By registering, you agree to our 
                <a class="hover:text-primary transition-colors border-b border-soft-border hover:border-primary pb-0.5" href="#">Privacy Policy</a> & 
                <a class="hover:text-primary transition-colors border-b border-soft-border hover:border-primary pb-0.5" href="#">Terms of Service</a>.
            </p>
        </div>
    </main>
</div>
</x-app-layout>
