<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TenantManagementController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('subscriptionPlan')->latest()->paginate(15);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.tenants.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pan_number' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:20',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Create tenant
        $tenant = Tenant::create([
            'uuid' => Str::uuid(),
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? 'Nepal',
            'pan_number' => $validated['pan_number'] ?? null,
            'vat_number' => $validated['vat_number'] ?? null,
            'subscription_plan_id' => $validated['subscription_plan_id'],
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
        ]);

        // Get subscription plan limits
        $plan = SubscriptionPlan::find($validated['subscription_plan_id']);
        $tenant->update([
            'max_users' => $plan->max_users,
            'max_branches' => $plan->max_branches,
            'storage_limit_mb' => $plan->storage_limit_mb,
        ]);

        // Create owner user
        $user = User::create([
            'uuid' => Str::uuid(),
            'name' => $validated['owner_name'],
            'email' => $validated['owner_email'],
            'password' => $validated['password'],
            'user_type' => 'owner',
            'tenant_id' => $tenant->id,
        ]);

        // Create tenant user relationship
        $tenant->users()->attach($user->id, ['role' => 'owner']);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant created successfully!');
    }

    public function show($id)
    {
        $tenant = Tenant::with(['subscriptionPlan', 'users', 'restaurants'])->findOrFail($id);
        return view('admin.tenants.show', compact('tenant'));
    }

    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.tenants.edit', compact('tenant', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:tenants,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pan_number' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:20',
            'subscription_plan_id' => 'sometimes|required|exists:subscription_plans,id',
            'max_users' => 'sometimes|required|integer|min:1',
            'max_branches' => 'sometimes|required|integer|min:1',
            'storage_limit_mb' => 'sometimes|required|integer|min:1',
            'is_active' => 'sometimes|required|boolean',
            'new_password' => 'nullable|string|min:8',
            'confirm_password' => 'nullable|string|same:new_password',
        ]);

        $tenant = Tenant::findOrFail($id);
        $tenant->update($validated);

        if (isset($validated['subscription_plan_id'])) {
            $plan = SubscriptionPlan::find($validated['subscription_plan_id']);
            $tenant->update([
                'max_users' => $plan->max_users,
                'max_branches' => $plan->max_branches,
                'storage_limit_mb' => $plan->storage_limit_mb,
            ]);
        }

        // Update owner password if provided
        if (!empty($validated['new_password'])) {
            $owner = $tenant->users()->where('user_type', 'owner')->first();
            if ($owner) {
                $owner->update([
                    'password' => $validated['new_password']
                ]);
            }
        }

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully!');
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully!');
    }

    public function suspend(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        
        $tenant = Tenant::findOrFail($id);
        $tenant->update([
            'status' => 'suspended',
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant suspended successfully!');
    }

    public function activate($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update([
            'status' => 'active',
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant activated successfully!');
    }

    public function loginAs($id)
    {
        $tenant = Tenant::findOrFail($id);
        $owner = $tenant->users()->where('user_type', 'owner')->first();
        
        if (!$owner) {
            return redirect()->route('admin.tenants.index')->with('error', 'No owner found for this tenant.');
        }

        // Logout current user first
        Auth::logout();
        
        // Login as the owner
        Auth::login($owner);
        
        // Set tenant_id in session
        session(['tenant_id' => $tenant->id]);
        
        // Redirect to restaurant dashboard
        return redirect('/dashboard')->with('success', 'Logged in as ' . $tenant->name);
    }
}
