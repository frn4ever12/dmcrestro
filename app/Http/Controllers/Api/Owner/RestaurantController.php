<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Repositories\RestaurantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    private RestaurantRepository $restaurantRepository;

    public function __construct(RestaurantRepository $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        $restaurants = $this->restaurantRepository->getByTenant($tenantId);

        return response()->json([
            'restaurants' => $restaurants,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:restaurants,slug',
            'description' => 'nullable|string',
            'pan_number' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'address' => 'required|string',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'municipality' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'invoice_prefix' => 'sometimes|required|string|max:10',
            'printer_type' => 'sometimes|required|in:thermal,dot_matrix,a4',
            'tax_rate' => 'sometimes|required|numeric|min:0|max:100',
            'service_charge_rate' => 'sometimes|required|numeric|min:0|max:100',
            'currency' => 'sometimes|required|string|max:10',
            'language' => 'sometimes|required|string|max:10',
            'nepali_date_enabled' => 'sometimes|required|boolean',
            'opening_time' => 'sometimes|required|date_format:H:i:s',
            'closing_time' => 'sometimes|required|date_format:H:i:s',
            'is_active' => 'sometimes|required|boolean',
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
            'owner_id' => $user->id,
        ]);

        $restaurant = $this->restaurantRepository->create($data);

        return response()->json([
            'message' => 'Restaurant created successfully',
            'restaurant' => $restaurant,
        ], 201);
    }

    public function show($id)
    {
        $restaurant = $this->restaurantRepository->findOrFail($id);
        $restaurant->load(['branches', 'menuCategories', 'tables']);

        return response()->json([
            'restaurant' => $restaurant,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:restaurants,slug,' . $id,
            'description' => 'nullable|string',
            'pan_number' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:20',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'address' => 'sometimes|required|string',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'municipality' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'invoice_prefix' => 'sometimes|required|string|max:10',
            'printer_type' => 'sometimes|required|in:thermal,dot_matrix,a4',
            'tax_rate' => 'sometimes|required|numeric|min:0|max:100',
            'service_charge_rate' => 'sometimes|required|numeric|min:0|max:100',
            'currency' => 'sometimes|required|string|max:10',
            'language' => 'sometimes|required|string|max:10',
            'nepali_date_enabled' => 'sometimes|required|boolean',
            'opening_time' => 'sometimes|required|date_format:H:i:s',
            'closing_time' => 'sometimes|required|date_format:H:i:s',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $restaurant = $this->restaurantRepository->update($id, $request->all());

        return response()->json([
            'message' => 'Restaurant updated successfully',
            'restaurant' => $restaurant,
        ]);
    }

    public function destroy($id)
    {
        $this->restaurantRepository->delete($id);

        return response()->json([
            'message' => 'Restaurant deleted successfully',
        ]);
    }
}
