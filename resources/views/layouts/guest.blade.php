<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ setting('site_name', config('app.name', 'Kare Ons Herbal')) }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Plus+Jakarta+Sans:wght@600;700;800&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body-md text-on-background antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center p-4 relative overflow-hidden bg-background">
            <!-- Ambient brand decoration -->
            <div class="absolute inset-0 -z-10 opacity-40 pointer-events-none">
                <div class="absolute top-[-10%] right-[-5%] w-[50vw] h-[50vw] rounded-full bg-herbal-light blur-[120px]"></div>
                <div class="absolute bottom-[-20%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-secondary-fixed/40 blur-[100px]"></div>
            </div>

            <a href="{{ route('home') }}" class="mb-5">
                <img src="{{ setting('logo') ? image_url(setting('logo')) : asset('images/logo.png') }}" alt="{{ setting('site_name', 'Kare Ons Herbal') }}" class="h-11 w-auto object-contain">
            </a>

            <div class="w-full sm:max-w-md px-6 py-7 bg-white/80 backdrop-blur-md border border-soft-border shadow-sm rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
