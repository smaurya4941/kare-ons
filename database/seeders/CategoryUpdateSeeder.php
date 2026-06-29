<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class CategoryUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing categories (this will cascade delete products if foreign keys are set that way)
        Category::query()->delete();

        $categories = [
            "Women's Wellness",
            "Women's Health",
            "Skin Care Range",
            "Hair Care Range",
            "General Health & Special Care",
            "Pain & Ortho Care",
            "Chronic Care",
            "Nutritional Wellness",
            "Acidity & Digestive Care",
            "General Health & Special Care (second section in brochure)"
        ];

        foreach($categories as $index => $catName) {
            Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
                'status' => 1,
                'sort_order' => $index,
            ]);
        }
    }
}
