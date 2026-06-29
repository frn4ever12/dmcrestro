<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking for duplicate submenus within the same menu...\n";
$menus = \App\Models\Menu::with('subMenus')->get();
foreach($menus as $menu) {
    $subMenuNames = [];
    foreach($menu->subMenus as $subMenu) {
        $subMenuNames[] = $subMenu->name;
    }
    $counts = array_count_values($subMenuNames);
    foreach($counts as $name => $count) {
        if($count > 1) {
            echo "Menu '" . $menu->name . "' has duplicate submenu: " . $name . " (" . $count . " times)\n";
        }
    }
}

echo "\nTotal Menus: " . \App\Models\Menu::count() . "\n";
echo "Total SubMenus: " . \App\Models\SubMenu::count() . "\n";
echo "Total Modules: " . \App\Models\Module::count() . "\n";
