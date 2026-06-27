<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('author')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $blogs = $query->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255|unique:blogs,title',
            'category'         => 'nullable|string|max:100',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'featured_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'           => 'required|boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published_at'     => 'nullable|date',
        ]);

        $validated['author_id']   = Auth::id();
        $validated['slug']        = $this->uniqueSlug($validated['title']);
        $validated['published_at'] = $validated['status']
            ? ($request->published_at ?? now())
            : null;

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        try {
            Blog::create($validated);
            return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
        } catch (\Exception $e) {
            if (isset($validated['featured_image'])) {
                Storage::disk('public')->delete($validated['featured_image']);
            }
            report($e);
            return back()->withInput()->with('error', 'Failed to create blog post due to an unexpected error.');
        }
    }

    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255|unique:blogs,title,' . $blog->id,
            'category'         => 'nullable|string|max:100',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'featured_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'           => 'required|boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published_at'     => 'nullable|date',
        ]);

        if ($validated['title'] !== $blog->title) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], $blog->id);
        }

        // Set published_at when publishing for first time
        if ($validated['status'] && ! $blog->published_at) {
            $validated['published_at'] = $request->published_at ?? now();
        } elseif (! $validated['status']) {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        try {
            $blog->update($validated);
            return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Failed to update blog post due to an unexpected error.');
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $blog->delete();

            return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted.');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to delete blog post due to an unexpected error.');
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function uniqueSlug(string $title, ?int $excludeId = null): string
    {
        $base   = Str::slug($title);
        $slug   = $base;
        $suffix = 1;

        while (
            Blog::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
