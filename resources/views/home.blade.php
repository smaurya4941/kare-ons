@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[90vh] flex items-center overflow-hidden bg-surface-container-lowest -mt-16 pt-16">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-transparent z-10"></div>
        <img class="w-full h-full object-cover grayscale opacity-40" data-alt="Luxurious herbal ingredients photograph" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCkrTAOW6DiEUzgV9Ss19vWTpAfUgfvjFmcaegFagSm7-OGL4LYa9eoXXhp5DQJj9nHpLdP31EBmKky0BFTP9X_iyD4uuZOUAPZozK8PMOodDwolV0CK-5dVPdTDQcjFs8pBNzhHVUg7DOsLEL1ZBEPwH1lIWHMuNsS9SIz-rY1AEd-rasgJBU69tQtrEV9VokeL6euoll7Y7s6OvJgRnkXKGnGDZnfspNkaWFASQovJxOZDDHwYz-KQg2Zrmo0f8PqUfZ2Fmebtsk"/>
    </div>
    <div class="relative z-20 max-w-container-max mx-auto px-margin-desktop w-full">
        <div class="max-w-3xl">
            <span class="text-xs font-bold text-primary uppercase tracking-widest mb-4 block">Premium Ayurvedic Solutions</span>
            <h1 class="font-display text-5xl md:text-6xl font-semibold mb-6 leading-[1.1] text-on-surface">
                Nature's Goodness, <br/>
                <span class="text-primary italic font-light">Refined by Science.</span>
            </h1>
            <p class="font-body text-lg text-secondary mb-10 max-w-xl leading-relaxed">
                Elevating holistic wellness through GMP-certified manufacturing excellence and ancestral botanical wisdom. We craft purity into every formulation.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <button class="bg-primary text-white px-8 py-3.5 text-sm font-semibold rounded-lg hover:bg-on-primary-fixed-variant transition-all shadow-sm">
                    GET QUOTE
                </button>
                <button class="border border-outline text-on-surface px-8 py-3.5 text-sm font-semibold rounded-lg hover:bg-surface-container transition-all">
                    PRODUCT CATALOGUE
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Certifications Row -->
<section class="py-10 bg-white border-y border-outline-variant">
    <div class="max-w-container-max mx-auto px-margin-desktop">
        <div class="flex flex-wrap justify-between items-center gap-8 text-secondary">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-2xl">verified</span>
                <span class="text-xs font-bold uppercase tracking-widest">GMP Certified</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-2xl">workspace_premium</span>
                <span class="text-xs font-bold uppercase tracking-widest">ISO 9001:2015</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-2xl">eco</span>
                <span class="text-xs font-bold uppercase tracking-widest">AYUSH Certified</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-2xl">public</span>
                <span class="text-xs font-bold uppercase tracking-widest">PAN India Network</span>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-section-gap bg-white">
    <div class="max-w-container-max mx-auto px-margin-desktop">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative group">
                <img class="w-full aspect-square object-cover rounded-lg shadow-sm carbon-border" data-alt="Modern Ayurvedic manufacturing facility" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD3alh9i9m2LWMQMqh9hJrrmgxQGSMOKMh2S4Ox-C-76pjTDLAFCj4Dj-eJ4Zq7VHoCX2F1Eds0hYvVRvZAOJapiu9A03ABDs3MBhYZARwAoaTF7N5xq4tslbR6Ud0rOcc2g0tB6m2hJtGUQuAC-XubKA3-k7CpommwTOg8vS1_J6haJhqRTvipTi0RU7qpjQBZfGth_CQbz46D2CS9nhM7Uj6jIK4JiNplCKt2EpLsApezdmH8XMHv0vEjs0nSjpHdxWH-fVRDTXA"/>
                <div class="absolute -bottom-6 -right-6 bg-surface-container p-8 border border-outline-variant rounded-lg shadow-md hidden lg:block">
                    <p class="text-3xl font-bold text-primary mb-1">25+</p>
                    <p class="text-xs font-bold uppercase tracking-wider text-secondary">Years of Heritage</p>
                </div>
            </div>
            <div>
                <span class="text-xs font-bold text-primary uppercase tracking-widest mb-4 block">Our Legacy</span>
                <h2 class="text-3xl md:text-4xl font-semibold mb-6 text-on-surface">Kare ONS Herbals: Bridging Tradition &amp; Innovation</h2>
                <div class="space-y-6 text-secondary leading-relaxed">
                    <p>Based in India, we are one of the fastest-growing Ayurvedic and herbal healthcare companies. We specialize in premium Third Party Manufacturing and PCD Pharma Franchise services, delivering quality formulations that stand the test of science.</p>
                    <p>Every formulation is meticulously developed by our specialists in our GMP-certified units, ensuring each capsule, syrup, and oil reflects our commitment to holistic excellence.</p>
                </div>
                <a class="mt-8 inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-primary group" href="#">
                    Explore Our Heritage
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Product Categories Bento Grid -->
<section class="py-section-gap bg-surface-container">
    <div class="max-w-container-max mx-auto px-margin-desktop">
        <div class="mb-12">
            <span class="text-xs font-bold text-primary uppercase tracking-widest mb-3 block">Our Portfolio</span>
            <h2 class="text-3xl font-semibold text-on-surface">Curated Ayurvedic Collections</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Large Card -->
            <div class="md:col-span-8 group relative overflow-hidden rounded-lg carbon-border h-[450px] bg-white">
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 opacity-90" data-alt="Herbal syrups packaging" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDIyT1Q97U-DHduopfWXpDRUsd0fh1C_liyRyo7D-3lqL72IrrSRayBzJ4hEqQDt0sreTB1hfFl0wyCFi4tjIDOQ4MtV-lwqM3Li2KNvjg1bUzRusBszYIAP0_0NucQz5TFNHF1NC6JH7RVN4KSTpfJhpQMIavKGelMwVZQekANpfe2hrKfpyv5joFjM59Rx7m3u6Np1kMClHZCIX73zY_SwEW4Fubrs2Mo9vKAYAqcYhro_aqkcxW5X0btxNcxM6TEpyIxyfMOdv8"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 text-white">
                    <h3 class="text-2xl font-semibold mb-2">Ayurvedic Syrups</h3>
                    <p class="text-sm opacity-80 mb-6 max-w-sm">Potent formulations for immunity, digestion, and daily vitality.</p>
                    <button class="bg-primary text-white px-5 py-2.5 text-xs font-bold uppercase tracking-widest rounded hover:bg-on-primary-fixed-variant transition-all">Explore Range</button>
                </div>
            </div>
            <!-- Small Card -->
            <div class="md:col-span-4 group relative overflow-hidden rounded-lg carbon-border h-[450px] bg-white">
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 opacity-90" data-alt="Herbal capsules" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCDEwalVpvMWKa0AxfcCmIrZqKYj2WWmmDiWzuse-cq908tQH3tFDJtia4rqhyl2MiYrXNIOgjf87QpoZVABuznp1rSOQJSK6Sv-kkW6357DwjTIjsVIY4eN1GgRKCGkq2TvZd5h7KPgpe_YSwdlSvBgtFy3Hq1N8hXdch3IF2xDuPrxLtxIWAo0zHpIDE9NpseLEDJvdx7Uq-L2siV6aqm6tBE8mQEitdHOWONBnFai7R-GgpiAAskQRdPBycEJg0BWXOSFy4lejU"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 text-white">
                    <h3 class="text-2xl font-semibold mb-2">Herbal Capsules</h3>
                    <button class="text-xs font-bold uppercase tracking-widest border-b border-white pb-1 hover:text-primary transition-all">View Products</button>
                </div>
            </div>
            <!-- Middle Row -->
            <div class="md:col-span-4 group relative overflow-hidden rounded-lg carbon-border h-[450px] bg-white">
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 opacity-90" data-alt="Pure herbal oils" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDQJyePWMpX1aE5s3WKsIZdsEamH3_yhdcQllJWlQheV_Its9j3k5n0a67XSjsy_oPuFwnu-nNGYdcHOA0-MMRgk3ZLldnzTQf5fPkzUaOtP_fU-z11CitXpU-ZdBQLAWGbgQdjFdN7ntI0t1LsvEmmvONaj9cq1_hWxbhNjdJmNYU1lxSyKEcZWR1Wlk8Oj36V4lBeJMwrxMT1N2sXnD0OoUa3tRwIkGpHnZw_JeBtZVLw1ZE2OA2Sy9-v1k8Og8UqT1n72d2pPGo"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 text-white">
                    <h3 class="text-2xl font-semibold mb-2">Pure Oils</h3>
                    <button class="text-xs font-bold uppercase tracking-widest border-b border-white pb-1 hover:text-primary transition-all">View Products</button>
                </div>
            </div>
            <div class="md:col-span-8 group relative overflow-hidden rounded-lg carbon-border h-[450px] bg-white">
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 opacity-90" data-alt="Wellness tablets" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkBZcHtVtxz2xKSGGj8IFFePAgraXUWCU2h0CCsuU1Zo-RbSITlBl2p7UONVw28xww6N4oaScdFmpWOFgHqzKLwBUdzOiH-S1zqTtJdt_dOIh9qB2QmYbBRYWQuBnKnljk8bXuDNI6E-AQRyj92TCfirBJEZS0FOs6OIW1AK7SgBmUAhHnz8a1d-OueHICa8O1D_67K4lSrs-1nciv_XhG8tMua-dbzaPHLn8ed2h_B8DgS1piP2r9vqDuFi4RUZTU7NQFVw4kN-0"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 text-white">
                    <h3 class="text-2xl font-semibold mb-2">Wellness Tablets</h3>
                    <p class="text-sm opacity-80 mb-6 max-w-sm">Precision-pressed herbal tablets crafted with authentic botanical extracts.</p>
                    <button class="bg-primary text-white px-5 py-2.5 text-xs font-bold uppercase tracking-widest rounded hover:bg-on-primary-fixed-variant transition-all">View All</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Expert Profile -->
