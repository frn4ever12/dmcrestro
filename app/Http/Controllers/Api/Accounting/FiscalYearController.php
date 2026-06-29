<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use App\Helpers\NepaliDateHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FiscalYearController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;

        $fiscalYears = FiscalYear::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json([
            'fiscal_years' => $fiscalYears,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bs_year' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'sometimes|required|boolean',
            'is_closed' => 'sometimes|required|boolean',
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
        ]);

        // Close other fiscal years if this one is active
        if ($request->is_active ?? false) {
            FiscalYear::where('restaurant_id', $user->restaurant_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $fiscalYear = FiscalYear::create($data);

        return response()->json([
            'message' => 'Fiscal year created successfully',
            'fiscal_year' => $fiscalYear,
        ], 201);
    }

    public function show($id)
    {
        $fiscalYear = FiscalYear::with(['journals', 'journals.entries'])->findOrFail($id);

        return response()->json([
            'fiscal_year' => $fiscalYear,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'bs_year' => 'sometimes|required|string|max:20',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'is_active' => 'sometimes|required|boolean',
            'is_closed' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $fiscalYear = FiscalYear::findOrFail($id);

        // Close other fiscal years if this one is being activated
        if ($request->is_active ?? false) {
            FiscalYear::where('restaurant_id', $fiscalYear->restaurant_id)
                ->where('id', '!=', $id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $fiscalYear->update($request->all());

        return response()->json([
            'message' => 'Fiscal year updated successfully',
            'fiscal_year' => $fiscalYear,
        ]);
    }

    public function getCurrent()
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;

        $currentFiscalYear = FiscalYear::where('restaurant_id', $restaurantId)
            ->where('is_active', true)
            ->first();

        if (!$currentFiscalYear) {
            // Auto-create current fiscal year
            $fiscalYearInfo = NepaliDateHelper::currentFiscalYear();
            $currentFiscalYear = FiscalYear::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'tenant_id' => $user->tenant_id,
                'restaurant_id' => $restaurantId,
                'name' => $fiscalYearInfo['name'],
                'bs_year' => $fiscalYearInfo['name'],
                'start_date' => $fiscalYearInfo['ad_start'],
                'end_date' => $fiscalYearInfo['ad_end'],
                'is_active' => true,
                'is_closed' => false,
            ]);
        }

        return response()->json([
            'fiscal_year' => $currentFiscalYear,
        ]);
    }

    public function close($id)
    {
        $fiscalYear = FiscalYear::findOrFail($id);
        $fiscalYear->update([
            'is_active' => false,
            'is_closed' => true,
            'closed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Fiscal year closed successfully',
            'fiscal_year' => $fiscalYear,
        ]);
    }
}
