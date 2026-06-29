<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $tables = Table::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('floor_id'), fn($q) => $q->where('floor_id', $request->floor_id))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->with('floor')
            ->get();

        return response()->json([
            'tables' => $tables,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:50',
            'floor_id' => 'nullable|exists:floors,id',
            'type' => 'sometimes|required|in:standard,booth,vip,outdoor',
            'capacity' => 'required|integer|min:1',
            'min_capacity' => 'nullable|integer|min:1',
            'is_mergeable' => 'sometimes|required|boolean',
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
            'restaurant_id' => $user->restaurant_id,
            'branch_id' => $user->branch_id,
        ]);

        $table = Table::create($data);

        // Generate QR code
        $qrCodeUrl = $this->generateQrCode($table);
        $table->update(['qr_code_url' => $qrCodeUrl]);

        return response()->json([
            'message' => 'Table created successfully',
            'table' => $table,
        ], 201);
    }

    public function show($id)
    {
        $table = Table::with(['floor', 'orders' => fn($q) => $q->latest()->take(5)])->findOrFail($id);

        return response()->json([
            'table' => $table,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'number' => 'sometimes|required|string|max:50',
            'floor_id' => 'nullable|exists:floors,id',
            'type' => 'sometimes|required|in:standard,booth,vip,outdoor',
            'capacity' => 'sometimes|required|integer|min:1',
            'min_capacity' => 'nullable|integer|min:1',
            'is_mergeable' => 'sometimes|required|boolean',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $table = Table::findOrFail($id);
        $table->update($request->all());

        return response()->json([
            'message' => 'Table updated successfully',
            'table' => $table,
        ]);
    }

    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();

        return response()->json([
            'message' => 'Table deleted successfully',
        ]);
    }

    public function markAvailable($id)
    {
        $table = Table::findOrFail($id);
        $table->markAsAvailable();

        return response()->json([
            'message' => 'Table marked as available',
            'table' => $table,
        ]);
    }

    public function markOccupied($id)
    {
        $table = Table::findOrFail($id);
        $table->markAsOccupied();

        return response()->json([
            'message' => 'Table marked as occupied',
            'table' => $table,
        ]);
    }

    protected function generateQrCode(Table $table): string
    {
        // Generate QR code URL for the table
        // In production, use a QR code library like simplesoftwareio/simple-qrcode
        $baseUrl = config('app.url');
        return "{$baseUrl}/public/menu/{$table->restaurant_id}/qr/{$table->id}";
    }
}
