<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Modules
        $modules = [
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'icon' => 'fas fa-home',
                'description' => 'Main dashboard and overview',
                'sort_order' => 1,
            ],
            [
                'name' => 'Restaurant Management',
                'slug' => 'restaurant-management',
                'icon' => 'fas fa-store',
                'description' => 'Restaurant profile and settings',
                'sort_order' => 2,
            ],
            [
                'name' => 'Menu Management',
                'slug' => 'menu-management',
                'icon' => 'fas fa-utensils',
                'description' => 'Food menu and categories',
                'sort_order' => 3,
            ],
            [
                'name' => 'Orders',
                'slug' => 'orders',
                'icon' => 'fas fa-shopping-cart',
                'description' => 'Order management',
                'sort_order' => 4,
            ],
            [
                'name' => 'POS',
                'slug' => 'pos',
                'icon' => 'fas fa-cash-register',
                'description' => 'Point of sale system',
                'sort_order' => 5,
            ],
            [
                'name' => 'Kitchen',
                'slug' => 'kitchen',
                'icon' => 'fas fa-fire',
                'description' => 'Kitchen display system',
                'sort_order' => 6,
            ],
            [
                'name' => 'Reservation',
                'slug' => 'reservation',
                'icon' => 'fas fa-calendar-alt',
                'description' => 'Table reservations',
                'sort_order' => 7,
            ],
            [
                'name' => 'Inventory',
                'slug' => 'inventory',
                'icon' => 'fas fa-boxes',
                'description' => 'Stock and inventory management',
                'sort_order' => 8,
            ],
            [
                'name' => 'Purchase',
                'slug' => 'purchase',
                'icon' => 'fas fa-shopping-basket',
                'description' => 'Purchase management',
                'sort_order' => 9,
            ],
            [
                'name' => 'Customers',
                'slug' => 'customers',
                'icon' => 'fas fa-users',
                'description' => 'Customer management',
                'sort_order' => 10,
            ],
            [
                'name' => 'Delivery',
                'slug' => 'delivery',
                'icon' => 'fas fa-truck',
                'description' => 'Delivery management',
                'sort_order' => 11,
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
                'icon' => 'fas fa-chart-line',
                'description' => 'Sales and revenue',
                'sort_order' => 12,
            ],
            [
                'name' => 'Accounting',
                'slug' => 'accounting',
                'icon' => 'fas fa-calculator',
                'description' => 'Financial accounting',
                'sort_order' => 13,
            ],
            [
                'name' => 'HRM',
                'slug' => 'hrm',
                'icon' => 'fas fa-user-tie',
                'description' => 'Human resource management',
                'sort_order' => 14,
            ],
            [
                'name' => 'CRM & Marketing',
                'slug' => 'crm-marketing',
                'icon' => 'fas fa-bullhorn',
                'description' => 'Customer relationship and marketing',
                'sort_order' => 15,
            ],
            [
                'name' => 'Website & QR Menu',
                'slug' => 'website-qr-menu',
                'icon' => 'fas fa-globe',
                'description' => 'Website and QR menu management',
                'sort_order' => 16,
            ],
            [
                'name' => 'Reports',
                'slug' => 'reports',
                'icon' => 'fas fa-chart-bar',
                'description' => 'Business reports',
                'sort_order' => 17,
            ],
            [
                'name' => 'Mobile App',
                'slug' => 'mobile-app',
                'icon' => 'fas fa-mobile-alt',
                'description' => 'Mobile app management',
                'sort_order' => 18,
            ],
            [
                'name' => 'Settings',
                'slug' => 'settings',
                'icon' => 'fas fa-cog',
                'description' => 'System settings',
                'sort_order' => 19,
            ],
            [
                'name' => 'Support',
                'slug' => 'support',
                'icon' => 'fas fa-life-ring',
                'description' => 'Help and support',
                'sort_order' => 20,
            ],
        ];

        foreach ($modules as $module) {
            \App\Models\Module::firstOrCreate(
                ['slug' => $module['slug']],
                $module
            );
        }

        // Create Menus and Submenus for each module
        $this->createDashboardMenus();
        $this->createRestaurantManagementMenus();
        $this->createMenuManagementMenus();
        $this->createOrderMenus();
        $this->createPOSMenus();
        $this->createKitchenMenus();
        $this->createReservationMenus();
        $this->createInventoryMenus();
        $this->createPurchaseMenus();
        $this->createCustomerMenus();
        $this->createDeliveryMenus();
        $this->createSalesMenus();
        $this->createAccountingMenus();
        $this->createHRMMenus();
        $this->createCRMMarketingMenus();
        $this->createWebsiteQRMenuMenus();
        $this->createReportMenus();
        $this->createMobileAppMenus();
        $this->createSettingsMenus();
        $this->createSupportMenus();

        $this->command->info('Restaurant menu structure seeded successfully!');
    }

    private function createDashboardMenus()
    {
        $module = \App\Models\Module::where('slug', 'dashboard')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'dashboard'],
            [
                'name' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'dashboard',
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Business Overview', 'slug' => 'business-overview', 'route' => 'dashboard.business-overview', 'sort_order' => 1],
            ['name' => 'Today\'s Summary', 'slug' => 'today-summary', 'route' => 'dashboard.today-summary', 'sort_order' => 2],
            ['name' => 'Analytics', 'slug' => 'analytics', 'route' => 'dashboard.analytics', 'sort_order' => 3],
            ['name' => 'Notifications', 'slug' => 'notifications', 'route' => 'dashboard.notifications', 'sort_order' => 4],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createRestaurantManagementMenus()
    {
        $module = \App\Models\Module::where('slug', 'restaurant-management')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'restaurant-management'],
            [
                'name' => 'Restaurant Management',
                'icon' => 'fas fa-building',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Restaurant Profile', 'slug' => 'restaurant-profile', 'route' => 'restaurant.profile', 'sort_order' => 1],
            ['name' => 'Branches', 'slug' => 'branches', 'route' => 'restaurant.branches', 'sort_order' => 2],
            ['name' => 'Floors', 'slug' => 'floors', 'route' => 'restaurant.floors', 'sort_order' => 3],
            ['name' => 'Dining Areas', 'slug' => 'dining-areas', 'route' => 'restaurant.dining-areas', 'sort_order' => 4],
            ['name' => 'Tables', 'slug' => 'tables', 'route' => 'restaurant.tables', 'sort_order' => 5],
            ['name' => 'Table Categories', 'slug' => 'table-categories', 'route' => 'restaurant.table-categories', 'sort_order' => 6],
            ['name' => 'QR Code Management', 'slug' => 'qr-code-management', 'route' => 'restaurant.qr-codes', 'sort_order' => 7],
            ['name' => 'Reservation Settings', 'slug' => 'reservation-settings', 'route' => 'restaurant.reservation-settings', 'sort_order' => 8],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createMenuManagementMenus()
    {
        $module = \App\Models\Module::where('slug', 'menu-management')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'menu-management'],
            [
                'name' => 'Menu Management',
                'icon' => 'fas fa-utensils',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Food Categories', 'slug' => 'food-categories', 'route' => 'menu.categories', 'sort_order' => 1],
            ['name' => 'Sub Categories', 'slug' => 'sub-categories', 'route' => 'menu.sub-categories', 'sort_order' => 2],
            ['name' => 'Menu Items', 'slug' => 'menu-items', 'route' => 'menu.items', 'sort_order' => 3],
            ['name' => 'Variants', 'slug' => 'variants', 'route' => 'menu.variants', 'sort_order' => 4],
            ['name' => 'Add-ons', 'slug' => 'add-ons', 'route' => 'menu.add-ons', 'sort_order' => 5],
            ['name' => 'Combo Meals', 'slug' => 'combo-meals', 'route' => 'menu.combo-meals', 'sort_order' => 6],
            ['name' => 'Happy Hour Menu', 'slug' => 'happy-hour-menu', 'route' => 'menu.happy-hour', 'sort_order' => 7],
            ['name' => 'Seasonal Menu', 'slug' => 'seasonal-menu', 'route' => 'menu.seasonal', 'sort_order' => 8],
            ['name' => 'Recipes', 'slug' => 'recipes', 'route' => 'menu.recipes', 'sort_order' => 9],
            ['name' => 'Ingredients', 'slug' => 'ingredients', 'route' => 'menu.ingredients', 'sort_order' => 10],
            ['name' => 'Units', 'slug' => 'units', 'route' => 'menu.units', 'sort_order' => 11],
            ['name' => 'Allergens', 'slug' => 'allergens', 'route' => 'menu.allergens', 'sort_order' => 12],
            ['name' => 'Kitchen Sections', 'slug' => 'kitchen-sections', 'route' => 'menu.kitchen-sections', 'sort_order' => 13],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createOrderMenus()
    {
        $module = \App\Models\Module::where('slug', 'orders')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'orders'],
            [
                'name' => 'Orders',
                'icon' => 'fas fa-receipt',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'New Orders', 'slug' => 'orders-new-orders', 'route' => 'orders.new', 'sort_order' => 1],
            ['name' => 'Dine-In Orders', 'slug' => 'orders-dine-in-orders', 'route' => 'orders.dine-in', 'sort_order' => 2],
            ['name' => 'Takeaway Orders', 'slug' => 'orders-takeaway-orders', 'route' => 'orders.takeaway', 'sort_order' => 3],
            ['name' => 'Delivery Orders', 'slug' => 'orders-delivery-orders', 'route' => 'orders.delivery', 'sort_order' => 4],
            ['name' => 'Online Orders', 'slug' => 'orders-online-orders', 'route' => 'orders.online', 'sort_order' => 5],
            ['name' => 'Scheduled Orders', 'slug' => 'orders-scheduled-orders', 'route' => 'orders.scheduled', 'sort_order' => 6],
            ['name' => 'Order History', 'slug' => 'orders-order-history', 'route' => 'orders.history', 'sort_order' => 7],
            ['name' => 'Cancelled Orders', 'slug' => 'orders-cancelled-orders', 'route' => 'orders.cancelled', 'sort_order' => 8],
            ['name' => 'Returned Orders', 'slug' => 'orders-returned-orders', 'route' => 'orders.returned', 'sort_order' => 9],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createPOSMenus()
    {
        $module = \App\Models\Module::where('slug', 'pos')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'pos'],
            [
                'name' => 'POS',
                'icon' => 'fas fa-cash-register',
                'route' => 'pos.index',
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'New Billing', 'slug' => 'pos-new-billing', 'route' => 'pos.new-billing', 'sort_order' => 1],
            ['name' => 'Quick Billing', 'slug' => 'pos-quick-billing', 'route' => 'pos.quick-billing', 'sort_order' => 2],
            ['name' => 'Hold Bills', 'slug' => 'pos-hold-bills', 'route' => 'pos.hold-bills', 'sort_order' => 3],
            ['name' => 'Resume Bills', 'slug' => 'pos-resume-bills', 'route' => 'pos.resume-bills', 'sort_order' => 4],
            ['name' => 'Split Bills', 'slug' => 'pos-split-bills', 'route' => 'pos.split-bills', 'sort_order' => 5],
            ['name' => 'Merge Bills', 'slug' => 'pos-merge-bills', 'route' => 'pos.merge-bills', 'sort_order' => 6],
            ['name' => 'Reprint Bill', 'slug' => 'pos-reprint-bill', 'route' => 'pos.reprint-bill', 'sort_order' => 7],
            ['name' => 'Refund Bills', 'slug' => 'pos-refund-bills', 'route' => 'pos.refund-bills', 'sort_order' => 8],
            ['name' => 'Void Bills', 'slug' => 'pos-void-bills', 'route' => 'pos.void-bills', 'sort_order' => 9],
            ['name' => 'Daily Closing', 'slug' => 'pos-daily-closing', 'route' => 'pos.daily-closing', 'sort_order' => 10],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createKitchenMenus()
    {
        $module = \App\Models\Module::where('slug', 'kitchen')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'kitchen'],
            [
                'name' => 'Kitchen',
                'icon' => 'fas fa-fire-alt',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Kitchen Dashboard', 'slug' => 'kitchen-kitchen-dashboard', 'route' => 'kitchen.dashboard', 'sort_order' => 1],
            ['name' => 'Pending Orders', 'slug' => 'kitchen-pending-orders', 'route' => 'kitchen.pending', 'sort_order' => 2],
            ['name' => 'Preparing Orders', 'slug' => 'kitchen-preparing-orders', 'route' => 'kitchen.preparing', 'sort_order' => 3],
            ['name' => 'Ready Orders', 'slug' => 'kitchen-ready-orders', 'route' => 'kitchen.ready', 'sort_order' => 4],
            ['name' => 'Served Orders', 'slug' => 'kitchen-served-orders', 'route' => 'kitchen.served', 'sort_order' => 5],
            ['name' => 'Kitchen Display (KDS)', 'slug' => 'kitchen-kds', 'route' => 'kitchen.kds', 'sort_order' => 6],
            ['name' => 'Kitchen Tickets', 'slug' => 'kitchen-kitchen-tickets', 'route' => 'kitchen.tickets', 'sort_order' => 7],
            ['name' => 'Chef Notes', 'slug' => 'kitchen-chef-notes', 'route' => 'kitchen.chef-notes', 'sort_order' => 8],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createReservationMenus()
    {
        $module = \App\Models\Module::where('slug', 'reservation')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'reservation'],
            [
                'name' => 'Reservation',
                'icon' => 'fas fa-calendar-check',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Table Reservation', 'slug' => 'reservation-table-reservation', 'route' => 'reservation.table', 'sort_order' => 1],
            ['name' => 'Party Booking', 'slug' => 'reservation-party-booking', 'route' => 'reservation.party', 'sort_order' => 2],
            ['name' => 'Event Booking', 'slug' => 'reservation-event-booking', 'route' => 'reservation.event', 'sort_order' => 3],
            ['name' => 'Waiting List', 'slug' => 'reservation-waiting-list', 'route' => 'reservation.waiting', 'sort_order' => 4],
            ['name' => 'Reservation Calendar', 'slug' => 'reservation-reservation-calendar', 'route' => 'reservation.calendar', 'sort_order' => 5],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createInventoryMenus()
    {
        $module = \App\Models\Module::where('slug', 'inventory')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'inventory'],
            [
                'name' => 'Inventory',
                'icon' => 'fas fa-warehouse',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Dashboard', 'slug' => 'inventory-dashboard', 'route' => 'inventory.dashboard', 'sort_order' => 1],
            ['name' => 'Raw Materials', 'slug' => 'inventory-raw-materials', 'route' => 'inventory.raw-materials', 'sort_order' => 2],
            ['name' => 'Products', 'slug' => 'inventory-products', 'route' => 'inventory.products', 'sort_order' => 3],
            ['name' => 'Categories', 'slug' => 'inventory-categories', 'route' => 'inventory.categories', 'sort_order' => 4],
            ['name' => 'Units', 'slug' => 'inventory-units', 'route' => 'inventory.units', 'sort_order' => 5],
            ['name' => 'Warehouses', 'slug' => 'inventory-warehouses', 'route' => 'inventory.warehouses', 'sort_order' => 6],
            ['name' => 'Stock List', 'slug' => 'inventory-stock-list', 'route' => 'inventory.stock', 'sort_order' => 7],
            ['name' => 'Stock Adjustment', 'slug' => 'inventory-stock-adjustment', 'route' => 'inventory.adjustment', 'sort_order' => 8],
            ['name' => 'Stock Transfer', 'slug' => 'inventory-stock-transfer', 'route' => 'inventory.transfer', 'sort_order' => 9],
            ['name' => 'Wastage', 'slug' => 'inventory-wastage', 'route' => 'inventory.wastage', 'sort_order' => 10],
            ['name' => 'Expired Items', 'slug' => 'inventory-expired-items', 'route' => 'inventory.expired', 'sort_order' => 11],
            ['name' => 'Stock Ledger', 'slug' => 'inventory-stock-ledger', 'route' => 'inventory.ledger', 'sort_order' => 12],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createPurchaseMenus()
    {
        $module = \App\Models\Module::where('slug', 'purchase')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'purchase'],
            [
                'name' => 'Purchase',
                'icon' => 'fas fa-shopping-cart',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Suppliers', 'slug' => 'purchase-suppliers', 'route' => 'purchase.suppliers', 'sort_order' => 1],
            ['name' => 'Purchase Orders', 'slug' => 'purchase-purchase-orders', 'route' => 'purchase.orders', 'sort_order' => 2],
            ['name' => 'Purchase Receive', 'slug' => 'purchase-purchase-receive', 'route' => 'purchase.receive', 'sort_order' => 3],
            ['name' => 'Purchase Invoice', 'slug' => 'purchase-purchase-invoice', 'route' => 'purchase.invoice', 'sort_order' => 4],
            ['name' => 'Purchase Return', 'slug' => 'purchase-purchase-return', 'route' => 'purchase.return', 'sort_order' => 5],
            ['name' => 'Supplier Payments', 'slug' => 'purchase-supplier-payments', 'route' => 'purchase.payments', 'sort_order' => 6],
            ['name' => 'Due Payments', 'slug' => 'purchase-due-payments', 'route' => 'purchase.due', 'sort_order' => 7],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createCustomerMenus()
    {
        $module = \App\Models\Module::where('slug', 'customers')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'customers'],
            [
                'name' => 'Customers',
                'icon' => 'fas fa-user-friends',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Customer List', 'slug' => 'customers-customer-list', 'route' => 'customers.list', 'sort_order' => 1],
            ['name' => 'Membership', 'slug' => 'customers-membership', 'route' => 'customers.membership', 'sort_order' => 2],
            ['name' => 'Loyalty Points', 'slug' => 'customers-loyalty-points', 'route' => 'customers.loyalty', 'sort_order' => 3],
            ['name' => 'Wallet', 'slug' => 'customers-wallet', 'route' => 'customers.wallet', 'sort_order' => 4],
            ['name' => 'Feedback', 'slug' => 'customers-feedback', 'route' => 'customers.feedback', 'sort_order' => 5],
            ['name' => 'Reviews', 'slug' => 'customers-reviews', 'route' => 'customers.reviews', 'sort_order' => 6],
            ['name' => 'Customer Groups', 'slug' => 'customers-customer-groups', 'route' => 'customers.groups', 'sort_order' => 7],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createDeliveryMenus()
    {
        $module = \App\Models\Module::where('slug', 'delivery')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'delivery'],
            [
                'name' => 'Delivery',
                'icon' => 'fas fa-motorcycle',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Delivery Dashboard', 'slug' => 'delivery-delivery-dashboard', 'route' => 'delivery.dashboard', 'sort_order' => 1],
            ['name' => 'Delivery Orders', 'slug' => 'delivery-delivery-orders', 'route' => 'delivery.orders', 'sort_order' => 2],
            ['name' => 'Delivery Partners', 'slug' => 'delivery-delivery-partners', 'route' => 'delivery.partners', 'sort_order' => 3],
            ['name' => 'Delivery Charges', 'slug' => 'delivery-delivery-charges', 'route' => 'delivery.charges', 'sort_order' => 4],
            ['name' => 'Rider Management', 'slug' => 'delivery-rider-management', 'route' => 'delivery.riders', 'sort_order' => 5],
            ['name' => 'Delivery Reports', 'slug' => 'delivery-delivery-reports', 'route' => 'delivery.reports', 'sort_order' => 6],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createSalesMenus()
    {
        $module = \App\Models\Module::where('slug', 'sales')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'sales'],
            [
                'name' => 'Sales',
                'icon' => 'fas fa-chart-line',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Sales List', 'slug' => 'sales-sales-list', 'route' => 'sales.list', 'sort_order' => 1],
            ['name' => 'Sales Return', 'slug' => 'sales-sales-return', 'route' => 'sales.return', 'sort_order' => 2],
            ['name' => 'Discount Management', 'slug' => 'sales-discount-management', 'route' => 'sales.discounts', 'sort_order' => 3],
            ['name' => 'Coupons', 'slug' => 'sales-coupons', 'route' => 'sales.coupons', 'sort_order' => 4],
            ['name' => 'Gift Cards', 'slug' => 'sales-gift-cards', 'route' => 'sales.gift-cards', 'sort_order' => 5],
            ['name' => 'Offers', 'slug' => 'sales-offers', 'route' => 'sales.offers', 'sort_order' => 6],
            ['name' => 'Daily Sales', 'slug' => 'sales-daily-sales', 'route' => 'sales.daily', 'sort_order' => 7],
            ['name' => 'Monthly Sales', 'slug' => 'sales-monthly-sales', 'route' => 'sales.monthly', 'sort_order' => 8],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createAccountingMenus()
    {
        $module = \App\Models\Module::where('slug', 'accounting')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'accounting'],
            [
                'name' => 'Accounting',
                'icon' => 'fas fa-calculator',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Voucher', 'slug' => 'accounting-voucher', 'route' => 'accounting.voucher', 'sort_order' => 1],
            ['name' => 'Journal Voucher', 'slug' => 'accounting-journal-voucher', 'route' => 'accounting.journal', 'sort_order' => 2],
            ['name' => 'Payment Voucher', 'slug' => 'accounting-payment-voucher', 'route' => 'accounting.payment', 'sort_order' => 3],
            ['name' => 'Receipt Voucher', 'slug' => 'accounting-receipt-voucher', 'route' => 'accounting.receipt', 'sort_order' => 4],
            ['name' => 'Contra Voucher', 'slug' => 'accounting-contra-voucher', 'route' => 'accounting.contra', 'sort_order' => 5],
            ['name' => 'Accounts', 'slug' => 'accounting-accounts', 'route' => 'accounting.accounts', 'sort_order' => 6],
            ['name' => 'Chart of Accounts', 'slug' => 'accounting-chart-of-accounts', 'route' => 'accounting.chart', 'sort_order' => 7],
            ['name' => 'Ledger', 'slug' => 'accounting-ledger', 'route' => 'accounting.ledger', 'sort_order' => 8],
            ['name' => 'Cash Book', 'slug' => 'accounting-cash-book', 'route' => 'accounting.cash-book', 'sort_order' => 9],
            ['name' => 'Bank Book', 'slug' => 'accounting-bank-book', 'route' => 'accounting.bank-book', 'sort_order' => 10],
            ['name' => 'Petty Cash', 'slug' => 'accounting-petty-cash', 'route' => 'accounting.petty-cash', 'sort_order' => 11],
            ['name' => 'Transactions', 'slug' => 'accounting-transactions', 'route' => 'accounting.transactions', 'sort_order' => 12],
            ['name' => 'Income', 'slug' => 'accounting-income', 'route' => 'accounting.income', 'sort_order' => 13],
            ['name' => 'Expenses', 'slug' => 'accounting-expenses', 'route' => 'accounting.expenses', 'sort_order' => 14],
            ['name' => 'Bank Transactions', 'slug' => 'accounting-bank-transactions', 'route' => 'accounting.bank-transactions', 'sort_order' => 15],
            ['name' => 'Bank Reconciliation', 'slug' => 'accounting-bank-reconciliation', 'route' => 'accounting.reconciliation', 'sort_order' => 16],
            ['name' => 'Financial Reports', 'slug' => 'accounting-financial-reports', 'route' => 'accounting.financial-reports', 'sort_order' => 17],
            ['name' => 'Trial Balance', 'slug' => 'accounting-trial-balance', 'route' => 'accounting.trial-balance', 'sort_order' => 18],
            ['name' => 'Profit & Loss', 'slug' => 'accounting-profit-loss', 'route' => 'accounting.profit-loss', 'sort_order' => 19],
            ['name' => 'Balance Sheet', 'slug' => 'accounting-balance-sheet', 'route' => 'accounting.balance-sheet', 'sort_order' => 20],
            ['name' => 'VAT Report', 'slug' => 'accounting-vat-report', 'route' => 'accounting.vat-report', 'sort_order' => 21],
            ['name' => 'PAN Report', 'slug' => 'accounting-pan-report', 'route' => 'accounting.pan-report', 'sort_order' => 22],
            ['name' => 'Tax Report', 'slug' => 'accounting-tax-report', 'route' => 'accounting.tax-report', 'sort_order' => 23],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createHRMMenus()
    {
        $module = \App\Models\Module::where('slug', 'hrm')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'hrm'],
            [
                'name' => 'HRM',
                'icon' => 'fas fa-users-cog',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Employees', 'slug' => 'hrm-employees', 'route' => 'hrm.employees', 'sort_order' => 1],
            ['name' => 'Departments', 'slug' => 'hrm-departments', 'route' => 'hrm.departments', 'sort_order' => 2],
            ['name' => 'Designations', 'slug' => 'hrm-designations', 'route' => 'hrm.designations', 'sort_order' => 3],
            ['name' => 'Attendance', 'slug' => 'hrm-attendance', 'route' => 'hrm.attendance', 'sort_order' => 4],
            ['name' => 'Leave', 'slug' => 'hrm-leave', 'route' => 'hrm.leave', 'sort_order' => 5],
            ['name' => 'Shifts', 'slug' => 'hrm-shifts', 'route' => 'hrm.shifts', 'sort_order' => 6],
            ['name' => 'Payroll', 'slug' => 'hrm-payroll', 'route' => 'hrm.payroll', 'sort_order' => 7],
            ['name' => 'Salary', 'slug' => 'hrm-salary', 'route' => 'hrm.salary', 'sort_order' => 8],
            ['name' => 'Advance Salary', 'slug' => 'hrm-advance-salary', 'route' => 'hrm.advance-salary', 'sort_order' => 9],
            ['name' => 'Incentives', 'slug' => 'hrm-incentives', 'route' => 'hrm.incentives', 'sort_order' => 10],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createCRMMarketingMenus()
    {
        $module = \App\Models\Module::where('slug', 'crm-marketing')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'crm-marketing'],
            [
                'name' => 'CRM & Marketing',
                'icon' => 'fas fa-bullhorn',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Customer Groups', 'slug' => 'crm-customer-groups', 'route' => 'crm.groups', 'sort_order' => 1],
            ['name' => 'SMS Campaign', 'slug' => 'crm-sms-campaign', 'route' => 'crm.sms', 'sort_order' => 2],
            ['name' => 'Email Campaign', 'slug' => 'crm-email-campaign', 'route' => 'crm.email', 'sort_order' => 3],
            ['name' => 'Push Notification', 'slug' => 'crm-push-notification', 'route' => 'crm.push', 'sort_order' => 4],
            ['name' => 'Loyalty Program', 'slug' => 'crm-loyalty-program', 'route' => 'crm.loyalty', 'sort_order' => 5],
            ['name' => 'Referral Program', 'slug' => 'crm-referral-program', 'route' => 'crm.referral', 'sort_order' => 6],
            ['name' => 'Coupons', 'slug' => 'crm-coupons', 'route' => 'crm.coupons', 'sort_order' => 7],
            ['name' => 'Promotions', 'slug' => 'crm-promotions', 'route' => 'crm.promotions', 'sort_order' => 8],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createWebsiteQRMenuMenus()
    {
        $module = \App\Models\Module::where('slug', 'website-qr-menu')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'website-qr-menu'],
            [
                'name' => 'Website & QR Menu',
                'icon' => 'fas fa-qrcode',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'QR Menu', 'slug' => 'website-qr-menu', 'route' => 'website.qr-menu', 'sort_order' => 1],
            ['name' => 'Website Settings', 'slug' => 'website-website-settings', 'route' => 'website.settings', 'sort_order' => 2],
            ['name' => 'Homepage', 'slug' => 'website-homepage', 'route' => 'website.homepage', 'sort_order' => 3],
            ['name' => 'About Us', 'slug' => 'website-about-us', 'route' => 'website.about', 'sort_order' => 4],
            ['name' => 'Gallery', 'slug' => 'website-gallery', 'route' => 'website.gallery', 'sort_order' => 5],
            ['name' => 'Blogs', 'slug' => 'website-blogs', 'route' => 'website.blogs', 'sort_order' => 6],
            ['name' => 'Offers', 'slug' => 'website-offers', 'route' => 'website.offers', 'sort_order' => 7],
            ['name' => 'Contact', 'slug' => 'website-contact', 'route' => 'website.contact', 'sort_order' => 8],
            ['name' => 'Customer Reviews', 'slug' => 'website-customer-reviews', 'route' => 'website.reviews', 'sort_order' => 9],
            ['name' => 'SEO Settings', 'slug' => 'website-seo-settings', 'route' => 'website.seo', 'sort_order' => 10],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createReportMenus()
    {
        $module = \App\Models\Module::where('slug', 'reports')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'reports'],
            [
                'name' => 'Reports',
                'icon' => 'fas fa-chart-pie',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Sales Reports', 'slug' => 'reports-sales-reports', 'route' => 'reports.sales', 'sort_order' => 1],
            ['name' => 'Daily Sales', 'slug' => 'reports-daily-sales', 'route' => 'reports.daily-sales', 'sort_order' => 2],
            ['name' => 'Monthly Sales', 'slug' => 'reports-monthly-sales', 'route' => 'reports.monthly-sales', 'sort_order' => 3],
            ['name' => 'Yearly Sales', 'slug' => 'reports-yearly-sales', 'route' => 'reports.yearly-sales', 'sort_order' => 4],
            ['name' => 'Item Wise Sales', 'slug' => 'reports-item-wise-sales', 'route' => 'reports.item-sales', 'sort_order' => 5],
            ['name' => 'Category Wise Sales', 'slug' => 'reports-category-wise-sales', 'route' => 'reports.category-sales', 'sort_order' => 6],
            ['name' => 'Purchase Reports', 'slug' => 'reports-purchase-reports', 'route' => 'reports.purchase', 'sort_order' => 7],
            ['name' => 'Purchase Summary', 'slug' => 'reports-purchase-summary', 'route' => 'reports.purchase-summary', 'sort_order' => 8],
            ['name' => 'Supplier Wise', 'slug' => 'reports-supplier-wise', 'route' => 'reports.supplier', 'sort_order' => 9],
            ['name' => 'Purchase Return', 'slug' => 'reports-purchase-return', 'route' => 'reports.purchase-return', 'sort_order' => 10],
            ['name' => 'Inventory Reports', 'slug' => 'reports-inventory-reports', 'route' => 'reports.inventory', 'sort_order' => 11],
            ['name' => 'Stock Report', 'slug' => 'reports-stock-report', 'route' => 'reports.stock', 'sort_order' => 12],
            ['name' => 'Stock Ledger', 'slug' => 'reports-stock-ledger', 'route' => 'reports.stock-ledger', 'sort_order' => 13],
            ['name' => 'Low Stock', 'slug' => 'reports-low-stock', 'route' => 'reports.low-stock', 'sort_order' => 14],
            ['name' => 'Expired Items', 'slug' => 'reports-expired-items', 'route' => 'reports.expired', 'sort_order' => 15],
            ['name' => 'Wastage Report', 'slug' => 'reports-wastage-report', 'route' => 'reports.wastage', 'sort_order' => 16],
            ['name' => 'Financial Reports', 'slug' => 'reports-financial-reports', 'route' => 'reports.financial', 'sort_order' => 17],
            ['name' => 'Income Report', 'slug' => 'reports-income-report', 'route' => 'reports.income', 'sort_order' => 18],
            ['name' => 'Expense Report', 'slug' => 'reports-expense-report', 'route' => 'reports.expense', 'sort_order' => 19],
            ['name' => 'Cash Flow', 'slug' => 'reports-cash-flow', 'route' => 'reports.cash-flow', 'sort_order' => 20],
            ['name' => 'Profit Report', 'slug' => 'reports-profit-report', 'route' => 'reports.profit', 'sort_order' => 21],
            ['name' => 'VAT Report', 'slug' => 'reports-vat', 'route' => 'reports.vat', 'sort_order' => 22],
            ['name' => 'Other Reports', 'slug' => 'reports-other-reports', 'route' => 'reports.other', 'sort_order' => 23],
            ['name' => 'Customer Report', 'slug' => 'reports-customer-report', 'route' => 'reports.customer', 'sort_order' => 24],
            ['name' => 'Employee Report', 'slug' => 'reports-employee-report', 'route' => 'reports.employee', 'sort_order' => 25],
            ['name' => 'Waiter Report', 'slug' => 'reports-waiter-report', 'route' => 'reports.waiter', 'sort_order' => 26],
            ['name' => 'Kitchen Report', 'slug' => 'reports-kitchen-report', 'route' => 'reports.kitchen', 'sort_order' => 27],
            ['name' => 'Delivery Report', 'slug' => 'reports-delivery-report', 'route' => 'reports.delivery', 'sort_order' => 28],
            ['name' => 'Reservation Report', 'slug' => 'reports-reservation-report', 'route' => 'reports.reservation', 'sort_order' => 29],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createMobileAppMenus()
    {
        $module = \App\Models\Module::where('slug', 'mobile-app')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'mobile-app'],
            [
                'name' => 'Mobile App',
                'icon' => 'fas fa-mobile-alt',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Mobile Orders', 'slug' => 'mobile-mobile-orders', 'route' => 'mobile.orders', 'sort_order' => 1],
            ['name' => 'App Settings', 'slug' => 'mobile-app-settings', 'route' => 'mobile.settings', 'sort_order' => 2],
            ['name' => 'QR Scan History', 'slug' => 'mobile-qr-scan-history', 'route' => 'mobile.qr-history', 'sort_order' => 3],
            ['name' => 'Push Notifications', 'slug' => 'mobile-push-notifications', 'route' => 'mobile.push', 'sort_order' => 4],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createSettingsMenus()
    {
        $module = \App\Models\Module::where('slug', 'settings')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'settings'],
            [
                'name' => 'Settings',
                'icon' => 'fas fa-cogs',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Restaurant Settings', 'slug' => 'settings-restaurant-settings', 'route' => 'settings.restaurant', 'sort_order' => 1],
            ['name' => 'General Settings', 'slug' => 'settings-general-settings', 'route' => 'settings.general', 'sort_order' => 2],
            ['name' => 'Branch Settings', 'slug' => 'settings-branch-settings', 'route' => 'settings.branch', 'sort_order' => 3],
            ['name' => 'Tax & VAT', 'slug' => 'settings-tax-vat', 'route' => 'settings.tax', 'sort_order' => 4],
            ['name' => 'Invoice Settings', 'slug' => 'settings-invoice-settings', 'route' => 'settings.invoice', 'sort_order' => 5],
            ['name' => 'Printer Settings', 'slug' => 'settings-printer-settings', 'route' => 'settings.printer', 'sort_order' => 6],
            ['name' => 'Payment Gateway', 'slug' => 'settings-payment-gateway', 'route' => 'settings.payment-gateway', 'sort_order' => 7],
            ['name' => 'Nepali Date Settings', 'slug' => 'settings-nepali-date', 'route' => 'settings.nepali-date', 'sort_order' => 8],
            ['name' => 'Language Settings', 'slug' => 'settings-language-settings', 'route' => 'settings.language', 'sort_order' => 9],
            ['name' => 'User Management', 'slug' => 'settings-user-management', 'route' => 'settings.users', 'sort_order' => 10],
            ['name' => 'Users', 'slug' => 'settings-users', 'route' => 'settings.users-list', 'sort_order' => 11],
            ['name' => 'Roles', 'slug' => 'settings-roles', 'route' => 'settings.roles', 'sort_order' => 12],
            ['name' => 'Permissions', 'slug' => 'settings-permissions', 'route' => 'settings.permissions', 'sort_order' => 13],
            ['name' => 'System', 'slug' => 'settings-system', 'route' => 'settings.system', 'sort_order' => 14],
            ['name' => 'Backup', 'slug' => 'settings-backup', 'route' => 'settings.backup', 'sort_order' => 15],
            ['name' => 'Restore', 'slug' => 'settings-restore', 'route' => 'settings.restore', 'sort_order' => 16],
            ['name' => 'Activity Logs', 'slug' => 'settings-activity-logs', 'route' => 'settings.logs', 'sort_order' => 17],
            ['name' => 'Login History', 'slug' => 'settings-login-history', 'route' => 'settings.login-history', 'sort_order' => 18],
            ['name' => 'API Keys', 'slug' => 'settings-api-keys', 'route' => 'settings.api-keys', 'sort_order' => 19],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }

    private function createSupportMenus()
    {
        $module = \App\Models\Module::where('slug', 'support')->first();
        
        $menu = $module->menus()->firstOrCreate(
            ['slug' => 'support'],
            [
                'name' => 'Support',
                'icon' => 'fas fa-headset',
                'route' => null,
                'sort_order' => 1,
            ]
        );

        $subMenus = [
            ['name' => 'Help Center', 'slug' => 'support-help-center', 'route' => 'support.help', 'sort_order' => 1],
            ['name' => 'Support Tickets', 'slug' => 'support-support-tickets', 'route' => 'support.tickets', 'sort_order' => 2],
            ['name' => 'Documentation', 'slug' => 'support-documentation', 'route' => 'support.docs', 'sort_order' => 3],
            ['name' => 'Contact Admin', 'slug' => 'support-contact-admin', 'route' => 'support.contact', 'sort_order' => 4],
        ];

        foreach ($subMenus as $subMenu) {
            $menu->subMenus()->firstOrCreate(
                ['slug' => $subMenu['slug']],
                $subMenu
            );
        }
    }
}
