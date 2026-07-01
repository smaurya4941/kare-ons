<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Brand;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products   = $query->paginate(15);
        $categories = Category::where('status', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $taxes = Tax::where('status', true)->get();
        return view('admin.products.create', compact('categories', 'brands', 'taxes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:255|unique:products,name',
            'category_id'          => 'required|exists:categories,id',
            'sku'                  => 'required|string|max:100|unique:products,sku',
            'price'                => 'required|numeric|min:0|max:9999999',
            'sale_price'           => 'nullable|numeric|min:0|lt:price',
            'stock_quantity'       => 'required|integer|min:0|max:99999',
            'short_description'    => 'nullable|string|max:500',
            'description'          => 'required|string',

            'brand_id'             => 'nullable|exists:brands,id',
            'tax_id'               => 'nullable|exists:taxes,id',
            'pack_size'            => 'nullable|string|max:255',
            'benefits'             => 'nullable|string|max:5000',
            'ayurvedic_reference'  => 'nullable|string|max:5000',
            'suitable_for'         => 'nullable|string|max:5000',
            'disclaimer'           => 'nullable|string|max:5000',
            'ingredients'          => 'nullable|string|max:5000',
            'usage_instructions'   => 'nullable|string|max:5000',
            'storage_instructions' => 'nullable|string|max:2000',
            'precautions'          => 'nullable|string|max:2000',

            // SEO & Status
            'status'               => 'required|boolean',
            'featured'             => 'boolean',
            'is_featured'          => 'boolean',
            'is_best_seller'       => 'boolean',
            'is_trending'          => 'boolean',
            'meta_title'           => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string|max:500',

            // Images
            'main_image'           => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $validated['slug']     = $this->uniqueSlug($validated['name']);
            $validated['featured'] = $request->boolean('featured');
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_best_seller'] = $request->boolean('is_best_seller');
            $validated['is_trending'] = $request->boolean('is_trending');
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');

            $product = Product::create(Arr::except($validated, ['gallery']));

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');
                    $product->images()->create([
                        'image_path'  => $path,
                        'sort_order'  => $index,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Delete uploaded files if DB insertion failed
            if (isset($validated['main_image'])) {
                Storage::disk('public')->delete($validated['main_image']);
            }
            if ($request->hasFile('gallery')) {
                // We'd have to track uploaded paths, but for simplicity we rely on local garbage collection or storage cleanup later
                // Just log the error and return
            }
            report($e);
            return back()->withInput()->with('error', 'Failed to create product due to an unexpected error.');
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'reviews.user']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $taxes = Tax::where('status', true)->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'taxes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id'          => 'required|exists:categories,id',
            'sku'                  => 'required|string|max:100|unique:products,sku,' . $product->id,
            'price'                => 'required|numeric|min:0|max:9999999',
            'sale_price'           => 'nullable|numeric|min:0|lt:price',
            'stock_quantity'       => 'required|integer|min:0|max:99999',
            'short_description'    => 'nullable|string|max:500',
            'description'          => 'required|string',

            'brand_id'             => 'nullable|exists:brands,id',
            'pack_size'            => 'nullable|string|max:255',
            'benefits'             => 'nullable|string|max:5000',
            'ayurvedic_reference'  => 'nullable|string|max:5000',
            'suitable_for'         => 'nullable|string|max:5000',
            'disclaimer'           => 'nullable|string|max:5000',
            'ingredients'          => 'nullable|string|max:5000',
            'usage_instructions'   => 'nullable|string|max:5000',
            'storage_instructions' => 'nullable|string|max:2000',
            'precautions'          => 'nullable|string|max:2000',

            // SEO & Status
            'status'               => 'required|boolean',
            'featured'             => 'boolean',
            'is_featured'          => 'boolean',
            'is_best_seller'       => 'boolean',
            'is_trending'          => 'boolean',
            'meta_title'           => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string|max:500',

            // Images
            'main_image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $validated['featured'] = $request->boolean('featured');
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_best_seller'] = $request->boolean('is_best_seller');
            $validated['is_trending'] = $request->boolean('is_trending');

            if ($validated['name'] !== $product->name) {
                $validated['slug'] = $this->uniqueSlug($validated['name'], $product->id);
            }

            if ($request->hasFile('main_image')) {
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $validated['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            $product->update(Arr::except($validated, ['gallery']));

            if ($request->hasFile('gallery')) {
                $maxSort = $product->images()->max('sort_order') ?? -1;
                foreach ($request->file('gallery') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'sort_order' => $maxSort + 1 + $index,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->with('error', 'Failed to update product due to an unexpected error.');
        }
    }

    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return back()->with('success', 'Gallery image removed.');
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }
            $product->delete();

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Failed to delete product due to an unexpected error.');
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base   = Str::slug($name);
        $slug   = $base;
        $suffix = 1;

        while (
            Product::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
