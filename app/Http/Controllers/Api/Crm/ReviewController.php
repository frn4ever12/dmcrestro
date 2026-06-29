<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $reviews = Review::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('customer_id'), fn($q) => $q->where('customer_id', $request->customer_id))
            ->when($request->has('rating'), fn($q) => $q->where('rating', $request->rating))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->with(['customer', 'order'])
            ->latest()
            ->paginate(50);

        return response()->json([
            'reviews' => $reviews,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'food_rating' => 'nullable|integer|min:1|max:5',
            'service_rating' => 'nullable|integer|min:1|max:5',
            'ambiance_rating' => 'nullable|integer|min:1|max:5',
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
            'status' => 'pending',
        ]);

        $review = Review::create($data);

        return response()->json([
            'message' => 'Review submitted successfully',
            'review' => $review->load(['customer', 'order']),
        ], 201);
    }

    public function show($id)
    {
        $review = Review::with(['customer', 'order'])->findOrFail($id);

        return response()->json([
            'review' => $review,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'food_rating' => 'nullable|integer|min:1|max:5',
            'service_rating' => 'nullable|integer|min:1|max:5',
            'ambiance_rating' => 'nullable|integer|min:1|max:5',
            'status' => 'sometimes|required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $review = Review::findOrFail($id);
        $review->update($request->all());

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review->load(['customer', 'order']),
        ]);
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'approved']);

        return response()->json([
            'message' => 'Review approved successfully',
            'review' => $review,
        ]);
    }

    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'rejected']);

        return response()->json([
            'message' => 'Review rejected successfully',
            'review' => $review,
        ]);
    }

    public function getAverageRating(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $reviews = Review::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'approved')
            ->get();

        $averageRating = $reviews->avg('rating');
        $averageFoodRating = $reviews->avg('food_rating');
        $averageServiceRating = $reviews->avg('service_rating');
        $averageAmbianceRating = $reviews->avg('ambiance_rating');

        $ratingDistribution = $reviews->groupBy('rating')->map(function ($reviews) {
            return $reviews->count();
        });

        return response()->json([
            'total_reviews' => $reviews->count(),
            'average_rating' => round($averageRating, 2),
            'average_food_rating' => round($averageFoodRating, 2),
            'average_service_rating' => round($averageServiceRating, 2),
            'average_ambiance_rating' => round($averageAmbianceRating, 2),
            'rating_distribution' => $ratingDistribution,
        ]);
    }
}
