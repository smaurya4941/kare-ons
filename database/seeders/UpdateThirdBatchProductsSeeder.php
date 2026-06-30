<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class UpdateThirdBatchProductsSeeder extends Seeder
{
    public function run()
    {
        $productsToUpdate = [
            // 1. Draksha Cough Syrup
            [
                'search' => 'Draksha',
                'data' => [
                    'name' => 'Kare Ons Draksha Cough Syrup',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '100 ml',
                    'usage_instructions' => 'Take 10–20 ml two to three times daily, or as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports respiratory wellness.</li>
<li>Helps soothe the throat.</li>
<li>Supports healthy bronchial function.</li>
<li>Helps maintain clear airways.</li>
<li>Supports both dry and productive cough relief.</li>
<li>Traditional Ayurvedic herbal formulation.</li>
</ul>',
                    'description' => 'Kare Ons Draksha Cough Syrup

Kare Ons Draksha Cough Syrup is an Ayurvedic herbal syrup formulated with Bharangi, Adhatoda Vasica, Somlata, Tulsi, Draksha, Mulethi, and other traditional herbs. It is designed to support respiratory health, soothe throat irritation, and promote healthy airway function.

The herbal formulation helps maintain respiratory comfort during seasonal changes and supports overall lung wellness.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic cough syrup</li>
<li>Supports respiratory health</li>
<li>Soothes throat irritation</li>
<li>Supports bronchial wellness</li>
<li>Herbal formulation</li>
<li>100 ml</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Bharangi</td><td class="border border-soft-border p-3">Traditionally supports respiratory wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Adhatoda Vasica</td><td class="border border-soft-border p-3">Supports healthy breathing</td></tr><tr><td class="border border-soft-border p-3 font-medium">Somlata</td><td class="border border-soft-border p-3">Traditionally used for bronchial support</td></tr><tr><td class="border border-soft-border p-3 font-medium">Tulsi</td><td class="border border-soft-border p-3">Supports immunity and respiratory health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Draksha</td><td class="border border-soft-border p-3">Traditionally used to soothe the throat</td></tr><tr><td class="border border-soft-border p-3 font-medium">Mulethi</td><td class="border border-soft-border p-3">Supports throat comfort</td></tr><tr><td class="border border-soft-border p-3 font-medium">Kakra Singi</td><td class="border border-soft-border p-3">Traditionally used in Ayurvedic respiratory formulations</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults\nChildren (under medical supervision)\nIndividuals seeking Ayurvedic respiratory support",
                    'storage_instructions' => "Store in a cool and dry place. Protect from direct sunlight.",
                    'disclaimer' => 'This product is not intended to diagnose, treat, cure, or prevent any disease.',
                    'precautions' => null,
                ]
            ],

            // 2. Gyno Care Intimate Wash
            [
                'search' => 'Gynocare Intimate Wash',
                'data' => [
                    'name' => 'Kare Ons Gyno Care Intimate Wash (pH 3.5)',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => 'pH 3.5',
                    'usage_instructions' => 'Use externally during bathing or as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Helps maintain feminine intimate hygiene.</li>
<li>Supports the natural pH balance.</li>
<li>Cleanses gently without harsh chemicals.</li>
<li>Helps maintain freshness.</li>
<li>Supports healthy intimate skin.</li>
</ul>',
                    'description' => 'Kare Ons Gyno Care Intimate Wash

Kare Ons Gyno Care Intimate Wash is an Ayurvedic feminine hygiene wash enriched with Aloe Vera, Neem, Triphala, Majuphal, and Punchvalkal. It is specially formulated to gently cleanse the intimate area while helping maintain the natural pH balance and freshness.

Its herbal ingredients provide daily feminine hygiene support without disturbing the skin\'s natural protective barrier.

<strong>Highlights</strong>
<ul>
<li>pH 3.5 balanced formula</li>
<li>Ayurvedic feminine wash</li>
<li>Gentle daily cleansing</li>
<li>Supports intimate freshness</li>
<li>Soap-free formulation</li>
<li>Herbal ingredients</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Majuphal</td><td class="border border-soft-border p-3">Traditionally supports skin toning</td></tr><tr><td class="border border-soft-border p-3 font-medium">Aloe Vera</td><td class="border border-soft-border p-3">Moisturizes and soothes skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Punchvalkal</td><td class="border border-soft-border p-3">Supports intimate wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Neem</td><td class="border border-soft-border p-3">Supports skin hygiene</td></tr><tr><td class="border border-soft-border p-3 font-medium">Triphala</td><td class="border border-soft-border p-3">Rich in natural antioxidants</td></tr></tbody></table></div>',
                    'suitable_for' => "Adult women\nDaily feminine hygiene",
                    'storage_instructions' => "Store in a cool, dry place away from direct sunlight.",
                    'disclaimer' => 'For external use only. Avoid contact with eyes. Discontinue use if irritation occurs.',
                    'precautions' => null,
                ]
            ],

            // 3. Gyno Care Intimate Cream
            [
                'search' => 'Gynocare Intimate Cream',
                'data' => [
                    'name' => 'Kare Ons Gyno Care Intimate Cream',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => null,
                    'usage_instructions' => 'Apply externally on clean, dry skin or as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports intimate skin care.</li>
<li>Helps moisturize delicate skin.</li>
<li>Supports skin comfort.</li>
<li>Helps maintain healthy intimate skin.</li>
<li>Herbal Ayurvedic formulation.</li>
</ul>',
                    'description' => 'Kare Ons Gyno Care Intimate Cream

Kare Ons Gyno Care Intimate Cream is an Ayurvedic herbal formulation enriched with Lodhra, Aloe Vera, Majuphal, Curcumin, Punchvalkala, Calendula, and Triphala. It is designed to support intimate skin hydration, comfort, and overall feminine wellness.

Its herbal ingredients help nourish delicate skin while maintaining softness and freshness.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic intimate care cream</li>
<li>Supports skin hydration</li>
<li>Helps maintain skin comfort</li>
<li>Herbal ingredients</li>
<li>Daily feminine care</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Lodhra</td><td class="border border-soft-border p-3">Traditionally supports skin wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Majuphal</td><td class="border border-soft-border p-3">Supports skin toning</td></tr><tr><td class="border border-soft-border p-3 font-medium">Aloe Vera</td><td class="border border-soft-border p-3">Moisturizes and soothes</td></tr><tr><td class="border border-soft-border p-3 font-medium">Curcumin</td><td class="border border-soft-border p-3">Rich in natural antioxidants</td></tr><tr><td class="border border-soft-border p-3 font-medium">Punchvalkala</td><td class="border border-soft-border p-3">Supports skin health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Calendula</td><td class="border border-soft-border p-3">Helps nourish skin</td></tr><tr><td class="border border-soft-border p-3 font-medium">Triphala</td><td class="border border-soft-border p-3">Supports overall skin wellness</td></tr></tbody></table></div>',
                    'suitable_for' => "Adult women\nDaily intimate skin care",
                    'storage_instructions' => "Store in a cool and dry place.",
                    'disclaimer' => 'For external use only. Avoid contact with eyes and discontinue use if irritation occurs.',
                    'precautions' => null,
                ]
            ],

            // 4. Liv Cure DS
            [
                'search' => 'Liv Cure DS',
                'data' => [
                    'name' => 'Kare Ons Liv Cure DS Sugar Free Syrup',
                    'brand' => 'Ujita Herbals',
                    'pack_size' => '200 ml',
                    'usage_instructions' => 'Take 10–20 ml two to three times daily, or as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports liver health.</li>
<li>Supports normal liver function.</li>
<li>Helps maintain healthy digestion.</li>
<li>Supports natural detoxification processes.</li>
<li>Sugar-free Ayurvedic formulation.</li>
</ul>',
                    'description' => 'Kare Ons Liv Cure DS Sugar Free Syrup

Kare Ons Liv Cure DS is a sugar-free Ayurvedic liver wellness syrup formulated with Bhringraj, Bhui Amla, Sharpunkha, Kalmegh, Triphala, and other traditional herbs. It is designed to support healthy liver function, digestive wellness, and the body\'s natural detoxification process.

Its herbal formulation provides daily nutritional support for maintaining overall liver health.

<strong>Highlights</strong>
<ul>
<li>Sugar-free Ayurvedic syrup</li>
<li>Supports liver wellness</li>
<li>Supports digestive health</li>
<li>Herbal formulation</li>
<li>200 ml</li>
</ul>',
                    'ingredients' => '<div class="overflow-x-auto"><table class="w-full text-left text-sm border-collapse border border-soft-border"><thead class="bg-surface/50 font-medium"><tr><th class="border border-soft-border p-3 text-on-surface">Ingredient</th><th class="border border-soft-border p-3 text-on-surface">Traditional Benefit</th></tr></thead><tbody class="text-on-surface-variant"><tr><td class="border border-soft-border p-3 font-medium">Bhringraj</td><td class="border border-soft-border p-3">Traditionally supports liver wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Sharpunkha</td><td class="border border-soft-border p-3">Supports liver function</td></tr><tr><td class="border border-soft-border p-3 font-medium">Bhui Amla</td><td class="border border-soft-border p-3">Traditionally used for liver support</td></tr><tr><td class="border border-soft-border p-3 font-medium">Kalmegh</td><td class="border border-soft-border p-3">Supports digestive and liver health</td></tr><tr><td class="border border-soft-border p-3 font-medium">Triphala</td><td class="border border-soft-border p-3">Supports digestion</td></tr><tr><td class="border border-soft-border p-3 font-medium">Vidang</td><td class="border border-soft-border p-3">Traditionally supports digestive wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Kakmachi</td><td class="border border-soft-border p-3">Supports overall wellness</td></tr><tr><td class="border border-soft-border p-3 font-medium">Muli</td><td class="border border-soft-border p-3">Traditionally used in Ayurvedic formulations</td></tr></tbody></table></div>',
                    'suitable_for' => "Adults\nIndividuals seeking Ayurvedic liver wellness support\nThose preferring a sugar-free herbal formulation",
                    'storage_instructions' => "Store in a cool and dry place. Protect from direct sunlight.",
                    'disclaimer' => 'This product is not intended to diagnose, treat, cure, or prevent any disease. Consult your healthcare professional before use if you are pregnant, nursing, taking medication, or have any existing medical condition.',
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
