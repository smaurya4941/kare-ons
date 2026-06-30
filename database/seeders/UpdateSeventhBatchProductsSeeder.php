<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class UpdateSeventhBatchProductsSeeder extends Seeder
{
    public function run()
    {
        $productsToUpdate = [
            // 1. Women Bliss Plus
            [
                'search' => 'Women Bliss',
                'data' => [
                    'name' => 'Kare Ons Women Bliss Plus (700 mg)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '700 mg',
                    'usage_instructions' => 'Take 1 tablet twice daily or as directed by your physician.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports women\'s vitality and wellness.</li>
<li>Helps maintain energy and stamina.</li>
<li>Supports hormonal balance.</li>
<li>Helps improve endurance.</li>
<li>Supports overall reproductive wellness.</li>
<li>Helps reduce fatigue and promotes daily performance.</li>
</ul>',
                    'description' => 'Kare Ons Women Bliss Plus (700 mg)

Kare Ons Women Bliss Plus is an Ayurvedic formulation containing Shilajit, Ashwagandha, Fenugreek, Maca Root, Safed Musli, Kaunch Beej, and other traditional herbs and minerals. It is designed to support women\'s vitality, stamina, hormonal wellness, and overall energy levels.

The herbal ingredients help promote endurance and support an active lifestyle.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic wellness supplement</li>
<li>Supports female vitality</li>
<li>Helps maintain energy</li>
<li>Supports hormonal balance</li>
<li>Rich herbal formulation</li>
<li>700 mg tablets</li>
</ul>',
                    'ingredients' => '<ul>
<li>Shilajit Extract (20% Fulvic Acid)</li>
<li>Ashwagandha Extract (5% HPLC)</li>
<li>Fenugreek Extract</li>
<li>Maca Root Extract</li>
<li>Gokshru</li>
<li>Kaunch Beej</li>
<li>Safed Musli</li>
<li>Kant Loh Bhasma</li>
<li>Rajat Bhasma</li>
<li>Abhrak Bhasma</li>
<li>Caffeine</li>
<li>Swarna Bhasma</li>
</ul>',
                    'suitable_for' => "Adult Women",
                    'storage_instructions' => "Store in a cool, dry place away from direct sunlight.",
                    'disclaimer' => 'This is an Ayurvedic proprietary medicine. Use under medical supervision during pregnancy or lactation.',
                    'precautions' => null,
                ]
            ],

            // 2. Eros Plus
            [
                'search' => 'Eros Plus',
                'data' => [
                    'name' => 'Kare Ons Eros Plus',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => null,
                    'usage_instructions' => 'Take 1 tablet twice daily or as directed by your healthcare professional.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports male vitality and wellness.</li>
<li>Helps maintain stamina and endurance.</li>
<li>Supports energy metabolism.</li>
<li>Promotes overall physical performance.</li>
<li>Helps reduce fatigue.</li>
<li>Supports an active lifestyle.</li>
</ul>',
                    'description' => 'Kare Ons Eros Plus

Kare Ons Eros Plus is an Ayurvedic herbal formulation prepared with Shilajit, Ashwagandha, Maca Root, Fenugreek, Safed Musli, Kaunch Beej, and other traditional herbs and minerals. It helps support men\'s vitality, stamina, energy, and overall wellness.

The herbal ingredients help maintain strength and endurance naturally.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic men\'s wellness supplement</li>
<li>Supports stamina</li>
<li>Supports vitality</li>
<li>Supports energy levels</li>
<li>Herbal formulation</li>
</ul>',
                    'ingredients' => '<ul>
<li>Shilajit Extract (20% Fulvic Acid)</li>
<li>Ashwagandha Extract (5% HPLC)</li>
<li>Fenugreek Extract</li>
<li>Maca Root</li>
<li>Gokshru</li>
<li>Kaunch Beej</li>
<li>Safed Musli</li>
<li>Rajat Bhasma</li>
<li>Abhrak Bhasma</li>
<li>Swarna Bhasma</li>
<li>Caffeine</li>
</ul>',
                    'suitable_for' => "Adult Men",
                    'storage_instructions' => "Store in a cool, dry place.",
                    'disclaimer' => 'This is an Ayurvedic proprietary medicine. Use only as directed by a healthcare professional.',
                    'precautions' => null,
                ]
            ],

            // 3. B Stop
            [
                'search' => 'B Stop',
                'data' => [
                    'name' => 'Kare Ons B Stop (650 mg)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '650 mg',
                    'usage_instructions' => 'Take 1–2 tablets twice daily or as directed by your physician.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports women\'s reproductive health.</li>
<li>Helps maintain normal menstrual wellness.</li>
<li>Supports healthy uterine function.</li>
<li>Traditionally used for women\'s health management.</li>
<li>Supports overall gynecological wellness.</li>
</ul>',
                    'description' => 'Kare Ons B Stop (650 mg)

Kare Ons B Stop is an Ayurvedic herbal formulation prepared with Bolbhadar Ras, Nagkesar, Lodhra, Kaharwa Pishti, and other traditional ingredients. It is formulated to support women\'s reproductive wellness and healthy menstrual function.

The herbal ingredients help maintain overall gynecological health.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic women\'s health tablet</li>
<li>Supports menstrual wellness</li>
<li>Traditional herbal formulation</li>
<li>650 mg tablets</li>
</ul>',
                    'ingredients' => '<ul>
<li>Bolbhadar Ras</li>
<li>Kaharwa Pishti</li>
<li>Nagkesar</li>
<li>Lodhra</li>
<li>Kamdudha Ras</li>
<li>Pushyanug Churna</li>
<li>Kamarkas</li>
</ul>',
                    'suitable_for' => "Adult Women",
                    'storage_instructions' => "Store in a cool, dry place.",
                    'disclaimer' => 'This is an Ayurvedic proprietary medicine. Use under the supervision of a qualified healthcare professional.',
                    'precautions' => null,
                ]
            ],

            // 4. Beej Poshan
            [
                'search' => 'Beej Poshan',
                'data' => [
                    'name' => 'Kare Ons Beej Poshan (650 mg)',
                    'brand' => 'Kare Ons Herbal',
                    'pack_size' => '650 mg',
                    'usage_instructions' => 'Take 1–2 tablets twice daily or as directed by your Ayurvedic physician.',
                    'ayurvedic_reference' => null,
                    'benefits' => '<ul>
<li>Supports reproductive wellness.</li>
<li>Helps maintain hormonal balance.</li>
<li>Supports ovulation and ovarian health.</li>
<li>Helps nourish reproductive tissues.</li>
<li>Supports women\'s fertility wellness.</li>
<li>Traditional Ayurvedic formulation.</li>
</ul>',
                    'description' => 'Kare Ons Beej Poshan (650 mg)

Kare Ons Beej Poshan is an Ayurvedic herbal formulation prepared with Putrajeevak, Shivlingi Seeds, Pushpadhanva Ras, Shatavari, Vidarikand, Mulethi, and other traditional herbs. It is designed to support women\'s reproductive health, hormonal balance, and fertility wellness.

The herbal ingredients help nourish reproductive tissues and support overall gynecological health.

<strong>Highlights</strong>
<ul>
<li>Ayurvedic fertility support tablet</li>
<li>Supports reproductive health</li>
<li>Supports hormonal wellness</li>
<li>Traditional herbal formulation</li>
<li>650 mg tablets</li>
</ul>',
                    'ingredients' => '<ul>
<li>Putrajeevak</li>
<li>Shivlingi Seeds</li>
<li>Pushpadhanva Ras</li>
<li>Shatavari</li>
<li>Garbhpal Ras</li>
<li>Mulethi</li>
<li>Vidarikand</li>
<li>Nagkesar</li>
</ul>',
                    'suitable_for' => "Adult Women",
                    'storage_instructions' => "Store in a cool, dry place away from direct sunlight.",
                    'disclaimer' => 'This is an Ayurvedic proprietary medicine. It is not intended to diagnose, treat, cure, or prevent any disease. Use under the guidance of a qualified healthcare professional, especially if pregnant, breastfeeding, or undergoing fertility treatment.',
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
