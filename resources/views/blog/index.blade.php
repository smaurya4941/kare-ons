@extends('layouts.app')

@section('title', 'Wellness Blog - Kare Ons Herbal')

@section('content')
<div class="bg-surface-container-lowest py-16">
    <div class="max-w-container-max mx-auto px-margin-desktop text-center">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-on-surface mb-6">Ayurvedic Wellness Blog</h1>
        <p class="text-lg text-secondary max-w-2xl mx-auto">Discover ancient wisdom for modern living. Expert articles on herbs, health tips, and holistic wellbeing.</p>
    </div>
</div>

<div class="max-w-container-max mx-auto px-margin-desktop py-12 min-h-[50vh]">
    <!-- Categories Filter -->
    @if($categories->count() > 0)
    <div class="flex flex-wrap gap-3 mb-12 justify-center">
        <a href="{{ route('blog.index') }}" class="px-6 py-2 rounded-full border border-outline-variant font-medium {{ !request('category') ? 'bg-primary text-white border-primary' : 'bg-surface hover:bg-surface-container transition text-on-surface' }}">All Articles</a>
        @foreach($categories as $category)
            @if($category)
                <a href="{{ route('blog.index', ['category' => $category]) }}" class="px-6 py-2 rounded-full border border-outline-variant font-medium {{ request('category') === $category ? 'bg-primary text-white border-primary' : 'bg-surface hover:bg-surface-container transition text-on-surface' }}">{{ $category }}</a>
            @endif
        @endforeach
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($blogs as $blog)
            <article class="bg-surface rounded-2xl border border-outline-variant overflow-hidden group hover:border-primary transition-colors shadow-sm flex flex-col">
                <a href="{{ route('blog.show', $blog->slug) }}" class="block aspect-[16/10] bg-surface-container overflow-hidden relative">
                    @if($blog->featured_image)
                        <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-outline">
                            <span class="material-symbols-outlined text-[48px]">article</span>
                        </div>
                    @endif
                    @if($blog->category)
                        <span class="absolute top-4 left-4 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $blog->category }}
                        </span>
                    @endif
                </a>
                <div class="p-6 flex flex-col flex-1">
                    <div class="text-sm text-secondary mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                    </div>
                    <h2 class="text-xl font-bold text-on-surface mb-3 group-hover:text-primary transition line-clamp-2">
                        <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                    </h2>
                    <p class="text-on-surface-variant mb-6 line-clamp-3 text-sm leading-relaxed flex-1">
                        {{ $blog->excerpt ?? Str::limit(strip_tags($blog->content), 120) }}
                    </p>
                    <a href="{{ route('blog.show', $blog->slug) }}" class="inline-flex items-center gap-1 text-primary font-bold hover:underline mt-auto">
                        Read Article <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-20 bg-surface-container-lowest rounded-2xl border border-outline-variant">
                <span class="material-symbols-outlined text-[48px] text-outline mb-4">article</span>
                <h3 class="text-xl font-bold text-on-surface mb-2">No Articles Found</h3>
                <p class="text-secondary">Check back soon for new wellness insights and herbal tips.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