<section class="py-section-gap bg-white overflow-hidden">
    <div class="max-w-container-max mx-auto px-margin-desktop">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="w-full lg:w-1/2">
                <div class="relative">
                    <div class="absolute -inset-1 bg-primary/10 rounded-lg"></div>
                    <img class="relative w-full aspect-[4/5] object-cover rounded-lg shadow-sm border border-outline-variant grayscale" data-alt="Dr. Rajni Dubey portrait" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7nh7NwqWPXZssRRuVII2BlGQMbNy_60yfqhj0CPsBi2A6ieS_WM2g-GLJ1Yb5NkyyFIEUUpKfvz7v8tqH8aMjQrfZ7hpOPKj5-GR_O9V6UFrY6YP9Cyu-HKc2chsuat9MHOuUW1ezHjJN1UaftjidQz2rWFgVURTi9yJRE_Mg5hVk6F9SQDjdbusrvq7J56bPgo_t2W3gIqDKNo61zk2j-q6-LYjPCPZyI44uarBmpVqGhkMTI36PY-A6DShRl4M2JWkqVD1bgUU"/>
                </div>
            </div>
            <div class="w-full lg:w-1/2">
                <span class="text-xs font-bold text-primary uppercase tracking-widest mb-4 block">Meet Our Expert</span>
                <h2 class="text-4xl font-semibold mb-2 text-on-surface">Dr. Rajni Dubey</h2>
                <h3 class="text-lg font-medium text-secondary mb-8">Expert Ayurvedic Vaidya &amp; Formulation Specialist</h3>
                <p class="text-xl font-light text-on-surface mb-8 italic border-l-4 border-primary pl-6">
                    "Nature provides everything we need to heal. At Kare ONS, our mission is to deliver these gifts with scientific precision and unwavering integrity."
                </p>
                <ul class="space-y-4 mb-10">
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">task_alt</span>
                        <span class="text-sm font-medium text-secondary">Authentic Research-Based Formulations</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">task_alt</span>
                        <span class="text-sm font-medium text-secondary">Two Decades of Natural Healing Expertise</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">task_alt</span>
                        <span class="text-sm font-medium text-secondary">Strict Quality &amp; GMP Supervision</span>
                    </li>
                </ul>
                <button class="bg-surface-container text-primary px-6 py-3 text-sm font-bold uppercase tracking-widest rounded-lg border border-outline-variant hover:bg-surface-container-high transition-all">
                    Meet Dr. Dubey
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-section-gap bg-inverse-surface text-white">
    <div class="max-w-container-max mx-auto px-margin-desktop">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <div class="lg:col-span-4">
                <span class="text-xs font-bold text-primary-fixed uppercase tracking-widest mb-6 block">Our Ethos</span>
                <h2 class="text-3xl font-semibold mb-6">We Live Our Values</h2>
                <p class="text-secondary-fixed font-light max-w-sm leading-relaxed">Built on trust and guided by the principles of pure Ayurveda, we prioritize people over profit.</p>
            </div>
            <div class="lg:col-span-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-12 gap-x-8">
                    <!-- Value Items -->
                    <div class="group border-t border-white/10 pt-6">
                        <span class="text-4xl font-bold text-stroke-primary opacity-30 block mb-4">01</span>
                        <h3 class="text-xl font-semibold mb-2">Integrity</h3>
                        <p class="text-secondary-fixed text-sm font-light">Upholding the highest standards of honesty in every formulation.</p>
                    </div>
                    <div class="group border-t border-white/10 pt-6">
                        <span class="text-4xl font-bold text-stroke-primary opacity-30 block mb-4">02</span>
                        <h3 class="text-xl font-semibold mb-2">Teamwork</h3>
                        <p class="text-secondary-fixed text-sm font-light">Collaborating with expert vaidyas and modern scientists.</p>
                    </div>
                    <div class="group border-t border-white/10 pt-6">
                        <span class="text-4xl font-bold text-stroke-primary opacity-30 block mb-4">03</span>
                        <h3 class="text-xl font-semibold mb-2">Pure Ayurveda</h3>
                        <p class="text-secondary-fixed text-sm font-light">Zero compromise on the authenticity of our herbal extracts.</p>
                    </div>
                    <div class="group border-t border-white/10 pt-6">
                        <span class="text-4xl font-bold text-stroke-primary opacity-30 block mb-4">04</span>
                        <h3 class="text-xl font-semibold mb-2">Innovation</h3>
                        <p class="text-secondary-fixed text-sm font-light">Modern manufacturing techniques for maximum effectiveness.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-24 text-center relative overflow-hidden bg-surface-container-low border-b border-outline-variant">
    <div class="max-w-3xl mx-auto px-margin-mobile relative z-10">
        <h2 class="text-4xl font-semibold mb-6 text-on-surface">Ready to Elevate Your Wellness Brand?</h2>
        <p class="text-lg text-secondary mb-10 max-w-2xl mx-auto font-light">Join hands with India's most trusted Ayurvedic manufacturing partner for premium Third Party and PCD Franchise solutions.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <button class="bg-primary text-white px-10 py-4 text-sm font-bold uppercase tracking-widest rounded-lg hover:bg-on-primary-fixed-variant transition-all shadow-md">
                Start Your Inquiry
            </button>
            <button class="bg-white border border-outline text-on-surface px-10 py-4 text-sm font-bold uppercase tracking-widest rounded-lg hover:bg-surface-container transition-all">
                Contact Dr. Rajni
            </button>
        </div>
    </div>
</section>
@endsection
