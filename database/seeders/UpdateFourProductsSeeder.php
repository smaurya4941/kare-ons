<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateFourProductsSeeder extends Seeder
{
    public function run()
    {
        $productsToUpdate = [
            // 1. Asthi Sukh Tablet
            [
                'search' => 'Asthisukh Tablet',
                'data' => [
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '30 Capsules',
                    'usage_instructions' => 'Take 1–2 tablets twice daily or as directed by your healthcare professional.',
                    'ayurvedic_reference' => 'Bhawna: Nirgundi and Vach',
                    'benefits' => '<ul>
<li>Supports joint comfort and flexibility.</li>
<li>Helps maintain healthy bones and joints.</li>
<li>Supports mobility.</li>
<li>Helps reduce joint stiffness.</li>
<li>Supports healthy inflammatory response.</li>
<li>Traditional Ayurvedic formulation for musculoskeletal wellness.</li>
</ul>',
                    'description' => 'Kare Ons Asthi Sukh Tablet 650 MG

Kare Ons Asthi Sukh Tablet is an Ayurvedic formulation developed to support joint health, flexibility, and mobility. It contains traditional herbs such as Rasana, Shallaki, Suranjana, Nirgundi, Ashwagandha, Giloy Satva, and other Ayurvedic ingredients that have traditionally been used to support healthy joints and muscles.

It is designed to promote joint comfort and overall musculoskeletal wellness as part of a healthy lifestyle.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic joint care formula</li>
<li>Supports bone health</li>
<li>Helps improve joint mobility</li>
<li>Supports muscle comfort</li>
<li>Herbal ingredients</li>
<li>650 MG</li>
<li>30 Capsules</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Rasana</td><td class="border border-soft-border p-3">Traditionally used for joint wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Shallaki</td><td class="border border-soft-border p-3">Supports joint mobility</td></tr><tr><td class="border border-soft-border p-3 font-medium">Suranjana</td><td class="border border-soft-border p-3">Supports healthy inflammatory response</td></tr><tr><td class="border border-soft-border p-3 font-medium">Nirgundi</td><td class="border border-soft-border p-3">Traditionally used for muscle and joint support</td></tr><tr><td class="border border-soft-border p-3 font-medium">Ashwagandha</td><td class="border border-soft-border p-3">Supports strength and vitality</td></tr><tr><td class="border border-soft-border p-3 font-medium">Giloy Satva</td><td class="border border-soft-border p-3">Supports immunity and wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Godantibhasma</td><td class="border border-soft-border p-3">Traditional Ayurvedic mineral ingredient</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults seeking herbal joint support\nIndividuals with active lifestyles\nPeople looking for Ayurvedic bone and joint wellness",
                    'storage_instructions' => "Store in a cool and dry place.\nProtect from sunlight.\nKeep out of reach of children.",
                    'disclaimer' => 'This product is not intended to diagnose, treat, cure, or prevent any disease.',
                    'precautions' => null,
                ]
            ],

            // 2. Gyno Care Vaginal Tablets
            [
                'search' => 'Gynocare Vaginal Tablet',
                'data' => [
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '6 Vaginal Tablets with Applicator',
                    'usage_instructions' => 'Use only as directed by a qualified healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports feminine intimate wellness.</li>
<li>Helps maintain healthy vaginal flora.</li>
<li>Supports vaginal hygiene.</li>
<li>Helps maintain vaginal comfort.</li>
<li>Traditional Ayurvedic feminine care formulation.</li>
</ul>',
                    'description' => 'Kare Ons Gyno Care Vaginal Tablets

Kare Ons Gyno Care Vaginal Tablets are an Ayurvedic feminine wellness formulation prepared with traditional herbs including Lodhra, Neem, Turmeric, Jaati, and Punchavalkala. These ingredients have traditionally been used in Ayurveda to support feminine hygiene, vaginal comfort, and overall intimate wellness.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic feminine care</li>
<li>Vaginal tablets (Pessary)</li>
<li>Supports intimate hygiene</li>
<li>Herbal formulation</li>
<li>Includes applicator</li>
<li>Pack of 6 tablets</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Lodhra</td><td class="border border-soft-border p-3">Traditionally used for feminine wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Neem</td><td class="border border-soft-border p-3">Supports skin and intimate hygiene</td></tr><tr><td class="border border-soft-border p-3 font-medium">Turmeric</td><td class="border border-soft-border p-3">Rich in natural antioxidants</td></tr><tr><td class="border border-soft-border p-3 font-medium">Jaati</td><td class="border border-soft-border p-3">Traditionally used for intimate care</td></tr><tr><td class="border border-soft-border p-3 font-medium">Punchavalkala</td><td class="border border-soft-border p-3">Supports vaginal wellness</td></tr></tbody></table></div>',
                    'suitable_for' => "Adult women\nFeminine intimate wellness support",
                    'storage_instructions' => "Store in a cool and dry place.\nKeep away from direct sunlight.",
                    'disclaimer' => 'This product is not intended to diagnose, treat, cure, or prevent any disease.',
                    'precautions' => null,
                ]
            ],

            // 3. PCOD Care Tablet
            [
                'search' => 'PCOD Care',
                'data' => [
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '60 Tablets',
                    'usage_instructions' => 'Take 1–2 tablets twice daily or as directed by your healthcare professional.',
                    'ayurvedic_reference' => 'Bhawna: Latakaranj, Sounth, Aloe Vera',
                    'benefits' => '<ul>
<li>Supports women\'s hormonal wellness.</li>
<li>Helps maintain reproductive health.</li>
<li>Supports menstrual wellness.</li>
<li>Supports overall female health.</li>
<li>Ayurvedic herbal formulation.</li>
</ul>',
                    'description' => 'Kare Ons PCOD Care Tablet 650 MG

Kare Ons PCOD Care Tablet is an Ayurvedic herbal formulation made with Kachnar Guggul, Moringa, Shilajeet, Shatpushpa, Aloe Vera, and other traditional Ayurvedic ingredients. It is designed to support hormonal balance, menstrual wellness, and overall reproductive health in women.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic women\'s wellness formula</li>
<li>Supports hormonal balance</li>
<li>Supports reproductive wellness</li>
<li>Herbal ingredients</li>
<li>650 MG</li>
<li>60 Tablets</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Kachnar Guggul</td><td class="border border-soft-border p-3">Traditionally used for glandular wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Moringa</td><td class="border border-soft-border p-3">Supports metabolic wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Shilajeet</td><td class="border border-soft-border p-3">Supports vitality</td></tr><tr><td class="border border-soft-border p-3 font-medium">Shatpushpa</td><td class="border border-soft-border p-3">Traditionally used for women\'s health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Trikatu</td><td class="border border-soft-border p-3">Supports digestion</td></tr><tr><td class="border border-soft-border p-3 font-medium">Aloe Vera</td><td class="border border-soft-border p-3">Supports overall wellness</td></tr></tbody></table></div>',
                    'suitable_for' => "Adult women\nWomen seeking hormonal wellness support",
                    'storage_instructions' => "Store in a cool and dry place.\nKeep away from moisture and sunlight.",
                    'disclaimer' => 'This product is not intended to diagnose, treat, cure, or prevent any disease.',
                    'precautions' => null,
                ]
            ],

            // 4. B.P Care Tablet
            [
                'search' => 'B.P Care',
                'data' => [
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '30 Tablets',
                    'usage_instructions' => 'Take 1 tablet twice daily or as directed by your healthcare professional.',
                    'ayurvedic_reference' => 'Bhawna: Tagar, Brahmi, Arjun, Jatamansi',
                    'benefits' => '<ul>
<li>Supports cardiovascular wellness.</li>
<li>Helps maintain healthy blood pressure already within the normal range.</li>
<li>Supports heart health.</li>
<li>Supports healthy circulation.</li>
<li>Traditional Ayurvedic formulation.</li>
</ul>',
                    'description' => 'Kare Ons B.P Care Tablet 650 MG

Kare Ons B.P Care Tablet is an Ayurvedic herbal formulation prepared with Serpgandha, Arjun, Brahmi, Jatamansi, Tagar, Punarnava, Moringa, and other traditional herbs. It is formulated to support cardiovascular health, healthy circulation, and overall heart wellness.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic heart wellness formula</li>
<li>Supports healthy circulation</li>
<li>Supports cardiovascular health</li>
<li>Herbal ingredients</li>
<li>650 MG</li>
<li>30 Tablets</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Serpgandha</td><td class="border border-soft-border p-3">Traditionally used for cardiovascular support</td></tr><tr><td class="border border-soft-border p-3 font-medium">Arjun</td><td class="border border-soft-border p-3">Supports heart health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Brahmi</td><td class="border border-soft-border p-3">Supports mental wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Jatamansi</td><td class="border border-soft-border p-3">Traditionally used for relaxation</td></tr><tr><td class="border border-soft-border p-3 font-medium">Tagar</td><td class="border border-soft-border p-3">Supports overall wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Punarnava</td><td class="border border-soft-border p-3">Supports kidney and fluid balance</td></tr><tr><td class="border border-soft-border p-3 font-medium">Moringa</td><td class="border border-soft-border p-3">Rich in antioxidants</td></tr><tr><td class="border border-soft-border p-3 font-medium">Ashwagandha</td><td class="border border-soft-border p-3">Supports vitality</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults seeking Ayurvedic heart wellness support\nIndividuals looking for herbal cardiovascular support",
                    'storage_instructions' => "Store in a cool and dry place.\nProtect from direct sunlight.",
                    'disclaimer' => 'This product is not intended to diagnose, treat, cure, or prevent any disease. Always consult your healthcare professional before using any herbal supplement, especially if you are pregnant, nursing, taking medication, or have an existing medical condition.',
                    'precautions' => null,
                ]
            ],
        ];

        foreach ($productsToUpdate as $item) {
            $product = Product::where('name', 'like', '%' . $item['search'] . '%')->first();
            if ($product) {
                $product->update($item['data']);
                echo "Updated: {$product->name}\n";
            } else {
                echo "Not found: {$item['search']}\n";
            }
        }
    }
}
