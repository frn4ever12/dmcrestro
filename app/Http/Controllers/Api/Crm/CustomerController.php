<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $customers = Customer::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('phone', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->when($request->has('is_loyalty_member'), fn($q) => $q->where('is_loyalty_member', $request->is_loyalty_member))
            ->with(['loyaltyPoints', 'addresses'])
            ->latest()
            ->paginate(50);

        return response()->json([
            'customers' => $customers,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'is_loyalty_member' => 'sometimes|required|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();
        $data = array_merge($request->all(), [
            'uuid' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $user->tenant_id,
            'restaurant_id' => $user->restaurant_id,
            'branch_id' => $user->branch_id,
        ]);

        $customer = Customer::create($data);

        return response()->json([
            'message' => 'Customer created successfully',
            'customer' => $customer,
        ], 201);
    }

    public function show($id)
    {
        $customer = Customer::with(['loyaltyPoints', 'addresses', 'orders', 'reviews'])->findOrFail($id);

        return response()->json([
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'is_loyalty_member' => 'sometimes|required|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return response()->json([
            'message' => 'Customer updated successfully',
            'customer' => $customer,
        ]);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully',
        ]);
    }

    public function addLoyaltyPoints(Request $request, $id)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($id);

        \App\Models\LoyaltyPoint::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $customer->tenant_id,
            'restaurant_id' => $customer->restaurant_id,
            'branch_id' => $customer->branch_id,
            'customer_id' => $customer->id,
            'points' => $request->points,
            'type' => 'earned',
            'reason' => $request->reason ?? 'Points added',
        ]);

        $customer->increment('total_points', $request->points);

        return response()->json([
            'message' => 'Loyalty points added successfully',
            'customer' => $customer->fresh(),
        ]);
    }

    public function redeemLoyaltyPoints(Request $request, $id)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($id);

        if ($customer->total_points < $request->points) {
            return response()->json([
                'message' => 'Insufficient loyalty points',
            ], 422);
        }

        \App\Models\LoyaltyPoint::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $customer->tenant_id,
            'restaurant_id' => $customer->restaurant_id,
            'branch_id' => $customer->branch_id,
            'customer_id' => $customer->id,
            'points' => $request->points,
            'type' => 'redeemed',
            'reason' => $request->reason ?? 'Points redeemed',
        ]);

        $customer->decrement('total_points', $request->points);

        return response()->json([
            'message' => 'Loyalty points redeemed successfully',
            'customer' => $customer->fresh(),
        ]);
    }
}
