<x-app-layout>
<div class="bg-background text-on-background min-h-screen flex items-center justify-center py-24 px-4 md:px-16 antialiased">
    <main class="w-full max-w-md">
        <!-- Brand Logo -->
        <div class="text-center mb-12">
            <a href="{{ route('home') }}" class="font-headline-lg text-4xl font-bold text-primary tracking-tight inline-block">Kare Ons Herbal</a>
            <p class="font-label-md text-sm font-medium text-on-surface-variant uppercase tracking-widest mt-2">Elevated Learning & Pure Ayurveda</p>
        </div>
        
        <!-- Forgot Password Card -->
        <div class="bg-white p-8 md:p-12 border border-soft-border rounded-xl shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <!-- Subtle decorative element indicating 'Electric Purple' accent -->
            <div class="absolute top-0 left-0 w-full h-1 bg-primary transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500 ease-out"></div>
            
            <div class="mb-8">
                <h2 class="font-headline-md text-2xl font-bold text-on-surface mb-2">Reset Password</h2>
                <p class="font-body-md text-base text-on-surface-variant">Enter the email address associated with your account, and we'll send you a link to reset your password.</p>
            </div>
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <!-- Email Input -->
                <div>
                    <label class="sr-only" for="email">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-surface-variant">
                            <span aria-hidden="true" class="material-symbols-outlined text-lg">mail</span>
                        </div>
                        <input class="block w-full pl-10 pr-3 py-3 bg-transparent border-0 border-b border-soft-border focus:ring-0 focus:border-primary transition-colors text-on-surface placeholder:text-on-surface-variant/50 @error('email') border-error @enderror" 
                               id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>
                
                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary font-label-md text-sm font-medium uppercase tracking-wider active:scale-95 transition-all duration-200 shadow-sm">
                        Send Reset Link
                        <span aria-hidden="true" class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </button>
                </div>
            </form>
            
            <!-- Back to Login Link -->
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center font-label-md text-sm font-medium text-on-surface-variant hover:text-primary transition-colors duration-200 group">
                    <span aria-hidden="true" class="material-symbols-outlined mr-2 text-sm transform group-hover:-translate-x-1 transition-transform duration-200">arrow_back</span>
                    Back to Login
                </a>
            </div>
        </div>
        
        <!-- Trust Badge / Subtext -->
        <div class="text-center mt-12">
            <p class="font-label-sm text-xs font-medium text-outline flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">lock</span>
                Secure & Encrypted Connection
            </p>
        </div>
    </main>
</div>
</x-app-layout>
