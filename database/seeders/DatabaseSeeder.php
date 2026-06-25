<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@kareons.com'],
            [
                'name' => 'KareOns Admin',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'phone' => '1234567890',
            ]
        );

        // 2. Categories
        $categories = [
            ['name' => 'Immunity Boosters', 'slug' => 'immunity-boosters', 'description' => 'Natural herbs to build strength and immunity.'],
            ['name' => 'Hair Care', 'slug' => 'hair-care', 'description' => 'Ayurvedic solutions for strong, healthy hair.'],
            ['name' => 'Digestive Health', 'slug' => 'digestive-health', 'description' => 'Herbs to improve digestion and gut health.'],
            ['name' => 'Skin Care', 'slug' => 'skin-care', 'description' => 'Natural glow and skin healing herbs.']
        ];

        foreach ($categories as $catData) {
            \App\Models\Category::firstOrCreate(['slug' => $catData['slug']], $catData);
        }

        // 3. Products
        $immunityCat = \App\Models\Category::where('slug', 'immunity-boosters')->first();
        $digestionCat = \App\Models\Category::where('slug', 'digestive-health')->first();

        $products = [
            [
                'category_id' => $immunityCat->id,
                'name' => 'Organic Ashwagandha Root Powder',
                'slug' => 'organic-ashwagandha-root-powder',
                'sku' => 'KHO-ASH-001',
                'main_image' => 'products/dummy.jpg',
                'short_description' => 'Premium adaptogenic herb for stress relief, vitality, and immunity building.',
                'description' => 'Ashwagandha is an ancient medicinal herb known as an adaptogen. It helps your body manage stress, boosts brain function, and lowers blood sugar and cortisol levels.',
                'price' => 599.00,
                'sale_price' => 449.00,
                'stock_quantity' => 50,
                'status' => true,
                'featured' => true,
                'ingredients' => '100% Organic Withania Somnifera (Ashwagandha) Root Powder',
                'benefits' => "• Reduces stress and anxiety\n• Improves muscle strength and recovery\n• Boosts immunity",
                'usage_instructions' => 'Take 1/2 to 1 teaspoon with warm milk or water before bedtime.',
                'storage_instructions' => 'Store in a cool, dry place away from direct sunlight.',
                'precautions' => 'Not recommended for pregnant women without consulting a doctor.',
            ],
            [
                'category_id' => $digestionCat->id,
                'name' => 'Triphala Churna (Digestive Support)',
                'slug' => 'triphala-churna',
                'sku' => 'KHO-TRI-002',
                'main_image' => 'products/dummy.jpg',
                'short_description' => 'A classic Ayurvedic herbal formulation consisting of three fruits to promote healthy digestion.',
                'description' => 'Triphala gently cleanses and detoxifies the system while simultaneously replenishing and nourishing it. Excellent for overall gastrointestinal health.',
                'price' => 349.00,
                'sale_price' => null,
                'stock_quantity' => 100,
                'status' => true,
                'featured' => true,
                'ingredients' => 'Amla (Phyllanthus emblica), Bibhitaki (Terminalia bellirica), Haritaki (Terminalia chebula)',
                'benefits' => "• Promotes natural internal cleansing\n• Supports healthy digestion and absorption\n• Antioxidant-rich",
                'usage_instructions' => 'Take 1 teaspoon with warm water before bed or upon waking up.',
                'storage_instructions' => 'Keep the container tightly closed in a dry place.',
                'precautions' => 'If you have loose motions, reduce the dosage.',
            ]
        ];

        foreach ($products as $prodData) {
            \App\Models\Product::firstOrCreate(['slug' => $prodData['slug']], $prodData);
        }

        // 4. Blogs
        $blogs = [
            [
                'title' => 'The Power of Ashwagandha: Ancient Herb for Modern Stress',
                'slug' => 'power-of-ashwagandha',
                'category' => 'Ayurvedic Health Tips',
                'excerpt' => 'Learn how this powerful adaptogen can help you manage modern-day stress and build natural immunity.',
                'content' => '<p>Ashwagandha is perhaps the most famous Ayurvedic herb in the modern world, and for good reason. It belongs to a class of herbs called adaptogens...</p><h2>How it works</h2><p>It helps regulate cortisol levels and calms the nervous system.</p>',
                'author_id' => $admin->id,
                'status' => true,
                'published_at' => now()->subDays(2)
            ],
            [
                'title' => 'Why Triphala is the Ultimate Digestive Tonic',
                'slug' => 'triphala-ultimate-digestive-tonic',
                'category' => 'Digestive Health',
                'excerpt' => 'Triphala translates to "three fruits". Discover why this simple combination is the cornerstone of Ayurvedic digestion.',
                'content' => '<p>Digestion is the root of all health in Ayurveda. If your digestion (Agni) is strong, you are healthy. Triphala helps balance this...</p>',
                'author_id' => $admin->id,
                'status' => true,
                'published_at' => now()->subDays(5)
            ]
        ];

        foreach ($blogs as $blogData) {
            \App\Models\Blog::firstOrCreate(['slug' => $blogData['slug']], $blogData);
        }
    }
}
