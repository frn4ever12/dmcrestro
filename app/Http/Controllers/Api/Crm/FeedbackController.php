<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $feedbacks = Feedback::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('type'), fn($q) => $q->where('type', $request->type))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->has('from_date') && $request->has('to_date'), fn($q) => $q->whereBetween('created_at', [$request->from_date, $request->to_date]))
            ->latest()
            ->paginate(50);

        return response()->json([
            'feedbacks' => $feedbacks,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'type' => 'required|in:complaint,suggestion,compliment,other',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
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
            'priority' => $request->priority ?? 'medium',
        ]);

        $feedback = Feedback::create($data);

        return response()->json([
            'message' => 'Feedback submitted successfully',
            'feedback' => $feedback,
        ], 201);
    }

    public function show($id)
    {
        $feedback = Feedback::with(['customer', 'order'])->findOrFail($id);

        return response()->json([
            'feedback' => $feedback,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|in:pending,in_progress,resolved,closed',
            'response' => 'nullable|string',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $feedback = Feedback::findOrFail($id);
        $feedback->update($request->all());

        return response()->json([
            'message' => 'Feedback updated successfully',
            'feedback' => $feedback,
        ]);
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json([
            'message' => 'Feedback deleted successfully',
        ]);
    }

    public function resolve(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string',
        ]);

        $feedback = Feedback::findOrFail($id);
        $feedback->update([
            'status' => 'resolved',
            'response' => $request->response,
            'resolved_at' => now(),
        ]);

        return response()->json([
            'message' => 'Feedback resolved successfully',
            'feedback' => $feedback,
        ]);
    }

    public function getStats(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $query = Feedback::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId));

        $total = $query->count();
        $pending = $query->where('status', 'pending')->count();
        $inProgress = $query->where('status', 'in_progress')->count();
        $resolved = $query->where('status', 'resolved')->count();

        $byType = $query->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        $byPriority = $query->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority');

        return response()->json([
            'total' => $total,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'by_type' => $byType,
            'by_priority' => $byPriority,
        ]);
    }
}
