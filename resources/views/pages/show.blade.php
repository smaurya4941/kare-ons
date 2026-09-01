@extends('layouts.app')

@section('title', $page->seo_title ?: $page->title)
@section('meta_description', $page->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($page->content), 160))
@if(isset($page->is_indexable) && ! $page->is_indexable)
    @section('no_index', 'true')
@endif

@section('content')
<div class="bg-surface py-12 border-b border-outline-variant/30">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop text-center">
        <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-primary mb-4">{{ $page->title }}</h1>
    </div>
</div>

<div class="max-w-3xl mx-auto px-margin-mobile md:px-margin-desktop py-10 prose prose-lg prose-neutral prose-headings:text-herbal-deep prose-a:text-primary">
    {!! $page->content !!}
</div>
@endsection
