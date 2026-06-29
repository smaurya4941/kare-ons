<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products
        Product::query()->delete();

        $data = [
            "Women's Wellness" => [
                "Gynocare Vaginal Tablet", "Gynocare Intimate Wash", "Gynocare Intimate Cream"
            ],
            "Women's Health" => [
                "PCOD Care", "Garbhdharak", "Beej Poshan"
            ],
            "Skin Care Range" => [
                "Burn Care", "Saffron Hydrating Serum", "D Tan Lotion"
            ],
            "Hair Care Range" => [
                "Kare Kesh Conditioner", "Kare Kesh Shampoo", "Kare Kesh Hair Oil"
            ],
            "General Health & Special Care" => [
                "Women Bliss Plus", "Eros Plus", "B Stop"
            ],
            "Pain & Ortho Care" => [
                "Asthisukh Tablet", "Asthi Sukh Oil", "Asthi Sukh Roll-On"
            ],
            "Chronic Care" => [
                "Mellitus Care", "B.P Care", "StressCure"
            ],
            "Nutritional Wellness" => [
                "Kare HB Syrup", "Kare HB Tablets", "Provit Powder (Chocolate Flavour)"
            ],
            "Acidity & Digestive Care" => [
                "Acetosum", "Hepato Care", "Liv Cure DS"
            ],
            "General Health & Special Care (second section in brochure)" => [
                "Draksha", "Shatpushpadi Tailam", "Sting Gurad" // Note: "Sting Gurad" as typed by user
            ],
        ];

        $skuCounter = 1;

        foreach ($data as $catName => $products) {
            $category = Category::where('name', $catName)->first();
            
            if (!$category) {
                // If it wasn't found by exact name, maybe it's the second General Health category
                if ($catName === 'General Health & Special Care (second section in brochure)') {
                    // It should match since we inserted it exactly like this
                }
                continue;
            }

            foreach ($products as $productName) {
                $sku = 'KH-' . str_pad($skuCounter, 3, '0', STR_PAD_LEFT);
                $skuCounter++;

                Product::create([
                    'category_id' => $category->id,
                    'name' => $productName,
                    'slug' => Str::slug($productName) . '-' . uniqid(), // Append uniqid to avoid slug collisions just in case, though they seem unique
                    'sku' => $sku,
                    'short_description' => null,
                    'description' => 'description',
                    'price' => 300,
                    'sale_price' => 249,
                    'stock_quantity' => 50,
                    'weight' => null,
                    'main_image' => '', // Empty string since it's not nullable in DB
                    'featured' => 0,
                    'status' => 1,
                    'meta_title' => null,
                    'meta_description' => null,
                ]);
            }
        }
    }
}
