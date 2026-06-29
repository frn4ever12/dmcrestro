<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::first();
        $branch = Branch::first();

        // Create categories
        $categories = [
            [
                'name' => 'Appetizers',
                'name_np' => 'खाजा',
                'slug' => 'appetizers',
                'description' => 'Start your meal with our delicious appetizers',
                'type' => 'food',
                'sort_order' => 1,
            ],
            [
                'name' => 'Main Course',
                'name_np' => 'मुख्य खाना',
                'slug' => 'main-course',
                'description' => 'Our signature main dishes',
                'type' => 'food',
                'sort_order' => 2,
            ],
            [
                'name' => 'Beverages',
                'name_np' => 'पेय पदार्थ',
                'slug' => 'beverages',
                'description' => 'Refreshing drinks and beverages',
                'type' => 'drinks',
                'sort_order' => 3,
            ],
            [
                'name' => 'Desserts',
                'name_np' => 'मिठाई',
                'slug' => 'desserts',
                'description' => 'Sweet treats to end your meal',
                'type' => 'dessert',
                'sort_order' => 4,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $category) {
            $createdCategories[$category['slug']] = MenuCategory::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'tenant_id' => $restaurant->tenant_id,
                'restaurant_id' => $restaurant->id,
                'branch_id' => $branch->id,
                ...$category,
                'is_available' => true,
                'is_active' => true,
            ]);
        }

        // Create menu items
        $items = [
            [
                'category_slug' => 'appetizers',
                'name' => 'Chicken Momos',
                'name_np' => 'चिकन मोमो',
                'slug' => 'chicken-momos',
                'description' => 'Steamed dumplings filled with spiced chicken',
                'price' => 250,
                'cost_price' => 150,
                'preparation_time' => 15,
                'is_spicy' => true,
            ],
            [
                'category_slug' => 'appetizers',
                'name' => 'Veg Momos',
                'name_np' => 'भेज मोमो',
                'slug' => 'veg-momos',
                'description' => 'Steamed dumplings filled with vegetables',
                'price' => 200,
                'cost_price' => 120,
                'preparation_time' => 15,
                'is_vegetarian' => true,
            ],
            [
                'category_slug' => 'main-course',
                'name' => 'Chicken Biryani',
                'name_np' => 'चिकन बिरयानी',
                'slug' => 'chicken-biryani',
                'description' => 'Fragrant basmati rice with spiced chicken',
                'price' => 450,
                'cost_price' => 280,
                'preparation_time' => 25,
                'is_spicy' => true,
            ],
            [
                'category_slug' => 'main-course',
                'name' => 'Dal Bhat',
                'name_np' => 'दाल भात',
                'slug' => 'dal-bhat',
                'description' => 'Traditional Nepali thali set',
                'price' => 350,
                'cost_price' => 200,
                'preparation_time' => 20,
                'is_vegetarian' => true,
            ],
            [
                'category_slug' => 'beverages',
                'name' => 'Masala Tea',
                'name_np' => 'मसाला चिया',
                'slug' => 'masala-tea',
                'description' => 'Spiced Indian tea',
                'price' => 80,
                'cost_price' => 30,
                'preparation_time' => 5,
                'is_vegetarian' => true,
            ],
            [
                'category_slug' => 'beverages',
                'name' => 'Fresh Lime Soda',
                'name_np' => 'ताजा लाइम सोडा',
                'slug' => 'fresh-lime-soda',
                'description' => 'Refreshing lime soda',
                'price' => 100,
                'cost_price' => 40,
                'preparation_time' => 5,
                'is_vegetarian' => true,
            ],
            [
                'category_slug' => 'desserts',
                'name' => 'Gulab Jamun',
                'name_np' => 'गुलाब जामुन',
                'slug' => 'gulab-jamun',
                'description' => 'Sweet milk dumplings in sugar syrup',
                'price' => 120,
                'cost_price' => 60,
                'preparation_time' => 10,
                'is_vegetarian' => true,
            ],
        ];

        foreach ($items as $item) {
            MenuItem::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'tenant_id' => $restaurant->tenant_id,
                'restaurant_id' => $restaurant->id,
                'branch_id' => $branch->id,
                'category_id' => $createdCategories[$item['category_slug']]->id,
                'name' => $item['name'],
                'name_np' => $item['name_np'],
                'slug' => $item['slug'],
                'description' => $item['description'],
                'price' => $item['price'],
                'cost_price' => $item['cost_price'],
                'preparation_time' => $item['preparation_time'],
                'is_vegetarian' => $item['is_vegetarian'] ?? false,
                'is_spicy' => $item['is_spicy'] ?? false,
                'is_available' => true,
                'is_active' => true,
            ]);
        }
    }
}
