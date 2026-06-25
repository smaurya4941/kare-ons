@extends('layouts.app')

@section('title', $blog->meta_title ?? $blog->title . ' - Kare Ons Herbal')

@section('content')
<article class="max-w-4xl mx-auto px-margin-desktop py-12">
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-secondary mb-8">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('home') }}" class="hover:text-primary transition">Home</a></li>
            <li><span class="material-symbols-outlined text-[16px]">chevron_right</span></li>
            <li><a href="{{ route('blog.index') }}" class="hover:text-primary transition">Blog</a></li>
            <li><span class="material-symbols-outlined text-[16px]">chevron_right</span></li>
            <li class="text-on-surface font-medium line-clamp-1" aria-current="page">{{ $blog->title }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <header class="mb-10 text-center">
        @if($blog->category)
            <a href="{{ route('blog.index', ['category' => $blog->category]) }}" class="inline-block bg-surface-container text-primary text-sm font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-6 hover:bg-primary hover:text-white transition">
                {{ $blog->category }}
            </a>
        @endif
        
        <h1 class="text-4xl md:text-5xl font-display font-bold text-on-surface mb-6 leading-tight">{{ $blog->title }}</h1>
        
        <div class="flex items-center justify-center gap-6 text-secondary text-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                {{ $blog->published_at ? $blog->published_at->format('F d, Y') : $blog->created_at->format('F d, Y') }}
            </div>
            @if($blog->author)
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">person</span>
                {{ $blog->author->name }}
            </div>
            @endif
        </div>
    </header>

    <!-- Featured Image -->
    @if($blog->featured_image)
        <div class="rounded-2xl overflow-hidden aspect-[21/9] mb-12 shadow-md">
            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    <!-- Content -->
    <div class="prose max-w-none prose-lg prose-headings:font-display prose-headings:text-on-surface prose-p:text-on-surface-variant prose-a:text-primary prose-img:rounded-xl">
        {!! $blog->content !!}
    </div>

    <!-- Social Share -->
    <div class="mt-16 pt-8 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-6">
        <h3 class="font-bold text-on-surface">Share this article:</h3>
        <div class="flex gap-4">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-on-surface hover:bg-primary hover:text-white transition">
                FB
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-on-surface hover:bg-primary hover:text-white transition">
                TW
            </a>
            <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . request()->url()) }}" target="_blank" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-on-surface hover:bg-primary hover:text-white transition">
                WA
            </a>
        </div>
    </div>
</article>

<!-- Related Articles -->
@if($relatedBlogs->count() > 0)
<div class="bg-surface-container-lowest py-16 border-t border-outline-variant">
    <div class="max-w-container-max mx-auto px-margin-desktop">
        <h2 class="text-3xl font-display font-bold text-on-surface mb-8 text-center">Related Articles</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($relatedBlogs as $related)
                <article class="bg-surface rounded-2xl border border-outline-variant overflow-hidden group hover:border-primary transition-colors shadow-sm flex flex-col">
                    <a href="{{ route('blog.show', $related->slug) }}" class="block aspect-[16/10] bg-surface-container overflow-hidden relative">
                        @if($related->featured_image)
                            <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @endif
                    </a>
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="text-lg font-bold text-on-surface mb-2 group-hover:text-primary transition line-clamp-2">
                            <a href="{{ route('blog.show', $related->slug) }}">{{ $related->title }}</a>
                        </h3>
                        <p class="text-on-surface-variant text-sm line-clamp-2 mb-4">{{ $related->excerpt ?? Str::limit(strip_tags($related->content), 80) }}</p>
                        <a href="{{ route('blog.show', $related->slug) }}" class="inline-flex items-center gap-1 text-primary font-bold hover:underline mt-auto text-sm">
                            Read More <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
