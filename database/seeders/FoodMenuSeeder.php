<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FoodMenuSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Seeding food menu categories and items...');
        
        DB::beginTransaction();
        
        try {
            // Get first tenant and restaurant
            $tenant = \App\Models\Tenant::first();
            $restaurant = \App\Models\Restaurant::first();
            $branch = \App\Models\Branch::first();
            
            if (!$tenant || !$restaurant) {
                $this->command->warn('No tenant or restaurant found. Please seed tenants first.');
                return;
            }
            
            // Create Menu Categories
            $categories = [
                [
                    'name' => 'Pizza',
                    'name_np' => 'पिज्जा',
                    'slug' => 'pizza',
                    'description' => 'Delicious Italian pizzas with various toppings',
                    'icon' => '🍕',
                    'type' => 'food',
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Burger',
                    'name_np' => 'बर्गर',
                    'slug' => 'burger',
                    'description' => 'Juicy burgers with fresh ingredients',
                    'icon' => '🍔',
                    'type' => 'food',
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Momo',
                    'name_np' => 'मम',
                    'slug' => 'momo',
                    'description' => 'Traditional Nepali dumplings',
                    'icon' => '🥟',
                    'type' => 'food',
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Chicken',
                    'name_np' => 'चिकन',
                    'slug' => 'chicken',
                    'description' => 'Grilled and fried chicken dishes',
                    'icon' => '🍗',
                    'type' => 'food',
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Drinks',
                    'name_np' => 'पेय पदार्थ',
                    'slug' => 'drinks',
                    'description' => 'Refreshing beverages and soft drinks',
                    'icon' => '🥤',
                    'type' => 'drinks',
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Dessert',
                    'name_np' => 'मिठाई',
                    'slug' => 'dessert',
                    'description' => 'Sweet treats and desserts',
                    'icon' => '🍰',
                    'type' => 'dessert',
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Noodles',
                    'name_np' => 'नूडल्स',
                    'slug' => 'noodles',
                    'description' => 'Asian style noodles',
                    'icon' => '🍜',
                    'type' => 'food',
                    'sort_order' => 7,
                ],
                [
                    'name' => 'Rice Items',
                    'name_np' => 'भातका वस्तु',
                    'slug' => 'rice',
                    'description' => 'Biryani, fried rice and rice dishes',
                    'icon' => '🍚',
                    'type' => 'food',
                    'sort_order' => 8,
                ],
                [
                    'name' => 'Soup',
                    'name_np' => 'सूप',
                    'slug' => 'soup',
                    'description' => 'Hot and cold soups',
                    'icon' => '🍲',
                    'type' => 'food',
                    'sort_order' => 9,
                ],
                [
                    'name' => 'Sandwich',
                    'name_np' => 'स्यान्डविच',
                    'slug' => 'sandwich',
                    'description' => 'Fresh sandwiches and subs',
                    'icon' => '🥪',
                    'type' => 'food',
                    'sort_order' => 10,
                ],
            ];
            
            $createdCategories = [];
            foreach ($categories as $category) {
                $createdCategory = MenuCategory::firstOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'restaurant_id' => $restaurant->id,
                        'slug' => $category['slug'],
                    ],
                    array_merge($category, [
                        'uuid' => Str::uuid(),
                        'tenant_id' => $tenant->id,
                        'restaurant_id' => $restaurant->id,
                        'branch_id' => $branch ? $branch->id : null,
                        'is_available' => true,
                        'is_active' => true,
                    ])
                );
                $createdCategories[$category['slug']] = $createdCategory;
            }
            
            $this->command->info('Menu categories seeded successfully.');
            
            // Create Menu Items
            $menuItems = [
                // Pizza Items
                [
                    'category' => 'pizza',
                    'items' => [
                        [
                            'name' => 'Margherita Pizza',
                            'name_np' => 'मार्गेरिटा पिज्जा',
                            'slug' => 'margherita-pizza',
                            'description' => 'Classic tomato sauce, mozzarella cheese, and fresh basil',
                            'price' => 450,
                            'cost_price' => 200,
                            'is_vegetarian' => true,
                            'is_vegan' => false,
                            'preparation_time' => 15,
                            'calories' => 850,
                            'image' => 'pizza/margherita.jpg',
                        ],
                        [
                            'name' => 'Pepperoni Pizza',
                            'name_np' => 'पेपेरोनी पिज्जा',
                            'slug' => 'pepperoni-pizza',
                            'description' => 'Tomato sauce, mozzarella, and spicy pepperoni',
                            'price' => 550,
                            'cost_price' => 250,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 15,
                            'calories' => 950,
                            'image' => 'pizza/pepperoni.jpg',
                        ],
                        [
                            'name' => 'BBQ Chicken Pizza',
                            'name_np' => 'BBQ चिकन पिज्जा',
                            'slug' => 'bbq-chicken-pizza',
                            'description' => 'BBQ sauce, grilled chicken, red onions, and cilantro',
                            'price' => 650,
                            'cost_price' => 300,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 18,
                            'calories' => 1050,
                            'image' => 'pizza/bbq-chicken.jpg',
                        ],
                        [
                            'name' => 'Veggie Supreme',
                            'name_np' => 'भेजी सुप्रीम',
                            'slug' => 'veggie-supreme',
                            'description' => 'Bell peppers, mushrooms, onions, olives, and tomatoes',
                            'price' => 520,
                            'cost_price' => 240,
                            'is_vegetarian' => true,
                            'is_vegan' => true,
                            'preparation_time' => 16,
                            'calories' => 780,
                            'image' => 'pizza/veggie-supreme.jpg',
                        ],
                    ],
                ],
                // Burger Items
                [
                    'category' => 'burger',
                    'items' => [
                        [
                            'name' => 'Chicken Burger',
                            'name_np' => 'चिकन बर्गर',
                            'slug' => 'chicken-burger',
                            'description' => 'Grilled chicken patty with lettuce, tomato, and mayo',
                            'price' => 280,
                            'cost_price' => 130,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 12,
                            'calories' => 650,
                            'image' => 'burger/chicken.jpg',
                        ],
                        [
                            'name' => 'Veg Burger',
                            'name_np' => 'भेजी बर्गर',
                            'slug' => 'veg-burger',
                            'description' => 'Veggie patty with fresh vegetables and cheese',
                            'price' => 220,
                            'cost_price' => 100,
                            'is_vegetarian' => true,
                            'is_vegan' => false,
                            'preparation_time' => 10,
                            'calories' => 480,
                            'image' => 'burger/veg.jpg',
                        ],
                        [
                            'name' => 'Cheese Burger',
                            'name_np' => 'चीज बर्गर',
                            'slug' => 'cheese-burger',
                            'description' => 'Beef patty with melted cheese and special sauce',
                            'price' => 320,
                            'cost_price' => 150,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 12,
                            'calories' => 750,
                            'image' => 'burger/cheese.jpg',
                        ],
                        [
                            'name' => 'Spicy Chicken Burger',
                            'name_np' => 'स्पाइसी चिकन बर्गर',
                            'slug' => 'spicy-chicken-burger',
                            'description' => 'Crispy chicken with spicy sauce and jalapeños',
                            'price' => 300,
                            'cost_price' => 140,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 12,
                            'calories' => 700,
                            'image' => 'burger/spicy-chicken.jpg',
                        ],
                    ],
                ],
                // Momo Items
                [
                    'category' => 'momo',
                    'items' => [
                        [
                            'name' => 'Chicken Momo (10 pcs)',
                            'name_np' => 'चिकन मम (१० वटा)',
                            'slug' => 'chicken-momo',
                            'description' => 'Steamed dumplings filled with spiced chicken',
                            'price' => 180,
                            'cost_price' => 80,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 15,
                            'calories' => 420,
                            'image' => 'momo/chicken.jpg',
                        ],
                        [
                            'name' => 'Veg Momo (10 pcs)',
                            'name_np' => 'भेजी मम (१० वटा)',
                            'slug' => 'veg-momo',
                            'description' => 'Steamed dumplings filled with mixed vegetables',
                            'price' => 150,
                            'cost_price' => 60,
                            'is_vegetarian' => true,
                            'is_vegan' => true,
                            'preparation_time' => 15,
                            'calories' => 320,
                            'image' => 'momo/veg.jpg',
                        ],
                        [
                            'name' => 'Fried Chicken Momo (10 pcs)',
                            'name_np' => 'तलेको चिकन मम (१० वटा)',
                            'slug' => 'fried-chicken-momo',
                            'description' => 'Deep fried chicken momo with spicy chutney',
                            'price' => 200,
                            'cost_price' => 90,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 18,
                            'calories' => 520,
                            'image' => 'momo/fried-chicken.jpg',
                        ],
                        [
                            'name' => 'C Momo (10 pcs)',
                            'name_np' => 'सी मम (१० वटा)',
                            'slug' => 'c-momo',
                            'description' => 'Momo served in spicy chili sauce',
                            'price' => 220,
                            'cost_price' => 100,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 18,
                            'calories' => 550,
                            'image' => 'momo/c-momo.jpg',
                        ],
                    ],
                ],
                // Chicken Items
                [
                    'category' => 'chicken',
                    'items' => [
                        [
                            'name' => 'Grilled Chicken',
                            'name_np' => 'ग्रिल्ड चिकन',
                            'slug' => 'grilled-chicken',
                            'description' => 'Marinated grilled chicken with herbs',
                            'price' => 350,
                            'cost_price' => 180,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 20,
                            'calories' => 450,
                            'image' => 'chicken/grilled.jpg',
                        ],
                        [
                            'name' => 'Chicken Wings (6 pcs)',
                            'name_np' => 'चिकन विंग्स (६ वटा)',
                            'slug' => 'chicken-wings',
                            'description' => 'Crispy chicken wings with dipping sauce',
                            'price' => 280,
                            'cost_price' => 140,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 18,
                            'calories' => 380,
                            'image' => 'chicken/wings.jpg',
                        ],
                        [
                            'name' => 'Chicken Tikka',
                            'name_np' => 'चिकन टिक्का',
                            'slug' => 'chicken-tikka',
                            'description' => 'Spiced grilled chicken cubes',
                            'price' => 320,
                            'cost_price' => 160,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 20,
                            'calories' => 420,
                            'image' => 'chicken/tikka.jpg',
                        ],
                    ],
                ],
                // Drinks Items
                [
                    'category' => 'drinks',
                    'items' => [
                        [
                            'name' => 'Coca Cola (500ml)',
                            'name_np' => 'कोका कोला (५०० मिली)',
                            'slug' => 'coca-cola',
                            'description' => 'Classic carbonated soft drink',
                            'price' => 80,
                            'cost_price' => 40,
                            'is_vegetarian' => true,
                            'is_vegan' => true,
                            'preparation_time' => 2,
                            'calories' => 210,
                            'image' => 'drinks/coca-cola.jpg',
                        ],
                        [
                            'name' => 'Fresh Orange Juice',
                            'name_np' => 'ताजा सुन्तला जुस',
                            'slug' => 'orange-juice',
                            'description' => 'Freshly squeezed orange juice',
                            'price' => 120,
                            'cost_price' => 60,
                            'is_vegetarian' => true,
                            'is_vegan' => true,
                            'preparation_time' => 5,
                            'calories' => 110,
                            'image' => 'drinks/orange-juice.jpg',
                        ],
                        [
                            'name' => 'Mango Lassi',
                            'name_np' => 'आम लस्सी',
                            'slug' => 'mango-lassi',
                            'description' => 'Creamy mango yogurt drink',
                            'price' => 100,
                            'cost_price' => 50,
                            'is_vegetarian' => true,
                            'is_vegan' => false,
                            'preparation_time' => 5,
                            'calories' => 180,
                            'image' => 'drinks/mango-lassi.jpg',
                        ],
                        [
                            'name' => 'Iced Tea',
                            'name_np' => 'आइस्ड टी',
                            'slug' => 'iced-tea',
                            'description' => 'Refreshing iced tea with lemon',
                            'price' => 70,
                            'cost_price' => 30,
                            'is_vegetarian' => true,
                            'is_vegan' => true,
                            'preparation_time' => 3,
                            'calories' => 45,
                            'image' => 'drinks/iced-tea.jpg',
                        ],
                    ],
                ],
                // Dessert Items
                [
                    'category' => 'dessert',
                    'items' => [
                        [
                            'name' => 'Chocolate Cake',
                            'name_np' => 'चकलेट केक',
                            'slug' => 'chocolate-cake',
                            'description' => 'Rich chocolate cake with chocolate ganache',
                            'price' => 200,
                            'cost_price' => 80,
                            'is_vegetarian' => true,
                            'is_vegan' => false,
                            'preparation_time' => 5,
                            'calories' => 450,
                            'image' => 'dessert/chocolate-cake.jpg',
                        ],
                        [
                            'name' => 'Ice Cream (Vanilla)',
                            'name_np' => 'आइस क्रिम (भ्यानिला)',
                            'slug' => 'vanilla-ice-cream',
                            'description' => 'Creamy vanilla ice cream',
                            'price' => 120,
                            'cost_price' => 50,
                            'is_vegetarian' => true,
                            'is_vegan' => false,
                            'preparation_time' => 2,
                            'calories' => 250,
                            'image' => 'dessert/vanilla-ice-cream.jpg',
                        ],
                        [
                            'name' => 'Gulab Jamun',
                            'name_np' => 'गुलाब जामुन',
                            'slug' => 'gulab-jamun',
                            'description' => 'Sweet milk dumplings in sugar syrup',
                            'price' => 80,
                            'cost_price' => 35,
                            'is_vegetarian' => true,
                            'is_vegan' => false,
                            'preparation_time' => 3,
                            'calories' => 180,
                            'image' => 'dessert/gulab-jamun.jpg',
                        ],
                    ],
                ],
                // Noodles Items
                [
                    'category' => 'noodles',
                    'items' => [
                        [
                            'name' => 'Chicken Chowmein',
                            'name_np' => 'चिकन चाउमिन',
                            'slug' => 'chicken-chowmein',
                            'description' => 'Stir-fried noodles with chicken and vegetables',
                            'price' => 160,
                            'cost_price' => 70,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 15,
                            'calories' => 520,
                            'image' => 'noodles/chicken-chowmein.jpg',
                        ],
                        [
                            'name' => 'Veg Chowmein',
                            'name_np' => 'भेजी चाउमिन',
                            'slug' => 'veg-chowmein',
                            'description' => 'Stir-fried noodles with mixed vegetables',
                            'price' => 140,
                            'cost_price' => 60,
                            'is_vegetarian' => true,
                            'is_vegan' => true,
                            'preparation_time' => 15,
                            'calories' => 420,
                            'image' => 'noodles/veg-chowmein.jpg',
                        ],
                        [
                            'name' => 'Thukpa',
                            'name_np' => 'थुक्पा',
                            'slug' => 'thukpa',
                            'description' => 'Tibetan noodle soup with vegetables and meat',
                            'price' => 180,
                            'cost_price' => 80,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 18,
                            'calories' => 380,
                            'image' => 'noodles/thukpa.jpg',
                        ],
                    ],
                ],
                // Rice Items
                [
                    'category' => 'rice',
                    'items' => [
                        [
                            'name' => 'Chicken Biryani',
                            'name_np' => 'चिकन बिरयानी',
                            'slug' => 'chicken-biryani',
                            'description' => 'Aromatic rice with spiced chicken',
                            'price' => 320,
                            'cost_price' => 150,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 25,
                            'calories' => 680,
                            'image' => 'rice/chicken-biryani.jpg',
                        ],
                        [
                            'name' => 'Veg Fried Rice',
                            'name_np' => 'भेजी फ्राइड राइस',
                            'slug' => 'veg-fried-rice',
                            'description' => 'Stir-fried rice with mixed vegetables',
                            'price' => 180,
                            'cost_price' => 80,
                            'is_vegetarian' => true,
                            'is_vegan' => true,
                            'preparation_time' => 15,
                            'calories' => 450,
                            'image' => 'rice/veg-fried-rice.jpg',
                        ],
                        [
                            'name' => 'Egg Fried Rice',
                            'name_np' => 'अण्डा फ्राइड राइस',
                            'slug' => 'egg-fried-rice',
                            'description' => 'Fried rice with scrambled eggs and vegetables',
                            'price' => 200,
                            'cost_price' => 90,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 15,
                            'calories' => 520,
                            'image' => 'rice/egg-fried-rice.jpg',
                        ],
                    ],
                ],
                // Soup Items
                [
                    'category' => 'soup',
                    'items' => [
                        [
                            'name' => 'Chicken Soup',
                            'name_np' => 'चिकन सूप',
                            'slug' => 'chicken-soup',
                            'description' => 'Clear chicken soup with vegetables',
                            'price' => 120,
                            'cost_price' => 50,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 12,
                            'calories' => 150,
                            'image' => 'soup/chicken.jpg',
                        ],
                        [
                            'name' => 'Tomato Soup',
                            'name_np' => 'टमाटर सूप',
                            'slug' => 'tomato-soup',
                            'description' => 'Creamy tomato soup with basil',
                            'price' => 100,
                            'cost_price' => 40,
                            'is_vegetarian' => true,
                            'is_vegan' => true,
                            'preparation_time' => 10,
                            'calories' => 120,
                            'image' => 'soup/tomato.jpg',
                        ],
                        [
                            'name' => 'Sweet Corn Soup',
                            'name_np' => 'स्वीट कर्न सूप',
                            'slug' => 'sweet-corn-soup',
                            'description' => 'Creamy sweet corn soup',
                            'price' => 110,
                            'cost_price' => 45,
                            'is_vegetarian' => true,
                            'is_vegan' => true,
                            'preparation_time' => 10,
                            'calories' => 130,
                            'image' => 'soup/sweet-corn.jpg',
                        ],
                    ],
                ],
                // Sandwich Items
                [
                    'category' => 'sandwich',
                    'items' => [
                        [
                            'name' => 'Club Sandwich',
                            'name_np' => 'क्लब स्यान्डविच',
                            'slug' => 'club-sandwich',
                            'description' => 'Triple-layer sandwich with chicken, bacon, and vegetables',
                            'price' => 280,
                            'cost_price' => 130,
                            'is_vegetarian' => false,
                            'is_vegan' => false,
                            'preparation_time' => 12,
                            'calories' => 580,
                            'image' => 'sandwich/club.jpg',
                        ],
                        [
                            'name' => 'Veg Sandwich',
                            'name_np' => 'भेजी स्यान्डविच',
                            'slug' => 'veg-sandwich',
                            'description' => 'Grilled vegetable sandwich with cheese',
                            'price' => 180,
                            'cost_price' => 80,
                            'is_vegetarian' => true,
                            'is_vegan' => false,
                            'preparation_time' => 8,
                            'calories' => 380,
                            'image' => 'sandwich/veg.jpg',
                        ],
                    ],
                ],
            ];
            
            $itemCount = 0;
            foreach ($menuItems as $categoryData) {
                $categorySlug = $categoryData['category'];
                $category = $createdCategories[$categorySlug];
                
                foreach ($categoryData['items'] as $index => $itemData) {
                    MenuItem::firstOrCreate(
                        [
                            'tenant_id' => $tenant->id,
                            'restaurant_id' => $restaurant->id,
                            'slug' => $itemData['slug'],
                        ],
                        array_merge($itemData, [
                            'uuid' => Str::uuid(),
                            'tenant_id' => $tenant->id,
                            'restaurant_id' => $restaurant->id,
                            'branch_id' => $branch ? $branch->id : null,
                            'category_id' => $category->id,
                            'tax_rate' => 13.00,
                            'discount_percentage' => 0,
                            'kitchen_section' => 'main',
                            'is_spicy' => false,
                            'is_available' => true,
                            'is_featured' => $index === 0, // First item in each category is featured
                            'sort_order' => $index + 1,
                            'is_active' => true,
                        ])
                    );
                    $itemCount++;
                }
            }
            
            DB::commit();
            
            $this->command->info("Successfully seeded {$itemCount} menu items across " . count($createdCategories) . " categories.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding menu: ' . $e->getMessage());
            throw $e;
        }
    }
}
