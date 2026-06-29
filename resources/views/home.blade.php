@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="relative h-[90vh] flex items-center overflow-hidden">
<div class="absolute inset-0 z-0">
<div class="w-full h-full bg-cover bg-center opacity-40" data-alt="Hero Background" style='background-image: url("{{ setting('home_hero_bg') ? asset('storage/' . setting('home_hero_bg')) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuAwFEioZmdWOcM4DVlVqnXarX4GXg6E_3pqPGJ9qld0IO7yJ_CZhfX3Zwp-qnfpt__FyCN0_K5j531wTlQtqctdZg3Gx1pIH1uh9nusroOJkFg-4k2bteEfXQ3FfZQIDnCXtiDUChXHGayo4eI99Ax69rK1C_Q5P-r5_2mvlw808GrBotWHQg3L3Yq3SXQFczGMbSu5GDBz2jZMqe-gC6IKOGxWpGBYz3K0_d73R6J4v4oHQVyVshr45hwO4DUnZ9rqUUIVRqOPdnU' }}");'></div>
<div class="absolute inset-0 bg-gradient-to-r from-background via-background/80 to-transparent"></div>
</div>
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop relative z-10 w-full">
<div class="max-w-2xl">
<div class="inline-flex items-center gap-2 px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full mb-8">
<span class="material-symbols-outlined text-[18px]">verified</span>
<span class="text-label-sm font-bold uppercase tracking-wider">GMP Certified Excellence</span>
</div>
<h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-background mb-6">{!! setting('home_hero_title', 'Nature\'s Wisdom, <br/><span class="text-secondary">Refined by Science.</span>') !!}</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-10 leading-relaxed">
                        {!! setting('home_hero_subtitle', 'Pioneering clinical-grade Ayurvedic medicine through rigorous scientific validation. Our state-of-the-art manufacturing facilities deliver holistic wellness solutions that honor ancient traditions while meeting modern pharmaceutical standards.') !!}
                    </p>
<div class="flex flex-wrap gap-4">
<a href="{{ setting('home_cta_link', route('shop.index')) }}" class="bg-primary text-on-primary px-10 py-4 rounded-full font-label-md text-label-md hover:scale-105 transition-transform duration-300">{{ setting('home_cta_text', 'Start Your Inquiry') }}</a>
<a href="{{ setting('home_cta_link', route('shop.index')) }}" class="border border-secondary text-secondary px-10 py-4 rounded-full font-label-md text-label-md flex items-center gap-2 hover:bg-secondary/5 transition-colors">
<span class="material-symbols-outlined">play_circle</span>
                            Our Process
                        </a>
