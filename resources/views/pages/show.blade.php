@extends('layouts.app')

@section('title', $page->title . ' - ' . setting('site_name', 'Kare ONS Herbals'))

@section('content')
<div class="bg-surface py-12 border-b border-outline-variant/30">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop text-center">
        <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-primary mb-4">{{ $page->title }}</h1>
    </div>
</div>

<div class="max-w-3xl mx-auto px-margin-mobile md:px-margin-desktop py-section-gap prose prose-lg prose-indigo">
    {!! $page->content !!}
</div>
@endsection
