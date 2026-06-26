@extends('layouts.app')

@section('content')
<main class="pt-24 md:pt-32 pb-section-gap">
<!-- Hero Section -->
<section class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto mb-section-gap">
<div class="relative w-full h-[60vh] min-h-[400px] rounded-xl overflow-hidden shadow-sm border border-soft-border group">
<div class="absolute inset-0 bg-cover bg-center transition-transform duration-[10s] group-hover:scale-105" data-alt="A macro, high-resolution photograph of vibrant, clinical-grade Ayurvedic herbs and botanical ingredients laid out immaculately on a pure white laboratory surface. The lighting is bright, ethereal, and diffused, creating a premium, modern apothecary aesthetic. Subtle, glowing electric purple light accents the edges of the organic forms, bridging ancient natural elements with cutting-edge scientific precision. The composition implies absolute purity and rigorous quality control." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAMzbuWekhWGym1n5wrc92A9_u_I9LHRrgoplpS6p4v1oACtYHpZLfXCe109SEM86v2Rn4TzeY3uv04CePP-_aicJuoqq2eTJFzxIdDFmFsgLrEwnvgWtWubNBg9r6ictddGzgKQrZqMyqg1jNqfBT-GCJUCalI8ZMHAlhIBzQptMaTffn4UhS1oCbhbJnXPWbj-5KavOzPAMfms88N5fDxykZQpZlTea-_SrwjFRYHP5TuVw3kDutL3KTVxKt9dlPC3-26M3lNZGG4')"></div>
<div class="absolute inset-0 bg-gradient-to-t from-surface/90 via-surface/40 to-transparent backdrop-blur-[2px]"></div>
<div class="absolute bottom-0 left-0 p-8 md:p-16 w-full md:w-2/3">
<div class="inline-block px-3 py-1 mb-4 rounded-full bg-tertiary/10 border border-tertiary/20">
<span class="font-label-sm text-label-sm text-tertiary tracking-wider uppercase">Our Heritage</span>
</div>
<h1 class="font-display-lg text-display-lg md:text-[64px] md:leading-[72px] text-on-surface mb-6">
                        Our Heritage,<br/><span class="text-primary">Your Wellness</span>
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl">
                        Bridging the ancient wisdom of pure Ayurveda with the rigorous standards of modern clinical manufacturing. We deliver formulations that are both traditionally authentic and scientifically validated.
                    </p>
</div>
</div>
</section>
<!-- Mission & Pillars (Bento Grid) -->
<section class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto mb-section-gap">
<div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
<!-- Mission Statement -->
<div class="md:col-span-5 flex flex-col justify-center pr-0 md:pr-8 mb-8 md:mb-0">
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-4">
                        The Standard of <br/>Pure Ayurveda
                    </h2>
<div class="w-12 h-1 bg-primary mb-6"></div>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">At Kare Ons Herbal, our mission is unequivocally clear: to provide Ayurvedic wellness solutions that demand no compromise. We believe that true healing begins with absolute purity.</p>
<p class="font-body-md text-body-md text-on-surface-variant">
                        Every syrup, capsule, and oil is a testament to our commitment to GMP Certified Manufacturing, ensuring that the profound efficacy of traditional herbs is delivered with clinical precision and safety.
                    </p>
</div>
<!-- Quality Pillars Grid -->
<div class="md:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
<!-- Pillar 1 -->
<div class="bg-surface-bright border border-soft-border rounded-xl p-6 hover:border-primary hover:shadow-[0_8px_24px_rgba(161,0,255,0.08)] transition-all duration-300 group">
<div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
<span class="material-symbols-outlined text-primary text-[28px]" data-icon="verified">verified</span>
</div>
<h3 class="font-headline-md text-[20px] leading-[28px] text-on-surface mb-2">GMP Certified</h3>
<p class="font-body-md text-[14px] leading-[20px] text-on-surface-variant">Manufactured in state-of-the-art, strictly regulated facilities ensuring pharmaceutical-grade hygiene and consistency.</p>
</div>
<!-- Pillar 2 -->
<div class="bg-surface-bright border border-soft-border rounded-xl p-6 hover:border-primary hover:shadow-[0_8px_24px_rgba(161,0,255,0.08)] transition-all duration-300 group">
<div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
<span class="material-symbols-outlined text-primary text-[28px]" data-icon="spa">spa</span>
</div>
<h3 class="font-headline-md text-[20px] leading-[28px] text-on-surface mb-2">Authentic Formulations</h3>
<p class="font-body-md text-[14px] leading-[20px] text-on-surface-variant">Recipes drawn directly from ancient Ayurvedic texts, preserved and prepared with utmost respect for the tradition.</p>
</div>
<!-- Pillar 3 -->
<div class="bg-surface-bright border border-soft-border rounded-xl p-6 hover:border-primary hover:shadow-[0_8px_24px_rgba(161,0,255,0.08)] transition-all duration-300 group">
<div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
<span class="material-symbols-outlined text-primary text-[28px]" data-icon="science">science</span>
</div>
<h3 class="font-headline-md text-[20px] leading-[28px] text-on-surface mb-2">Quality Tested</h3>
<p class="font-body-md text-[14px] leading-[20px] text-on-surface-variant">Rigorous lab testing at every stage, from raw material sourcing to final product, to guarantee potency and safety.</p>
</div>
<!-- Pillar 4 -->
<div class="bg-surface-bright border border-soft-border rounded-xl p-6 hover:border-primary hover:shadow-[0_8px_24px_rgba(161,0,255,0.08)] transition-all duration-300 group">
<div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
<span class="material-symbols-outlined text-primary text-[28px]" data-icon="flag">flag</span>
</div>
<h3 class="font-headline-md text-[20px] leading-[28px] text-on-surface mb-2">Made in India</h3>
<p class="font-body-md text-[14px] leading-[20px] text-on-surface-variant">Proudly sourced and manufactured in the birthplace of Ayurveda, supporting local farmers and sustainable practices.</p>
</div>
</div>
</div>
</section>
</main>
@endsection
