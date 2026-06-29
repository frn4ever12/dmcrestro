<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->get();
        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscription-plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans,slug',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'trial_days' => 'required|integer|min:0',
            'max_restaurants' => 'required|integer|min:1',
            'max_branches' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1',
            'storage_limit_mb' => 'required|integer|min:1',
            'pos_module' => 'boolean',
            'qr_menu_module' => 'boolean',
            'inventory_module' => 'boolean',
            'accounting_module' => 'boolean',
            'hrm_module' => 'boolean',
            'crm_module' => 'boolean',
            'kitchen_display_module' => 'boolean',
            'online_ordering_module' => 'boolean',
            'delivery_module' => 'boolean',
            'reservation_module' => 'boolean',
            'marketing_module' => 'boolean',
            'mobile_app_module' => 'boolean',
            'api_access' => 'boolean',
            'white_label' => 'boolean',
            'custom_domain' => 'boolean',
            'priority_support' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Subscription plan created successfully!');
    }

    public function edit($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        return view('admin.subscription-plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans,slug,' . $id,
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'trial_days' => 'required|integer|min:0',
            'max_restaurants' => 'required|integer|min:1',
            'max_branches' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1',
            'storage_limit_mb' => 'required|integer|min:1',
            'pos_module' => 'boolean',
            'qr_menu_module' => 'boolean',
            'inventory_module' => 'boolean',
            'accounting_module' => 'boolean',
            'hrm_module' => 'boolean',
            'crm_module' => 'boolean',
            'kitchen_display_module' => 'boolean',
            'online_ordering_module' => 'boolean',
            'delivery_module' => 'boolean',
            'reservation_module' => 'boolean',
            'marketing_module' => 'boolean',
            'mobile_app_module' => 'boolean',
            'api_access' => 'boolean',
            'white_label' => 'boolean',
            'custom_domain' => 'boolean',
            'priority_support' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'modules' => 'nullable|array',
            'menus' => 'nullable|array',
            'submenus' => 'nullable|array',
        ]);

        $plan = SubscriptionPlan::findOrFail($id);
        
        // Combine modules, menus, and submenus into enabled_modules array
        $enabledModules = [];
        
        // Process modules - use empty array if not present
        $modules = $request->input('modules', []);
        $menus = $request->input('menus', []);
        $submenus = $request->input('submenus', []);
        
        if (is_array($modules)) {
            $enabledModules = array_merge($enabledModules, $modules);
        }
        if (is_array($menus)) {
            $enabledModules = array_merge($enabledModules, $menus);
        }
        if (is_array($submenus)) {
            $enabledModules = array_merge($enabledModules, $submenus);
        }
        
        // Convert all values to integers and re-index array to remove string keys
        $enabledModules = array_map('intval', $enabledModules);
        $enabledModules = array_values(array_unique($enabledModules));
        
        // Remove the separate arrays from validated data
        unset($validated['modules'], $validated['menus'], $validated['submenus']);
        
        // Update the plan with validated data and enabled_modules
        $plan->update($validated);
        $plan->enabled_modules = $enabledModules;
        $plan->save();

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Subscription plan updated successfully!');
    }

    public function destroy($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Subscription plan deleted successfully!');
    }
}
