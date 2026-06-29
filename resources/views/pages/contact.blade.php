@extends('layouts.app')

@section('content')
<main class="pt-32 pb-section-gap px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto min-h-screen flex flex-col">
<!-- Hero Section -->
<section class="mb-20 text-center max-w-3xl mx-auto">
<h1 class="font-display-lg text-display-lg text-on-surface mb-6">Get in Touch</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant">
                We are here to support your journey towards holistic wellness. Reach out to our Ayurvedic experts for personalized guidance, product inquiries, or support.
            </p>
</section>
<!-- Contact Grid -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-gutter mb-section-gap">
<!-- Left: Contact Form -->
<div class="md:col-span-7 bg-clinical-white p-8 md:p-12 border border-soft-border rounded">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-8">Send a Message</h2>
@if(session('success'))
    <div class="mb-6 p-4 bg-tertiary text-on-tertiary rounded">
        {{ session('success') }}
    </div>
@endif

<form class="space-y-6" method="POST" action="{{ route('contact.submit') }}">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block font-label-md text-label-md text-on-surface-variant mb-1" for="name">Full Name</label>
            <input class="input-minimal w-full border border-soft-border rounded px-4 py-2 @error('name') border-error @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Your Name" type="text" required />
            @error('name')<p class="text-error text-label-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block font-label-md text-label-md text-on-surface-variant mb-1" for="email">Email Address</label>
            <input class="input-minimal w-full border border-soft-border rounded px-4 py-2 @error('email') border-error @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" type="email" required />
            @error('email')<p class="text-error text-label-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
    <div>
        <label class="block font-label-md text-label-md text-on-surface-variant mb-1" for="subject">Subject</label>
        <input class="input-minimal w-full border border-soft-border rounded px-4 py-2 @error('subject') border-error @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="How can we help?" type="text" />
        @error('subject')<p class="text-error text-label-sm mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block font-label-md text-label-md text-on-surface-variant mb-1" for="message">Message</label>
        <textarea class="input-minimal w-full border border-soft-border rounded px-4 py-2 resize-none @error('message') border-error @enderror" id="message" name="message" placeholder="Your message details..." rows="4" required>{{ old('message') }}</textarea>
        @error('message')<p class="text-error text-label-sm mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="pt-4">
        <button class="bg-primary text-on-primary px-8 py-3 rounded hover:opacity-90 w-full md:w-auto flex justify-center items-center" type="submit">
            Send Inquiry <span class="material-symbols-outlined ml-2 text-[20px]">arrow_forward</span>
        </button>
    </div>
</form>
</div>
<!-- Right: Contact Details & Info -->
<div class="md:col-span-5 flex flex-col space-y-8">
<!-- Contact Info Card -->
<div class="bg-surface-bright p-8 border border-soft-border rounded flex-grow">
<h3 class="font-headline-md text-headline-md text-on-surface mb-6">Contact Information</h3>
<div class="space-y-6">
<div class="flex items-start">
<span class="material-symbols-outlined text-primary mt-1 mr-4">location_on</span>
<div>
<p class="font-label-md text-label-md text-on-surface-variant mb-1">Office Address</p>
<p class="font-body-md text-body-md text-on-surface">{!! nl2br(e(setting('address', "Kare Ons Herbal Wellness Center\nMumbai, India"))) !!}</p>
</div>
</div>
<div class="flex items-start">
<span class="material-symbols-outlined text-primary mt-1 mr-4">phone</span>
<div>
<p class="font-label-md text-label-md text-on-surface-variant mb-1">Phone</p>
<p class="font-body-md text-body-md text-on-surface">{{ setting('site_phone', '+91 9315436116') }}</p>
</div>
</div>
<div class="flex items-start">
<span class="material-symbols-outlined text-primary mt-1 mr-4">mail</span>
<div>
<p class="font-label-md text-label-md text-on-surface-variant mb-1">Email</p>
<p class="font-body-md text-body-md text-on-surface">{{ setting('site_email', 'info@kareons.com') }}</p>
</div>
</div>
</div>
</div>
<!-- Support Hours -->
<div class="bg-primary/5 p-8 border border-primary/20 rounded">
<div class="flex items-center mb-4">
<span class="material-symbols-outlined text-primary mr-3">schedule</span>
<h3 class="font-headline-md text-headline-md text-on-surface">Support Hours</h3>
</div>
<p class="font-body-md text-body-md text-on-surface-variant">
                        Monday - Saturday<br/>
                        9:00 AM - 6:00 PM (IST)
                    </p>
<p class="font-label-sm text-label-sm text-primary mt-4 uppercase tracking-wider">
                        Available for Consultations
                    </p>
</div>
</div>
</div>
<!-- Map & FAQ Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
<!-- Stylized Map Placeholder -->
<div class="relative h-64 md:h-auto bg-surface-container-high rounded overflow-hidden border border-soft-border">
<img class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-multiply" data-alt="A stylized, minimalist map showing the location of a wellness center in Mumbai. The map uses a light clinical aesthetic with soft gray roads and subtle electric purple accents highlighting the specific building location. The design is clean, geometric, and modern, avoiding realistic satellite textures in favor of a premium, vector-like corporate aesthetic." data-location="Mumbai, India" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCQ0iYIsFwceywDYuJb7DCKc_d5Ik0fNniNklW3S4luOqf2LmfU39r5ThkrOStGoWC_9b-pnKcia92WRp2SYgLuO5h_h6DlCi2xEN0OdT6oMYnVzpi5sneMpOMCphzXjCQGSNpel7o-hCyzfqtnlmWb9EMIQIfeOl9hvNY7O4IpNCu4lCaPxG8mDlQJxQTlktayoyO4cHzZJ_eNECw7AxZPLNg5WovWLdalwBPER-6moND3v_VpfXw2YlRxQQyfWeUT5z8CBmzXhJj9"/>
<div class="absolute inset-0 bg-gradient-to-t from-surface/80 to-transparent"></div>
<div class="absolute bottom-6 left-6">
<span class="bg-clinical-white/90 backdrop-blur px-4 py-2 rounded text-label-md font-label-md text-primary shadow-sm border border-soft-border">Mumbai Office</span>
</div>
</div>
<!-- FAQ CTA -->
<div class="bg-clinical-white p-8 md:p-12 border border-soft-border rounded flex flex-col justify-center items-start">
<span class="material-symbols-outlined text-4xl text-outline mb-4">help_center</span>
<h3 class="font-headline-md text-headline-md text-on-surface mb-4">Quick Answers</h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">
                    Before reaching out, you might find what you need in our comprehensive Help Center, featuring guides on Ayurvedic principles and order support.
                </p>
<a class="inline-flex items-center text-primary font-label-md text-label-md hover:underline" href="#">
                    Visit Help Center <span class="material-symbols-outlined ml-1 text-[18px]">arrow_forward</span>
</a>
</div>
</div>
</main>
@endsection
