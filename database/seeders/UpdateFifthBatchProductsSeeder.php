<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class UpdateFifthBatchProductsSeeder extends Seeder
{
    public function run()
    {
        $productsToUpdate = [
            // 1. D-Tan Lotion
            [
                'search' => 'D Tan',
                'data' => [
                    'name' => 'Kare Ons D-Tan Lotion (100 ml Pack)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '100 ml',
                    'usage_instructions' => 'Apply evenly to clean skin and massage gently until absorbed. Use daily or as directed by your skincare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Helps reduce the appearance of tanning.</li>
<li>Supports an even-looking skin tone.</li>
<li>Helps nourish and moisturize the skin.</li>
<li>Supports healthy, radiant-looking skin.</li>
<li>Helps maintain soft and refreshed skin.</li>
<li>Suitable for daily skincare.</li>
</ul>',
                    'description' => 'Kare Ons D-Tan Lotion (100 ml Pack)

Kare Ons D-Tan Lotion is a herbal skincare formulation enriched with Marigold, Mulberry, Licorice, Wheat Germ Oil, and Aloe Vera. The nourishing botanical ingredients help moisturize the skin while supporting a brighter and healthier-looking complexion.

Its lightweight formula is suitable for everyday use and helps maintain soft, smooth, and refreshed skin.

<strong>Highlights</strong>
<ul>
<li>Herbal de-tan lotion</li>
<li>Supports brighter-looking skin</li>
<li>Moisturizes and nourishes</li>
<li>Suitable for all skin types</li>
<li>100 ml pack</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Marigold</td><td class="border border-soft-border p-3">Helps soothe and nourish skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Mulberry</td><td class="border border-soft-border p-3">Rich in natural antioxidants</td></tr><tr><td class="border border-soft-border p-3 font-medium">Licorice</td><td class="border border-soft-border p-3">Traditionally supports an even-looking complexion</td></tr><tr><td class="border border-soft-border p-3 font-medium">Wheat Germ Oil</td><td class="border border-soft-border p-3">Nourishes and moisturizes skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Aloe Vera</td><td class="border border-soft-border p-3">Hydrates and refreshes skin</td></tr></tbody></table></div>',
                    'suitable_for' => "Men & Women\nAll skin types\nDaily skincare",
                    'storage_instructions' => "Store in a cool, dry place away from direct sunlight.",
                    'disclaimer' => 'For external use only. Avoid contact with eyes. Discontinue use if irritation occurs.',
                    'precautions' => null,
                ]
            ],

            // 2. Karekesh Hair Oil
            [
                'search' => 'Kare Kesh Hair',
                'data' => [
                    'name' => 'Karekesh Hair Oil (100 ml)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '100 ml',
                    'usage_instructions' => 'Massage gently into the scalp and hair. Leave for several hours or overnight before washing.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Nourishes the scalp.</li>
<li>Supports healthy hair growth.</li>
<li>Helps strengthen hair roots.</li>
<li>Supports healthy-looking hair.</li>
<li>Helps reduce hair breakage.</li>
<li>Supports scalp wellness.</li>
</ul>',
                    'description' => 'Karekesh Hair Oil (100 ml)

Karekesh Hair Oil is an Ayurvedic herbal hair oil formulated with Bhringraj, Mulethi, Karanja, Bakuchi, Neem, Brahmi, Triphala, and several traditional herbs. The nourishing oil helps moisturize the scalp while supporting stronger, healthier-looking hair.

Regular massage helps maintain scalp health and naturally healthy hair.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic herbal hair oil</li>
<li>Nourishes scalp</li>
<li>Supports healthy hair</li>
<li>Strengthens hair roots</li>
<li>Suitable for regular use</li>
<li>100 ml</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Bhringraj</td><td class="border border-soft-border p-3">Traditionally supports hair wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Mulethi</td><td class="border border-soft-border p-3">Helps nourish hair roots</td></tr><tr><td class="border border-soft-border p-3 font-medium">Karanja</td><td class="border border-soft-border p-3">Supports scalp health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Bakuchi</td><td class="border border-soft-border p-3">Traditionally used in hair care</td></tr><tr><td class="border border-soft-border p-3 font-medium">Neem</td><td class="border border-soft-border p-3">Helps maintain scalp hygiene</td></tr><tr><td class="border border-soft-border p-3 font-medium">Brahmi</td><td class="border border-soft-border p-3">Supports healthy scalp</td></tr><tr><td class="border border-soft-border p-3 font-medium">Triphala</td><td class="border border-soft-border p-3">Rich in natural antioxidants</td></tr></tbody></table></div>',
                    'suitable_for' => "Men & Women\nAll hair types",
                    'storage_instructions' => "Store in a cool, dry place.",
                    'disclaimer' => 'For external use only.',
                    'precautions' => null,
                ]
            ],

            // 3. Karekesh Shampoo
            [
                'search' => 'Kare Kesh Shampoo',
                'data' => [
                    'name' => 'Karekesh Shampoo (100 ml)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '100 ml',
                    'usage_instructions' => 'Apply to wet hair, massage gently to create lather, and rinse thoroughly.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Gently cleanses hair.</li>
<li>Helps nourish the scalp.</li>
<li>Supports soft and manageable hair.</li>
<li>Helps maintain healthy-looking hair.</li>
<li>Supports scalp hygiene.</li>
<li>Suitable for daily use.</li>
</ul>',
                    'description' => 'Karekesh Shampoo (100 ml)

Karekesh Shampoo is a sulphate-free and paraben-free herbal shampoo enriched with Hibiscus, Triphala, Mulethi, Neem, Bhringraj, Rose, and Tulsi. It gently cleanses the scalp while helping maintain soft, smooth, and healthy-looking hair.

Its herbal formula supports scalp cleanliness and everyday hair care.

<strong>Highlights</strong>
<ul>
<li>Herbal shampoo</li>
<li>Sulphate free</li>
<li>Paraben free</li>
<li>Gentle cleansing</li>
<li>Suitable for daily use</li>
<li>100 ml</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Hibiscus</td><td class="border border-soft-border p-3">Supports healthy hair</td></tr><tr><td class="border border-soft-border p-3 font-medium">Triphala</td><td class="border border-soft-border p-3">Helps nourish the scalp</td></tr><tr><td class="border border-soft-border p-3 font-medium">Mulethi</td><td class="border border-soft-border p-3">Moisturizes hair</td></tr><tr><td class="border border-soft-border p-3 font-medium">Neem</td><td class="border border-soft-border p-3">Supports scalp hygiene</td></tr><tr><td class="border border-soft-border p-3 font-medium">Bhringraj</td><td class="border border-soft-border p-3">Traditionally supports hair wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Tulsi</td><td class="border border-soft-border p-3">Supports scalp health</td></tr></tbody></table></div>',
                    'suitable_for' => "Men & Women\nAll hair types",
                    'storage_instructions' => "Store in a cool, dry place.",
                    'disclaimer' => 'For external use only.',
                    'precautions' => null,
                ]
            ],

            // 4. Karekesh Conditioner
            [
                'search' => 'Kare Kesh Conditioner',
                'data' => [
                    'name' => 'Karekesh Conditioner (100 ml)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '100 ml',
                    'usage_instructions' => 'After shampooing, apply evenly to damp hair. Leave for 2–3 minutes, then rinse thoroughly.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Helps soften hair.</li>
<li>Supports hair hydration.</li>
<li>Helps improve hair manageability.</li>
<li>Supports smooth and shiny hair.</li>
<li>Nourishes dry hair.</li>
<li>Suitable for all hair types.</li>
</ul>',
                    'description' => 'Karekesh Conditioner (100 ml)

Karekesh Conditioner is an Ayurvedic herbal conditioner enriched with Mango Butter, Rosemary Oil, Almond Oil, Hibiscus, Aloe Vera, Coconut Oil, Kalonji Oil, Rose, and Bhringraj. The nourishing botanical ingredients help moisturize the hair, improve manageability, and leave it feeling soft and smooth.

Its lightweight formula supports healthy-looking hair without weighing it down.

<strong>Highlights</strong>
<ul>
<li>Herbal conditioner</li>
<li>Deep nourishment</li>
<li>Smooth and manageable hair</li>
<li>Sulphate free</li>
<li>Paraben free</li>
<li>100 ml</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Mango Butter</td><td class="border border-soft-border p-3">Deeply nourishes hair</td></tr><tr><td class="border border-soft-border p-3 font-medium">Rosemary Oil</td><td class="border border-soft-border p-3">Supports scalp wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Almond Oil</td><td class="border border-soft-border p-3">Softens and conditions hair</td></tr><tr><td class="border border-soft-border p-3 font-medium">Hibiscus</td><td class="border border-soft-border p-3">Supports healthy hair</td></tr><tr><td class="border border-soft-border p-3 font-medium">Aloe Vera</td><td class="border border-soft-border p-3">Hydrates and nourishes</td></tr><tr><td class="border border-soft-border p-3 font-medium">Coconut Oil</td><td class="border border-soft-border p-3">Helps reduce dryness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Kalonji Oil</td><td class="border border-soft-border p-3">Traditionally used for hair care</td></tr><tr><td class="border border-soft-border p-3 font-medium">Bhringraj</td><td class="border border-soft-border p-3">Supports overall hair wellness</td></tr></tbody></table></div>',
                    'suitable_for' => "Men & Women\nAll hair types",
                    'storage_instructions' => "Store in a cool, dry place away from direct sunlight.",
                    'disclaimer' => 'For external use only. Avoid contact with eyes. If irritation occurs, discontinue use and consult a healthcare professional.',
                    'precautions' => null,
                ]
            ],
        ];

        foreach ($productsToUpdate as $item) {
            $product = Product::where('name', 'like', '%' . $item['search'] . '%')->first();
            
            if ($product) {
                // Generate a new slug if the name changed
                if ($product->name !== $item['data']['name']) {
                    $item['data']['slug'] = Str::slug($item['data']['name']);
                }

                $product->update($item['data']);
                echo "Updated: {$product->name}\n";
            } else {
                echo "Not found: {$item['search']}\n";
            }
        }
    }
}
