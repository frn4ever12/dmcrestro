<?php

namespace App\Services;

use App\Models\Module;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    /**
     * Get enabled modules for the current tenant's subscription plan
     */
    public function getEnabledModules()
    {
        $tenantId = session('tenant_id');
        
        if (!$tenantId) {
            return collect([]);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        
        if (!$tenant || !$tenant->subscriptionPlan) {
            return collect([]);
        }

        $plan = $tenant->subscriptionPlan;
        
        // Get enabled modules from subscription plan
        $enabledModuleIds = $plan->enabled_modules ?? [];
        
        // Get all modules and check if they're enabled in the subscription plan
        $modules = Module::active()->with(['menus' => function($query) {
            $query->active()->with(['subMenus' => function($query) {
                $query->active();
            }]);
        }])->get();

        // Filter modules based on subscription plan enabled_modules array
        return $modules->filter(function($module) use ($plan, $enabledModuleIds) {
            // Core modules are always enabled
            $coreModules = ['dashboard', 'settings', 'support'];
            
            if (in_array($module->slug, $coreModules)) {
                return true;
            }

            // Check if module is in enabled_modules array
            if (in_array($module->id, $enabledModuleIds)) {
                // Filter menus and submenus based on enabled_modules
                $module->menus = $module->menus->filter(function($menu) use ($enabledModuleIds) {
                    if (in_array($menu->id, $enabledModuleIds)) {
                        // Filter submenus
                        $menu->subMenus = $menu->subMenus->filter(function($subMenu) use ($enabledModuleIds) {
                            return in_array($subMenu->id, $enabledModuleIds);
                        });
                        return true;
                    }
                    // If menu is not in enabled_modules but module is, still show the menu
                    return true;
                });
                return true;
            }

            return false;
        })->values();
    }

    /**
     * Check if a module is enabled in the subscription plan
     */
    private function isModuleEnabled($module, $plan)
    {
        // Core modules are always enabled
        $coreModules = ['dashboard', 'settings', 'support'];
        
        if (in_array($module->slug, $coreModules)) {
            return true;
        }

        // Check if module is in enabled_modules array
        $enabledModuleIds = $plan->enabled_modules ?? [];
        return in_array($module->id, $enabledModuleIds);
    }

    /**
     * Get menu structure as array for rendering
     */
    public function getMenuStructure()
    {
        $modules = $this->getEnabledModules();
        
        return $modules->map(function($module) {
            return [
                'id' => $module->id,
                'name' => $module->name,
                'slug' => $module->slug,
                'icon' => $module->icon,
                'menus' => $module->menus->map(function($menu) {
                    return [
                        'id' => $menu->id,
                        'name' => $menu->name,
                        'slug' => $menu->slug,
                        'icon' => $menu->icon,
                        'route' => $menu->route,
                        'sub_menus' => $menu->subMenus->map(function($subMenu) {
                            return [
                                'id' => $subMenu->id,
                                'name' => $subMenu->name,
                                'slug' => $subMenu->slug,
                                'icon' => $subMenu->icon,
                                'route' => $subMenu->route,
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
            ];
        })->toArray();
    }
}
