<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChartOfAccountsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;

        $accounts = ChartOfAccounts::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($request->has('account_type'), fn($q) => $q->where('account_type', $request->account_type))
            ->when($request->has('parent_id'), fn($q) => $q->where('parent_id', $request->parent_id))
            ->orderBy('code')
            ->get();

        return response()->json([
            'accounts' => $accounts,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'name_np' => 'nullable|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'opening_balance' => 'sometimes|required|numeric',
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
        ]);

        $account = ChartOfAccounts::create($data);

        return response()->json([
            'message' => 'Account created successfully',
            'account' => $account,
        ], 201);
    }

    public function show($id)
    {
        $account = ChartOfAccounts::with(['children', 'parent'])->findOrFail($id);

        return response()->json([
            'account' => $account,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|required|string|max:50',
            'name' => 'sometimes|required|string|max:255',
            'name_np' => 'nullable|string|max:255',
            'account_type' => 'sometimes|required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'opening_balance' => 'sometimes|required|numeric',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $account = ChartOfAccounts::findOrFail($id);
        $account->update($request->all());

        return response()->json([
            'message' => 'Account updated successfully',
            'account' => $account,
        ]);
    }

    public function getTree()
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;

        $accounts = ChartOfAccounts::where('restaurant_id', $restaurantId)
            ->whereNull('parent_id')
            ->with('children.children')
            ->orderBy('code')
            ->get();

        return response()->json([
            'accounts_tree' => $accounts,
        ]);
    }
}
