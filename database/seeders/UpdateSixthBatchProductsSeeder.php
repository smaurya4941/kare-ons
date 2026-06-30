<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class UpdateSixthBatchProductsSeeder extends Seeder
{
    public function run()
    {
        $productsToUpdate = [
            // 1. Saffron Hydrating Serum
            [
                'search' => 'Saffron',
                'data' => [
                    'name' => 'Kare Ons Saffron Hydrating Face Serum (25 ml)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '25 ml',
                    'usage_instructions' => 'Apply 2–3 drops on clean face and neck. Massage gently until absorbed. Use morning and night.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Deeply hydrates and nourishes the skin.</li>
<li>Helps improve skin radiance.</li>
<li>Supports an even-looking complexion.</li>
<li>Helps reduce dullness and dryness.</li>
<li>Refreshes and revitalizes tired-looking skin.</li>
<li>Suitable for all skin types.</li>
</ul>',
                    'description' => 'Kare Ons Saffron Hydrating Face Serum (25 ml)

Kare Ons Saffron Hydrating Face Serum is a lightweight herbal serum enriched with Saffron, Marigold, Mulberry, Pineapple, and Rose Water. The botanical ingredients help hydrate the skin while supporting a brighter and healthier-looking complexion.

Its fast-absorbing formula helps keep skin soft, smooth, and naturally radiant without leaving a greasy feel.

<strong>Highlights</strong>
<ul>
<li>Herbal hydrating serum</li>
<li>Enriched with Saffron</li>
<li>Lightweight & non-greasy</li>
<li>Suitable for daily use</li>
<li>Suitable for all skin types</li>
<li>25 ml pack</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Saffron</td><td class="border border-soft-border p-3">Supports radiant-looking skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Marigold</td><td class="border border-soft-border p-3">Helps soothe irritated skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Mulberry</td><td class="border border-soft-border p-3">Rich in natural antioxidants</td></tr><tr><td class="border border-soft-border p-3 font-medium">Pineapple</td><td class="border border-soft-border p-3">Helps refresh and nourish skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Rose Water</td><td class="border border-soft-border p-3">Hydrates and refreshes skin</td></tr></tbody></table></div>',
                    'suitable_for' => "Men & Women\nAll skin types",
                    'storage_instructions' => "Store in a cool, dry place away from direct sunlight.",
                    'disclaimer' => 'For external use only. Avoid contact with eyes.',
                    'precautions' => null,
                ]
            ],

            // 2. Burn Care Cream
            [
                'search' => 'Burn Care',
                'data' => [
                    'name' => 'Kare Ons Burn Care Cream (40 gm)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '40 gm',
                    'usage_instructions' => 'Clean the affected area and apply a thin layer as required or as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Helps soothe minor skin irritation.</li>
<li>Supports skin repair and nourishment.</li>
<li>Moisturizes dry and damaged skin.</li>
<li>Helps calm irritated skin.</li>
<li>Supports healthy skin recovery.</li>
<li>Suitable for daily skin care.</li>
</ul>',
                    'description' => 'Kare Ons Burn Care Cream (40 gm)

Kare Ons Burn Care Cream is a herbal skincare formulation enriched with Cow Ghee, Turmeric, Aloe Vera, and Liquorice Extract. The nourishing ingredients help soothe irritated skin while supporting the skin\'s natural healing process.

Its moisturizing formula helps maintain soft, healthy, and comfortable skin.

<strong>Highlights</strong>
<ul>
<li>Herbal skin care cream</li>
<li>Nourishes damaged skin</li>
<li>Moisturizes and soothes</li>
<li>Easy to apply</li>
<li>40 gm pack</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Cow Ghee</td><td class="border border-soft-border p-3">Nourishes and moisturizes skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Turmeric</td><td class="border border-soft-border p-3">Traditionally supports healthy skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Aloe Vera</td><td class="border border-soft-border p-3">Hydrates and cools skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Liquorice Extract</td><td class="border border-soft-border p-3">Rich in natural antioxidants</td></tr></tbody></table></div>',
                    'suitable_for' => "Men & Women",
                    'storage_instructions' => "Store in a cool, dry place.",
                    'disclaimer' => 'For external use only. Avoid applying on deep or severe burns without medical advice.',
                    'precautions' => null,
                ]
            ],

            // 3. Kare Hb Syrup
            [
                'search' => 'HB Syrup',
                'data' => [
                    'name' => 'Kare Hb Syrup (Sugar Free)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '200 ml',
                    'usage_instructions' => 'Use as directed by your physician or healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports healthy hemoglobin levels.</li>
<li>Helps maintain healthy red blood cell production.</li>
<li>Supports iron metabolism.</li>
<li>Helps reduce weakness associated with low iron.</li>
<li>Supports overall vitality and wellness.</li>
<li>Sugar-free formulation.</li>
</ul>',
                    'description' => 'Kare Hb Syrup

Kare Hb Syrup is an Ayurvedic sugar-free herbal formulation prepared with Dhatri Loh, Amalki Rasayan, Draksha, Bhringraj, Beetroot, Giloy, Punarnava, and other traditional herbs. It is designed to support healthy hemoglobin levels and overall nutritional wellness.

Its herbal ingredients help maintain energy levels and support general health.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic iron tonic</li>
<li>Sugar free</li>
<li>Supports hemoglobin</li>
<li>Supports overall vitality</li>
<li>Suitable for adults</li>
<li>200 ml pack</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Dhatri Loh</td><td class="border border-soft-border p-3">Traditional source of iron</td></tr><tr><td class="border border-soft-border p-3 font-medium">Amalki Rasayan</td><td class="border border-soft-border p-3">Supports iron absorption</td></tr><tr><td class="border border-soft-border p-3 font-medium">Draksha</td><td class="border border-soft-border p-3">Rich in natural nutrients</td></tr><tr><td class="border border-soft-border p-3 font-medium">Bhringraj</td><td class="border border-soft-border p-3">Traditionally supports overall wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Beetroot</td><td class="border border-soft-border p-3">Natural source of nutrients</td></tr><tr><td class="border border-soft-border p-3 font-medium">Giloy</td><td class="border border-soft-border p-3">Supports immunity</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults requiring nutritional support for healthy hemoglobin levels.",
                    'storage_instructions' => "Store in a cool and dry place.",
                    'disclaimer' => 'This is an Ayurvedic proprietary medicine. Use under medical supervision if pregnant, nursing, or under treatment.',
                    'precautions' => null,
                ]
            ],

            // 4. Kare Hb Tablet
            [
                'search' => 'HB Tablet',
                'data' => [
                    'name' => 'Kare Hb Tablet (650 mg)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '650 mg',
                    'usage_instructions' => 'Take 1–2 tablets twice daily or as directed by your Ayurvedic physician.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports healthy hemoglobin levels.</li>
<li>Helps maintain healthy red blood cell production.</li>
<li>Supports iron metabolism.</li>
<li>Helps reduce weakness and fatigue.</li>
<li>Supports overall health and vitality.</li>
<li>Suitable for daily nutritional support.</li>
</ul>',
                    'description' => 'Kare Hb Tablet (650 mg)

Kare Hb Tablet is an Ayurvedic herbal formulation prepared with Dhatri Loh, Amalki Rasayan, Abhrak Bhasma, Punarnavadi Mandoor, Shatavari, and Shunthi. It helps support healthy hemoglobin levels and overall nutritional wellness.

Its traditional ingredients help maintain energy, vitality, and healthy iron balance.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic herbal tablet</li>
<li>Supports hemoglobin</li>
<li>Supports healthy iron metabolism</li>
<li>Helps maintain energy levels</li>
<li>650 mg tablets</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Dhatri Loh</td><td class="border border-soft-border p-3">Traditional iron-rich ingredient</td></tr><tr><td class="border border-soft-border p-3 font-medium">Amalki Rasayan</td><td class="border border-soft-border p-3">Supports iron absorption</td></tr><tr><td class="border border-soft-border p-3 font-medium">Abhrak Bhasma</td><td class="border border-soft-border p-3">Traditionally used for vitality</td></tr><tr><td class="border border-soft-border p-3 font-medium">Punarnavadi Mandoor</td><td class="border border-soft-border p-3">Traditionally supports healthy blood formation</td></tr><tr><td class="border border-soft-border p-3 font-medium">Shatavari</td><td class="border border-soft-border p-3">Supports overall wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Shunthi</td><td class="border border-soft-border p-3">Traditionally supports digestion</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults requiring nutritional support for healthy hemoglobin levels.",
                    'storage_instructions' => "Store in a cool, dry place away from direct sunlight.",
                    'disclaimer' => 'This is an Ayurvedic proprietary medicine. Use under the guidance of a qualified healthcare professional.',
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
