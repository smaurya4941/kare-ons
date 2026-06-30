<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class UpdateFourthBatchProductsSeeder extends Seeder
{
    public function run()
    {
        $productsToUpdate = [
            // 1. Acetosum
            [
                'search' => 'Acetosum',
                'data' => [
                    'name' => 'Kare Ons Acetosum 650 MG',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '30 Tablets',
                    'usage_instructions' => 'Take 1–2 tablets twice daily, or as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports healthy digestion.</li>
<li>Helps maintain normal stomach acid balance.</li>
<li>Supports digestive comfort.</li>
<li>Helps reduce bloating and gastric discomfort.</li>
<li>Supports overall digestive wellness.</li>
<li>Traditional Ayurvedic formulation.</li>
</ul>',
                    'description' => 'Kare Ons Acetosum 650 MG

Kare Ons Acetosum is an Ayurvedic herbal formulation prepared with Sutshekhar Ras, Muktashukti, Shankh Bhasma, Madhuyashti, and Kamdudha Ras. It is designed to support healthy digestion, maintain stomach acid balance, and promote digestive comfort.

The traditional Ayurvedic ingredients help support proper digestive function and overall gastrointestinal wellness.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic digestive support</li>
<li>Supports stomach comfort</li>
<li>Helps maintain acid balance</li>
<li>Supports digestive wellness</li>
<li>Herbal formulation</li>
<li>650 MG</li>
<li>30 Tablets</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Sutshekhar Ras</td><td class="border border-soft-border p-3">Traditionally supports digestive balance</td></tr><tr><td class="border border-soft-border p-3 font-medium">Muktashukti</td><td class="border border-soft-border p-3">Supports stomach comfort</td></tr><tr><td class="border border-soft-border p-3 font-medium">Shankh Bhasma</td><td class="border border-soft-border p-3">Traditionally used for digestive wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Madhuyashti</td><td class="border border-soft-border p-3">Supports digestive and stomach health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Kamdudha Ras</td><td class="border border-soft-border p-3">Traditionally used in Ayurvedic digestive formulations</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults\nIndividuals seeking digestive wellness support",
                    'storage_instructions' => "Store in a cool and dry place away from direct sunlight.",
                    'disclaimer' => 'This product is not intended to diagnose, treat, cure, or prevent any disease.',
                    'precautions' => null,
                ]
            ],

            // 2. Shatpushpadi Tailam
            [
                'search' => 'Shatpushpadi',
                'data' => [
                    'name' => 'Kare Ons Shatpushpadi Tailam (30 ml Pack)',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '30 ml',
                    'usage_instructions' => 'Use only under the supervision of a qualified Ayurvedic healthcare professional. (Traditional Administration: Nasya, Uttar Basti)',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports women\'s reproductive wellness.</li>
<li>Helps maintain menstrual health.</li>
<li>Supports uterine wellness.</li>
<li>Traditional Ayurvedic therapeutic oil.</li>
<li>Designed for specialized Ayurvedic procedures.</li>
</ul>',
                    'description' => 'Kare Ons Shatpushpadi Tailam

Kare Ons Shatpushpadi Tailam is a traditional Ayurvedic medicated oil formulated according to classical Ayurvedic principles. It is intended for specialized therapeutic use under the supervision of a qualified Ayurvedic practitioner.

It supports women\'s reproductive wellness and is commonly used in traditional Panchakarma procedures such as Nasya and Uttar Basti.

<strong>Highlights</strong>
<ul>
<li>Classical Ayurvedic oil</li>
<li>Women\'s wellness support</li>
<li>Traditional Panchakarma preparation</li>
<li>30 ml pack</li>
</ul>',
                    'ingredients' => '<p>The formulation is based on the classical Ayurvedic preparation Shatpushpadi Tailam.</p>',
                    'suitable_for' => "Adults\nProfessional Ayurvedic therapeutic use",
                    'storage_instructions' => "Store in a cool and dry place.",
                    'disclaimer' => 'For therapeutic use only. Use under medical supervision. This product is not intended to diagnose, treat, cure, or prevent any disease.',
                    'precautions' => null,
                ]
            ],

            // 3. Sting Guard Cream
            [
                'search' => 'Sting',
                'data' => [
                    'name' => 'Kare Ons Sting Guard Cream (30 ml Pack)',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '30 ml',
                    'usage_instructions' => 'Apply a thin layer to clean, dry skin 2–3 times daily or as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports healthy skin.</li>
<li>Helps soothe irritated skin.</li>
<li>Helps moisturize dry skin.</li>
<li>Supports skin comfort.</li>
<li>Ayurvedic herbal skin care cream.</li>
</ul>',
                    'description' => 'Kare Ons Sting Guard Cream

Kare Ons Sting Guard Cream is an Ayurvedic herbal skin care cream enriched with Coconut Oil, Neem Oil, Cocoa Butter, Karanj, Purified Sulphur, and other traditional herbal ingredients. It is formulated to help moisturize, soothe, and protect the skin while supporting overall skin wellness.

Its nourishing herbal ingredients help maintain healthy skin and everyday skin comfort.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic skin care cream</li>
<li>Herbal moisturizing formula</li>
<li>Supports skin comfort</li>
<li>Easy-to-apply cream</li>
<li>30 ml pack</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Coconut Oil</td><td class="border border-soft-border p-3">Moisturizes skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Neem Oil</td><td class="border border-soft-border p-3">Traditionally supports skin hygiene</td></tr><tr><td class="border border-soft-border p-3 font-medium">Cocoa Butter</td><td class="border border-soft-border p-3">Nourishes dry skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Karanj</td><td class="border border-soft-border p-3">Traditionally used in skin care</td></tr><tr><td class="border border-soft-border p-3 font-medium">Shuddha Gandhak</td><td class="border border-soft-border p-3">Classical Ayurvedic skin ingredient</td></tr><tr><td class="border border-soft-border p-3 font-medium">Vidang</td><td class="border border-soft-border p-3">Traditionally supports skin wellness</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults\nDaily skin care",
                    'storage_instructions' => "Store in a cool, dry place away from sunlight.",
                    'disclaimer' => 'For external use only. Avoid contact with eyes and discontinue use if irritation occurs.',
                    'precautions' => null,
                ]
            ],

            // 4. Hepato Care
            [
                'search' => 'Hepato',
                'data' => [
                    'name' => 'Kare Ons Hepato Care 650 MG',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '30 Tablets',
                    'usage_instructions' => 'Take as directed by your healthcare professional.',
                    'ayurvedic_reference' => 'Phaltrikadi Kwath Ghan with Sarpunkha Ghan',
                    'benefits' => '<ul>
<li>Supports healthy liver function.</li>
<li>Supports digestive wellness.</li>
<li>Helps maintain liver health.</li>
<li>Supports the body\'s natural detoxification process.</li>
<li>Traditional Ayurvedic liver formulation.</li>
</ul>',
                    'description' => 'Kare Ons Hepato Care 650 MG

Kare Ons Hepato Care is an Ayurvedic herbal formulation prepared with Kutki, Chirayta, Sarpunkha, Triphala, and other traditional Ayurvedic ingredients. It is formulated to support healthy liver function, digestive wellness, and the body\'s natural detoxification processes.

The herbal blend helps maintain overall liver wellness as part of a healthy lifestyle.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic liver support</li>
<li>Supports healthy liver function</li>
<li>Supports digestion</li>
<li>Herbal ingredients</li>
<li>650 MG</li>
<li>30 Tablets</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Kutki</td><td class="border border-soft-border p-3">Traditionally supports liver wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Chirayta</td><td class="border border-soft-border p-3">Supports digestive and liver health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Sarpunkha</td><td class="border border-soft-border p-3">Traditionally used for liver support</td></tr><tr><td class="border border-soft-border p-3 font-medium">Triphala</td><td class="border border-soft-border p-3">Supports digestion and detoxification</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults\nIndividuals seeking Ayurvedic liver wellness support",
                    'storage_instructions' => "Store in a cool and dry place away from direct sunlight.",
                    'disclaimer' => 'This product is not intended to diagnose, treat, cure, or prevent any disease. Consult your healthcare professional before using this product, especially if you are pregnant, nursing, taking medication, or have an existing medical condition.',
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
