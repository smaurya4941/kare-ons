<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class UpdateNextFourProductsSeeder extends Seeder
{
    public function run()
    {
        $productsToUpdate = [
            // 1. Stress Cure
            [
                'search' => 'Stress',
                'data' => [
                    'name' => 'Kare Ons Stress Cure 650 MG',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '30 Capsules',
                    'usage_instructions' => 'Take 1–2 capsules at bedtime, or as directed by your healthcare professional.',
                    'ayurvedic_reference' => 'Bhawna: Tagar and Vach',
                    'benefits' => '<ul>
<li>Supports stress management.</li>
<li>Helps promote relaxation.</li>
<li>Supports restful sleep.</li>
<li>Helps maintain emotional well-being.</li>
<li>Supports nervous system health.</li>
<li>Traditional Ayurvedic herbal formulation.</li>
</ul>',
                    'description' => 'Kare Ons Stress Cure 650 MG

Kare Ons Stress Cure is an Ayurvedic herbal formulation designed to support mental wellness, relaxation, and healthy sleep patterns. It combines traditional herbs such as Jatamansi, Tagar, Ashwagandha, Vacha, Brahmi, and Khushmand Extract, which have been traditionally used in Ayurveda to help manage daily stress and promote a calm mind.

It supports overall emotional wellness and healthy sleep as part of a balanced lifestyle.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic stress relief formula</li>
<li>Supports relaxation</li>
<li>Promotes healthy sleep</li>
<li>Supports emotional wellness</li>
<li>Herbal ingredients</li>
<li>650 MG</li>
<li>30 Capsules</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Jatamansi</td><td class="border border-soft-border p-3">Traditionally supports mental calmness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Tagar</td><td class="border border-soft-border p-3">Supports relaxation and sleep</td></tr><tr><td class="border border-soft-border p-3 font-medium">Ashwagandha</td><td class="border border-soft-border p-3">Adaptogenic herb for stress support</td></tr><tr><td class="border border-soft-border p-3 font-medium">Vacha</td><td class="border border-soft-border p-3">Traditionally supports brain health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Brahmi</td><td class="border border-soft-border p-3">Supports memory and cognitive wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Khushmand Extract</td><td class="border border-soft-border p-3">Traditionally used for calming support</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults experiencing everyday stress\nIndividuals seeking natural sleep support\nPeople looking for Ayurvedic mental wellness support",
                    'storage_instructions' => "Store in a cool and dry place.\nProtect from sunlight.\nKeep away from children.",
                    'disclaimer' => 'This product is not intended to diagnose, treat, cure, or prevent any disease.',
                    'precautions' => null,
                ]
            ],

            // 2. Asthi Sukh Oil
            [
                'search' => 'Asthi Sukh Oil',
                'data' => [
                    'name' => 'Kare Ons Asthi Sukh Oil',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '60 ml',
                    'usage_instructions' => 'Massage gently over the affected area 1–2 times daily or as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports joint comfort.</li>
<li>Helps relax muscles.</li>
<li>Supports flexibility and mobility.</li>
<li>Helps soothe stiffness.</li>
<li>Supports healthy movement.</li>
<li>Ayurvedic herbal massage oil.</li>
</ul>',
                    'description' => 'Kare Ons Asthi Sukh Oil

Kare Ons Asthi Sukh Oil is an Ayurvedic herbal massage oil formulated with Til Oil, Guggul, Nilgiri Oil, Kapoor, Castor Oil, Mahanarayan Oil, and Peppermint. This traditional blend is designed to support joint comfort, muscle relaxation, and healthy mobility.

Regular massage may help maintain flexibility and overall musculoskeletal wellness.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic massage oil</li>
<li>Supports joint comfort</li>
<li>Helps relax muscles</li>
<li>Supports mobility</li>
<li>Fast-absorbing herbal oil</li>
<li>60 ml</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Til Oil</td><td class="border border-soft-border p-3">Nourishes joints and muscles</td></tr><tr><td class="border border-soft-border p-3 font-medium">Guggul</td><td class="border border-soft-border p-3">Traditionally supports joint health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Nilgiri Oil</td><td class="border border-soft-border p-3">Cooling and soothing</td></tr><tr><td class="border border-soft-border p-3 font-medium">Kapoor</td><td class="border border-soft-border p-3">Provides a refreshing sensation</td></tr><tr><td class="border border-soft-border p-3 font-medium">Castor Oil</td><td class="border border-soft-border p-3">Supports muscle relaxation</td></tr><tr><td class="border border-soft-border p-3 font-medium">Mahanarayan Oil</td><td class="border border-soft-border p-3">Classical Ayurvedic massage oil</td></tr><tr><td class="border border-soft-border p-3 font-medium">Peppermint</td><td class="border border-soft-border p-3">Cooling herbal ingredient</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults\nActive individuals\nJoint and muscle wellness support",
                    'storage_instructions' => "Store in a cool and dry place away from direct sunlight.",
                    'disclaimer' => 'For external use only. Avoid contact with eyes and broken skin.',
                    'precautions' => null,
                ]
            ],

            // 3. Asthi Sukh Roll On
            [
                'search' => 'Roll',
                'data' => [
                    'name' => 'Kare Ons Asthi Sukh Roll On 50 ml',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '50 ml',
                    'usage_instructions' => 'Apply directly over the affected area and massage gently 1–2 times daily.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Convenient roll-on application.</li>
<li>Supports joint comfort.</li>
<li>Helps relax muscles.</li>
<li>Supports mobility.</li>
<li>Cooling herbal formula.</li>
<li>Easy to carry.</li>
</ul>',
                    'description' => 'Kare Ons Asthi Sukh Roll On 50 ml

Kare Ons Asthi Sukh Roll On is a convenient Ayurvedic herbal roll-on specially formulated for easy application over joints and muscles. It combines Til Oil, Guggul, Nilgiri Oil, Kapoor, Castor Oil, Peppermint, and Mahanarayan Oil to provide soothing massage and support everyday mobility.

The roll-on design makes it ideal for quick application at home, office, or while travelling.

<strong>Highlights</strong>
<ul>
<li>Easy roll-on application</li>
<li>Ayurvedic herbal formula</li>
<li>Supports joint and muscle comfort</li>
<li>Travel friendly</li>
<li>Cooling effect</li>
<li>50 ml</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Til Oil</td><td class="border border-soft-border p-3">Nourishes muscles</td></tr><tr><td class="border border-soft-border p-3 font-medium">Guggul</td><td class="border border-soft-border p-3">Supports joint wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Nilgiri Oil</td><td class="border border-soft-border p-3">Refreshing herbal oil</td></tr><tr><td class="border border-soft-border p-3 font-medium">Kapoor</td><td class="border border-soft-border p-3">Cooling sensation</td></tr><tr><td class="border border-soft-border p-3 font-medium">Castor Oil</td><td class="border border-soft-border p-3">Supports muscle comfort</td></tr><tr><td class="border border-soft-border p-3 font-medium">Peppermint</td><td class="border border-soft-border p-3">Refreshing herbal ingredient</td></tr><tr><td class="border border-soft-border p-3 font-medium">Mahanarayan Oil</td><td class="border border-soft-border p-3">Classical Ayurvedic oil</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults\nJoint and muscle wellness\nDaily use",
                    'storage_instructions' => "Store in a cool and dry place.",
                    'disclaimer' => 'For external use only. Avoid contact with eyes and damaged skin.',
                    'precautions' => null,
                ]
            ],

            // 4. Provit
            [
                'search' => 'Provit',
                'data' => [
                    'name' => 'Kare Ons Provit Nutritional Supplement (Sugar Free)',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => 'Vanilla Flavour',
                    'usage_instructions' => 'Mix 5–10 g in milk or water and consume as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports daily nutrition.</li>
<li>High-protein nutritional supplement.</li>
<li>Supports strength and stamina.</li>
<li>Helps maintain healthy body weight.</li>
<li>Supports muscle health.</li>
<li>Sugar-free formulation.</li>
</ul>',
                    'description' => 'Kare Ons Provit Nutritional Supplement

Kare Ons Provit is a sugar-free Ayurvedic nutritional supplement enriched with high-quality protein and traditional herbs such as Ashwagandha, Shatawari, Vidarikand, Soybean Protein, and Sahijan. It is formulated to support daily nutrition, strength, stamina, and overall wellness.

Its balanced nutritional composition makes it suitable for individuals seeking additional nutritional support as part of a healthy diet.

<strong>Highlights</strong>
<ul>
<li>Sugar-free formula</li>
<li>High protein nutritional supplement</li>
<li>Supports strength and stamina</li>
<li>Supports muscle health</li>
<li>Vanilla flavour</li>
<li>Ayurvedic nutritional blend</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Ashwagandha</td><td class="border border-soft-border p-3">Supports vitality and energy</td></tr><tr><td class="border border-soft-border p-3 font-medium">Shatawari</td><td class="border border-soft-border p-3">Traditionally used as a nutritive tonic</td></tr><tr><td class="border border-soft-border p-3 font-medium">Vidarikand</td><td class="border border-soft-border p-3">Supports nourishment and strength</td></tr><tr><td class="border border-soft-border p-3 font-medium">Soybean Protein</td><td class="border border-soft-border p-3">High-quality plant protein</td></tr><tr><td class="border border-soft-border p-3 font-medium">Sahijan (Moringa)</td><td class="border border-soft-border p-3">Rich source of vitamins and minerals</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults\nElderly individuals\nGrowing children\nIndividuals seeking nutritional supplementation",
                    'storage_instructions' => "Store in a cool, dry place. Keep the container tightly closed after use.",
                    'disclaimer' => 'This product is a nutritional supplement and is not intended to diagnose, treat, cure, or prevent any disease. Consult your healthcare professional before use if you have any medical condition or are taking medication.',
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
