<?php

namespace App\Http\Controllers\Api\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $payrolls = Payroll::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('employee_id'), fn($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->has('month'), fn($q) => $q->where('month', $request->month))
            ->when($request->has('year'), fn($q) => $q->where('year', $request->year))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->with('employee')
            ->latest()
            ->paginate(50);

        return response()->json([
            'payrolls' => $payrolls,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|array',
            'deductions' => 'nullable|array',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();
        $employee = Employee::findOrFail($request->employee_id);

        // Check if payroll already exists for this month/year
        $existing = Payroll::where('employee_id', $request->employee_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Payroll already generated for this month',
                'payroll' => $existing,
            ], 422);
        }

        return DB::transaction(function () use ($request, $user, $employee) {
            $basicSalary = $request->basic_salary;
            $allowances = collect($request->allowances ?? [])->sum();
            $deductions = collect($request->deductions ?? [])->sum();
            $overtimePay = ($request->overtime_hours ?? 0) * ($request->overtime_rate ?? 0);
            $bonus = $request->bonus ?? 0;

            $grossSalary = $basicSalary + $allowances + $overtimePay + $bonus;
            $netSalary = $grossSalary - $deductions;

            $data = array_merge($request->all(), [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'tenant_id' => $user->tenant_id,
                'restaurant_id' => $user->restaurant_id,
                'branch_id' => $user->branch_id,
                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,
                'status' => 'pending',
            ]);

            $payroll = Payroll::create($data);

            return response()->json([
                'message' => 'Payroll generated successfully',
                'payroll' => $payroll->load('employee'),
            ], 201);
        });
    }

    public function show($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);

        return response()->json([
            'payroll' => $payroll,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'basic_salary' => 'sometimes|required|numeric|min:0',
            'allowances' => 'nullable|array',
            'deductions' => 'nullable|array',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:pending,paid,cancelled',
            'paid_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        return DB::transaction(function () use ($request, $id) {
            $payroll = Payroll::findOrFail($id);

            $basicSalary = $request->basic_salary ?? $payroll->basic_salary;
            $allowances = collect($request->allowances ?? $payroll->allowances ?? [])->sum();
            $deductions = collect($request->deductions ?? $payroll->deductions ?? [])->sum();
            $overtimePay = ($request->overtime_hours ?? $payroll->overtime_hours ?? 0) * ($request->overtime_rate ?? $payroll->overtime_rate ?? 0);
            $bonus = $request->bonus ?? $payroll->bonus ?? 0;

            $grossSalary = $basicSalary + $allowances + $overtimePay + $bonus;
            $netSalary = $grossSalary - $deductions;

            $payroll->update(array_merge($request->all(), [
                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,
            ]));

            return response()->json([
                'message' => 'Payroll updated successfully',
                'payroll' => $payroll->load('employee'),
            ]);
        });
    }

    public function markPaid($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);

        return response()->json([
            'message' => 'Payroll marked as paid',
            'payroll' => $payroll,
        ]);
    }

    public function generateBulk(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        $user = auth()->user();
        $payrolls = [];

        foreach ($request->employee_ids as $employeeId) {
            $employee = Employee::findOrFail($employeeId);

            // Check if payroll already exists
            $existing = Payroll::where('employee_id', $employeeId)
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->first();

            if (!$existing) {
                $basicSalary = $employee->basic_salary;
                $allowances = collect($employee->allowances ?? [])->sum();
                $deductions = collect($employee->deductions ?? [])->sum();

                $grossSalary = $basicSalary + $allowances;
                $netSalary = $grossSalary - $deductions;

                $payroll = Payroll::create([
                    'uuid' => \Illuminate\Support\Str::uuid(),
                    'tenant_id' => $user->tenant_id,
                    'restaurant_id' => $user->restaurant_id,
                    'branch_id' => $user->branch_id,
                    'employee_id' => $employeeId,
                    'month' => $request->month,
                    'year' => $request->year,
                    'basic_salary' => $basicSalary,
                    'allowances' => $employee->allowances,
                    'deductions' => $employee->deductions,
                    'gross_salary' => $grossSalary,
                    'net_salary' => $netSalary,
                    'status' => 'pending',
                ]);

                $payrolls[] = $payroll;
            }
        }

        return response()->json([
            'message' => 'Bulk payroll generated successfully',
            'payrolls' => $payrolls,
        ], 201);
    }
}