</div>
</div>
</div>
</section>
<!-- Trusted Logos -->
<section class="bg-surface py-12 border-y border-outline-variant/20">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<p class="text-center text-label-sm text-outline uppercase tracking-[0.2em] mb-8 font-bold">Standard of Excellence Certifications</p>
<div class="flex flex-wrap justify-between items-center gap-8 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
<div class="flex items-center gap-2"><span class="text-headline-sm font-bold">GMP </span><span class="text-label-sm">Certified</span></div>
<div class="flex items-center gap-2"><span class="text-headline-sm font-bold">ISO 9001:2015</span></div>
<div class="flex items-center gap-2"><span class="text-headline-sm font-bold">AYUSH</span><span class="text-label-sm">Premium Mark</span></div>
<div class="flex items-center gap-2"><span class="text-headline-sm font-bold">PAN INDIA</span><span class="text-label-sm">Distribution</span></div>
<div class="flex items-center gap-2"><span class="text-headline-sm font-bold">FDA</span><span class="text-label-sm">Compliant</span></div>
</div>
</div>
</section>
{{-- 
<!-- Heritage Section -->
<section class="py-section-gap">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-center">
<div class="relative">
<div class="aspect-square w-full clinical-card overflow-hidden rounded-xl transition-all duration-700 opacity-100 translate-y-0">
<div class="w-full h-full bg-cover bg-center" data-alt="A wide-angle, high-fidelity photograph of a modern, sterile Ayurvedic pharmaceutical manufacturing facility. Stainless steel extraction equipment and clean white laboratory surfaces are visible under bright, cool-toned professional lighting. The environment is impeccably clean, conveying over 25 years of heritage merged with innovative pharmaceutical technology in a professional medical aesthetic." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD4Ep4TFx9gwlJyWONaFoNAf9CFeUp5Mi-FHEKip2ilNBj31zA1Sfro7GbZoiGk04X_H_ymUhCLP7_c_vV1AFXU4BdwXXctI6YCLQ7X2TADqLzJtxQocmu2DWANOrkSSgL_tzV4DVwkkbTpQEdNfQOZZ5k52nKr1CRWAOF5iTloe6RNDDCnSeyOWpS0hIfTE5o_04JuMJQBdaeUFGJ6LwTbw-q4zc1FGLXPH8wwz_WEilf06WxNOe7nmRNLH4YaBH6xH7DJblUZo34");'></div>
</div>
<div class="absolute -bottom-5 right-0 md:-bottom-10 md:-right-10 bg-tertiary-container text-on-tertiary p-6 md:p-8 rounded-xl shadow-2xl z-10">
<p class="text-[48px] md:text-[64px] font-display-lg leading-none mb-2">25<span class="text-secondary-fixed text-[48px] md:text-display-lg">+</span></p>
<p class="font-label-md text-on-tertiary-container uppercase tracking-widest">Years of Clinical<br/>Heritage</p>
</div>
</div>
<div>
<h2 class="font-headline-md text-headline-md mb-6">Bridging Tradition &amp; Innovation </h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-8 leading-relaxed">
                        At Kare ONS Herbals, we believe that the future of medicine lies at the intersection of ancient Ayurvedic wisdom and modern clinical precision. For over two decades, we have dedicated our expertise to refining botanical extracts into therapeutic solutions that are both safe and effective.
                    </p>
<ul class="space-y-4 mb-10">
<li class="flex items-start gap-4">
<span class="material-symbols-outlined text-secondary mt-1">check_circle</span>
<span class="font-body-md">In-house R&amp;D and quality control laboratories</span>
</li>
<li class="flex items-start gap-4">
<span class="material-symbols-outlined text-secondary mt-1">check_circle</span>
<span class="font-body-md">Sustainably sourced, high-potency raw herbs</span>
</li>
<li class="flex items-start gap-4">
<span class="material-symbols-outlined text-secondary mt-1">check_circle</span>
<span class="font-body-md">Standardized manufacturing protocols for global compliance</span>
</li>
</ul>
<a href="{{ route('shop.index') }}" class="inline-block bg-primary text-on-primary px-8 py-3 rounded-full font-label-md hover:bg-on-surface-variant transition-colors">Our Full Story</a>
</div>
</div>
</section>
--}}
<!-- Curated Ayurvedic Collections (Imported from SCREEN_18) -->
<section class="py-section-gap bg-surface-container-low">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="mb-12">
<span class="text-label-sm font-bold text-secondary uppercase tracking-widest mb-3 block">Our Portfolio</span>
<h2 class="font-headline-md text-headline-md text-on-background">Curated Ayurvedic Collections</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
<!-- Large Card: Ayurvedic Syrups -->
<div class="md:col-span-8 group relative overflow-hidden rounded-xl clinical-card h-[450px]">
<img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Herbal syrups packaging" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDIyT1Q97U-DHduopfWXpDRUsd0fh1C_liyRyo7D-3lqL72IrrSRayBzJ4hEqQDt0sreTB1hfFl0wyCFi4tjIDOQ4MtV-lwqM3Li2KNvjg1bUzRusBszYIAP0_0NucQz5TFNHF1NC6JH7RVN4KSTpfJhpQMIavKGelMwVZQekANpfe2hrKfpyv5joFjM59Rx7m3u6Np1kMClHZCIX73zY_SwEW4Fubrs2Mo9vKAYAqcYhro_aqkcxW5X0btxNcxM6TEpyIxyfMOdv8"/>
<div class="absolute inset-0 portfolio-overlay"></div>
<div class="absolute bottom-0 left-0 p-10 text-white w-full">
<h3 class="font-headline-sm text-headline-sm mb-2 text-white">Ayurvedic Syrups</h3>
<p class="font-body-md text-white/80 mb-6 max-w-sm">Potent formulations for immunity, digestion, and daily vitality.</p>
<a href="{{ route('shop.index', ['category' => 'immunity-boosters']) }}" class="inline-block bg-secondary text-on-secondary px-6 py-2.5 rounded-full text-label-md font-bold hover:scale-105 transition-all">Explore Range</a>
</div>
</div>
<!-- Small Card: Herbal Capsules -->
<div class="md:col-span-4 group relative overflow-hidden rounded-xl clinical-card h-[450px]">
<img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Herbal capsules" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCDEwalVpvMWKa0AxfcCmIrZqKYj2WWmmDiWzuse-cq908tQH3tFDJtia4rqhyl2MiYrXNIOgjf87QpoZVABuznp1rSOQJSK6Sv-kkW6357DwjTIjsVIY4eN1GgRKCGkq2TvZd5h7KPgpe_YSwdlSvBgtFy3Hq1N8hXdch3IF2xDuPrxLtxIWAo0zHpIDE9NpseLEDJvdx7Uq-L2siV6aqm6tBE8mQEitdHOWONBnFai7R-GgpiAAskQRdPBycEJg0BWXOSFy4lejU"/>
<div class="absolute inset-0 portfolio-overlay"></div>
<div class="absolute bottom-0 left-0 p-10 text-white w-full">
<h3 class="font-headline-sm text-headline-sm mb-4 text-white">Herbal Capsules</h3>
<a href="{{ route('shop.index', ['category' => 'digestive-health']) }}" class="inline-block text-label-md font-bold text-white border-b-2 border-secondary pb-1 hover:text-secondary-fixed transition-all">View Products</a>
</div>
</div>
<!-- Small Card: Pure Oils -->
<div class="md:col-span-4 group relative overflow-hidden rounded-xl clinical-card h-[450px]">
<img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Pure herbal oils" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDQJyePWMpX1aE5s3WKsIZdsEamH3_yhdcQllJWlQheV_Its9j3k5n0a67XSjsy_oPuFwnu-nNGYdcHOA0-MMRgk3ZLldnzTQf5fPkzUaOtP_fU-z11CitXpU-ZdBQLAWGbgQdjFdN7ntI0t1LsvEmmvONaj9cq1_hWxbhNjdJmNYU1lxSyKEcZWR1Wlk8Oj36V4lBeJMwrxMT1N2sXnD0OoUa3tRwIkGpHnZw_JeBtZVLw1ZE2OA2Sy9-v1k8Og8UqT1n72d2pPGo"/>
<div class="absolute inset-0 portfolio-overlay"></div>
<div class="absolute bottom-0 left-0 p-10 text-white w-full">
<h3 class="font-headline-sm text-headline-sm mb-4 text-white">Pure Oils</h3>
<a href="{{ route('shop.index', ['category' => 'skin-care']) }}" class="inline-block text-label-md font-bold text-white border-b-2 border-secondary pb-1 hover:text-secondary-fixed transition-all">View Products</a>
</div>
</div>
<!-- Large Card: Wellness Tablets -->
<div class="md:col-span-8 group relative overflow-hidden rounded-xl clinical-card h-[450px]">
<img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Wellness tablets" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkBZcHtVtxz2xKSGGj8IFFePAgraXUWCU2h0CCsuU1Zo-RbSITlBl2p7UONVw28xww6N4oaScdFmpWOFgHqzKLwBUdzOiH-S1zqTtJdt_dOIh9qB2QmYbBRYWQuBnKnljk8bXuDNI6E-AQRyj92TCfirBJEZS0FOs6OIW1AK7SgBmUAhHnz8a1d-OueHICa8O1D_67K4lSrs-1nciv_XhG8tMua-dbzaPHLn8ed2h_B8DgS1piP2r9vqDuFi4RUZTU7NQFVw4kN-0"/>
<div class="absolute inset-0 portfolio-overlay"></div>
<div class="absolute bottom-0 left-0 p-10 text-white w-full">
<h3 class="font-headline-sm text-headline-sm mb-2 text-white">Wellness Tablets</h3>
<p class="font-body-md text-white/80 mb-6 max-w-sm">Precision-pressed herbal tablets crafted with authentic botanical extracts.</p>
<a href="{{ route('shop.index', ['category' => 'hair-care']) }}" class="inline-block bg-secondary text-on-secondary px-6 py-2.5 rounded-full text-label-md font-bold hover:scale-105 transition-all">View All</a>
</div>
</div>
</div>
</div>
</section>
<!-- Expert Spotlight -->
<section class="py-section-gap overflow-hidden">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="bg-white border border-outline-variant/30 rounded-3xl overflow-hidden flex flex-col lg:flex-row items-stretch">
<div class="lg:w-1/2 h-96 lg:h-auto relative">
<div class="w-full h-full bg-cover bg-center" data-alt="A professional portrait of Dr. Rajni Dubey, a distinguished Ayurvedic expert, in a high-end clinical setting. She is dressed in professional clinical attire, exuding confidence and approachability. The background consists of a minimalist medical library and a laboratory, lit with soft, warm professional studio lighting in a light-mode aesthetic." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB2dyUmn2OYzoxeJ34xYl2v30ARYuhHNow-zAi4ZwdAx1cWv0H-ZrJSXznQnuwKcLzu9wApe62fZHxZeeN1UNhJZQ2OoqM5StXwR3rVcLXLaZVM6PUwfgGrAB8v35WGMhKk3sEwICtCODP0Z5_Es5jUGeH2Gl1H5SmK_a61zwgHXvt8z1qwYCfRQs3MWk9scA83f3f15WchryT4fX5ifLXoOF-ty0ERSuC0k0cb00gCZ9B3Fh4NLMxHwIshuXyCaPAykPryDmsix7c");'></div>
<div class="absolute inset-0 bg-gradient-to-t from-primary/40 to-transparent lg:hidden"></div>
</div>
<div class="lg:w-1/2 p-12 lg:p-20 flex flex-col justify-center bg-surface-container-lowest">
<span class="material-symbols-outlined text-secondary text-[48px] mb-8" style='font-variation-settings: "FILL" 1;'>format_quote</span>
<h3 class="font-headline-md text-headline-md mb-6 italic leading-snug">"True healing occurs when we harmonize the elemental wisdom of nature with the diagnostic precision of science."</h3>
<div class="mb-10">
<p class="font-headline-sm text-headline-sm text-primary">Dr. Rajni Dubey</p>
<p class="text-secondary font-label-md">Expert Ayurvedic Vaidya (B.A.M.S)</p>
<p class="text-on-surface-variant text-label-sm mt-2">20+ Years Clinical Practice &amp; Formulation Research</p>
</div>
<div class="grid grid-cols-2 gap-8 border-t border-outline-variant/30 pt-8">
<div>
<p class="font-bold text-primary mb-1">Clinical Studies</p>
<p class="text-on-surface-variant text-label-sm">Authored 50+ research papers on botanical efficacy.</p>
</div>
<div>
<p class="font-bold text-primary mb-1">Custom Formulations</p>
<p class="text-on-surface-variant text-label-sm">Chief Architect of our proprietary wellness blends.</p>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Values Section -->
<section class="bg-primary-container py-section-gap">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="text-center max-w-3xl mx-auto mb-20">
<h2 class="font-headline-md text-headline-md text-on-primary mb-4">Our Ethos</h2>
<p class="text-on-primary-container font-body-md">The core principles that guide our clinical excellence and pharmaceutical integrity.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
<div class="bg-white/5 border border-white/10 p-10 rounded-xl hover:bg-white/10 transition-colors">
<div class="w-14 h-14 bg-secondary/20 rounded-full flex items-center justify-center mb-8">
<span class="material-symbols-outlined text-secondary-fixed text-[32px]">shield_person</span>
</div>
<h4 class="text-on-primary font-headline-sm mb-4">Integrity</h4>
<p class="text-on-primary-container text-label-md">Absolute transparency in sourcing and manufacturing for unwavering trust.</p>
</div>
<div class="bg-white/5 border border-white/10 p-10 rounded-xl hover:bg-white/10 transition-colors">
<div class="w-14 h-14 bg-secondary/20 rounded-full flex items-center justify-center mb-8">
<span class="material-symbols-outlined text-secondary-fixed text-[32px]">groups</span>
</div>
<h4 class="text-on-primary font-headline-sm mb-4">Teamwork</h4>
<p class="text-on-primary-container text-label-md">Collaborative intelligence of scientists, vaidyas, and process engineers.</p>
</div>
<div class="bg-white/5 border border-white/10 p-10 rounded-xl hover:bg-white/10 transition-colors">
<div class="w-14 h-14 bg-secondary/20 rounded-full flex items-center justify-center mb-8">
<span class="material-symbols-outlined text-secondary-fixed text-[32px]">eco</span>
</div>
<h4 class="text-on-primary font-headline-sm mb-4">Pure Ayurveda</h4>
<p class="text-on-primary-container text-label-md">Upholding the sanctity of ancient recipes with pharmaceutical precision.</p>
</div>
<div class="bg-white/5 border border-white/10 p-10 rounded-xl hover:bg-white/10 transition-colors">
<div class="w-14 h-14 bg-secondary/20 rounded-full flex items-center justify-center mb-8">
<span class="material-symbols-outlined text-secondary-fixed text-[32px]">psychology_alt</span>
</div>
<h4 class="text-on-primary font-headline-sm mb-4">Innovation</h4>
<p class="text-on-primary-container text-label-md">Continuous R&amp;D to improve bioavailability and therapeutic delivery.</p>
</div>
</div>
</div>
</section>
<!-- CTA Section -->
<section class="relative py-section-gap overflow-hidden">
<div class="absolute inset-0 z-0">
<div class="w-full h-full bg-cover bg-fixed opacity-5" data-alt="A macro photograph of geometric crystal patterns and herbal structures, symbolizing the fusion of nature and science. The lighting is ethereal and high-key, using a clean corporate palette of navy and slate to create a premium, authoritative background texture." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCLvSADlI7_jhTB8Zjknx0h8WrG6-76PhIko-z31YEGd9A3oyQ9SM49CcxxiZ5ktFndwCoxR3sYlavMsVq0BuhSS0IZ1cJp61miZ4V5BrDH6tmyldSyyPX6J7q7bll5iiyNSjXdvL8s892LV6XMbWGs056M_7OY6TNSLs7SwyFfs6Xj6RS0jYowuRZjAA21Tt4r-8p2orsk2Wms9Y0hNgbaV9sZ8-wLMgrh-WZjVBS8hEXE-K2ZAhhn5JKAbKmjeALSPOg7otm2il8");'></div>
</div>
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop text-center relative z-10">
<div class="max-w-4xl mx-auto">
<h2 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg mb-8">Ready to Elevate Your Wellness Brand?</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-12">
                        Partner with Kare ONS Herbals for pharmaceutical-grade Ayurvedic contract manufacturing and formulation development. Let's create clinical solutions together.
                    </p>
<div class="flex flex-col sm:flex-row justify-center gap-6">
<a href="{{ route('shop.index') }}" class="bg-primary text-on-primary px-12 py-5 rounded-full font-label-md text-label-md shadow-xl hover:shadow-2xl transition-all inline-block text-center">Request Partnership Proposal</a>
<a href="{{ route('shop.index') }}" class="bg-white border border-outline text-primary px-12 py-5 rounded-full font-label-md text-label-md hover:bg-surface-container transition-all inline-block text-center">Schedule a Call</a>
</div>
<div class="mt-16 flex justify-center items-center gap-10 text-on-surface-variant font-label-sm uppercase tracking-widest">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-secondary">mail</span>
                            {{ setting('site_email', 'info@kareonsherbals.com') }}
                        </div>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-secondary">call</span>
                            {{ setting('site_phone', '+91-70841104992') }}
                        </div>
</div>
</div>
</div>
</section>

@endsection
