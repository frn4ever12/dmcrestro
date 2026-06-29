<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::active()->ordered()->get();

        return response()->json([
            'plans' => $plans,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'trial_days' => 'required|integer|min:0',
            'max_restaurants' => 'required|integer|min:1',
            'max_branches' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:1',
            'storage_limit_mb' => 'required|integer|min:1',
            'pos_module' => 'sometimes|required|boolean',
            'qr_menu_module' => 'sometimes|required|boolean',
            'inventory_module' => 'sometimes|required|boolean',
            'accounting_module' => 'sometimes|required|boolean',
            'hrm_module' => 'sometimes|required|boolean',
            'crm_module' => 'sometimes|required|boolean',
            'kitchen_display_module' => 'sometimes|required|boolean',
            'online_ordering_module' => 'sometimes|required|boolean',
            'delivery_module' => 'sometimes|required|boolean',
            'api_access' => 'sometimes|required|boolean',
            'white_label' => 'sometimes|required|boolean',
            'custom_domain' => 'sometimes|required|boolean',
            'priority_support' => 'sometimes|required|boolean',
            'is_active' => 'sometimes|required|boolean',
            'sort_order' => 'sometimes|required|integer',
            'features' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $plan = SubscriptionPlan::create($request->all());

        return response()->json([
            'message' => 'Subscription plan created successfully',
            'plan' => $plan,
        ], 201);
    }

    public function show($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->load('tenants');

        return response()->json([
            'plan' => $plan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:subscription_plans,slug,' . $id,
            'description' => 'nullable|string',
            'monthly_price' => 'sometimes|required|numeric|min:0',
            'yearly_price' => 'sometimes|required|numeric|min:0',
            'trial_days' => 'sometimes|required|integer|min:0',
            'max_restaurants' => 'sometimes|required|integer|min:1',
            'max_branches' => 'sometimes|required|integer|min:1',
            'max_users' => 'sometimes|required|integer|min:1',
            'storage_limit_mb' => 'sometimes|required|integer|min:1',
            'is_active' => 'sometimes|required|boolean',
            'sort_order' => 'sometimes|required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $plan = SubscriptionPlan::findOrFail($id);
        $plan->update($request->all());

        return response()->json([
            'message' => 'Subscription plan updated successfully',
            'plan' => $plan,
        ]);
    }

    public function destroy($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        
        if ($plan->tenants()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete plan with active tenants',
            ], 422);
        }

        $plan->delete();

        return response()->json([
            'message' => 'Subscription plan deleted successfully',
        ]);
    }
}
