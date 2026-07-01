<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255|unique:categories,name',
            'parent_id'        => 'nullable|exists:categories,id',
            'description'      => 'nullable|string|max:2000',
            'status'           => 'required|boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories/images', 'public');
        }
        
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('categories/banners', 'public');
        }

        try {
            Category::create($validated);
            \Illuminate\Support\Facades\Cache::forget('header_categories');
            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            if (isset($validated['image'])) {
                Storage::disk('public')->delete($validated['image']);
            }
            if (isset($validated['banner_image'])) {
                Storage::disk('public')->delete($validated['banner_image']);
            }
            report($e);
            return back()->withInput()->with('error', 'Failed to create category due to an unexpected error.');
        }
    }

    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id'        => 'nullable|exists:categories,id',
            'description'      => 'nullable|string|max:2000',
            'status'           => 'required|boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Prevent category from being its own parent
        if ($validated['parent_id'] == $category->id) {
            $validated['parent_id'] = null;
        }

        // Regenerate slug only if name changed
        if ($validated['name'] !== $category->name) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $category->id);
        }
        
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories/images', 'public');
        }
        
        if ($request->hasFile('banner_image')) {
            if ($category->banner_image) {
                Storage::disk('public')->delete($category->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('categories/banners', 'public');
        }

        try {
            $category->update($validated);
            \Illuminate\Support\Facades\Cache::forget('header_categories');
            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Failed to update category due to an unexpected error.');
        }
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete a category that has products. Reassign or delete the products first.');
        }
        
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete a category that has child categories. Delete or reassign the children first.');
        }

        try {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            if ($category->banner_image) {
                Storage::disk('public')->delete($category->banner_image);
            }
            $category->delete();
            \Illuminate\Support\Facades\Cache::forget('header_categories');

            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to delete category due to an unexpected error.');
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Generate a slug that is guaranteed to be unique in the categories table.
     */
    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base   = Str::slug($name);
        $slug   = $base;
        $suffix = 1;

        while (
            Category::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
