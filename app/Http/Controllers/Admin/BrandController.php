<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $brands = $query->paginate(15);

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string|max:2000',
            'status'      => 'required|boolean',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        try {
            Brand::create($validated);
            return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
        } catch (\Exception $e) {
            if (isset($validated['logo'])) {
                Storage::disk('public')->delete($validated['logo']);
            }
            report($e);
            return back()->withInput()->with('error', 'Failed to create brand due to an unexpected error.');
        }
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string|max:2000',
            'status'      => 'required|boolean',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validated['name'] !== $brand->name) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $brand->id);
        }

        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        try {
            $brand->update($validated);
            return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Failed to update brand due to an unexpected error.');
        }
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->exists()) {
            return back()->with('error', 'Cannot delete brand because it has associated products.');
        }
        
        try {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $brand->delete();

            return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to delete brand due to an unexpected error.');
        }
    }

    /**
     * Generate a unique slug for brands.
     */
    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base   = Str::slug($name);
        $slug   = $base;
        $suffix = 1;

        while (
            Brand::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
