@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'noIndex' => false,
    'ogImage' => null,
    'ogType' => 'website',
    'prevUrl' => null,
    'nextUrl' => null,
])

@php
    $siteName = setting('site_name', config('app.name', 'Kareons Herbal'));
    
    // Resolve Title
    if ($title) {
        $finalTitle = $title . ' | ' . $siteName;
    } else {
        $finalTitle = $siteName . ' - Ayurvedic Herbal Products';
    }

    // Resolve Description
    $finalDescription = $description ?? setting('seo_meta_description', 'Discover premium Ayurvedic herbal products at Kareons Herbal.');

    // Clean Canonical URL (Remove query parameters)
    if ($canonical) {
        $finalCanonical = $canonical;
    } else {
        $currentUrl = url()->current();
        // If it's the home page, just use the base URL
        $finalCanonical = $currentUrl;
    }

    // Resolve Open Graph Image
    $finalOgImage = $ogImage ?? (setting('logo') ? image_url(setting('logo')) : asset('images/logo.png'));
    
    // Robots
    $robots = $noIndex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large';
@endphp

<title>{{ $finalTitle }}</title>
<meta name="description" content="{{ $finalDescription }}">
<link rel="canonical" href="{{ $finalCanonical }}">
<meta name="robots" content="{{ $robots }}">

@if($prevUrl)
<link rel="prev" href="{{ $prevUrl }}">
@endif
@if($nextUrl)
<link rel="next" href="{{ $nextUrl }}">
@endif

<!-- Open Graph -->
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $finalTitle }}">
<meta property="og:description" content="{{ $finalDescription }}">
<meta property="og:url" content="{{ $finalCanonical }}">
<meta property="og:image" content="{{ $finalOgImage }}">
<meta property="og:site_name" content="{{ $siteName }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $finalTitle }}">
<meta name="twitter:description" content="{{ $finalDescription }}">
<meta name="twitter:image" content="{{ $finalOgImage }}">
