<?php

namespace App\Http\Controllers\Api\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $attendances = Attendance::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('employee_id'), fn($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->has('date'), fn($q) => $q->whereDate('date', $request->date))
            ->when($request->has('from_date') && $request->has('to_date'), fn($q) => $q->whereBetween('date', [$request->from_date, $request->to_date]))
            ->with('employee')
            ->latest()
            ->paginate(50);

        return response()->json([
            'attendances' => $attendances,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i:s',
            'check_out' => 'nullable|date_format:H:i:s|after:check_in',
            'status' => 'sometimes|required|in:present,absent,late,half_day,leave',
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

        // Check if attendance already exists for this date
        $existing = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Attendance already recorded for this date',
                'attendance' => $existing,
            ], 422);
        }

        $data = array_merge($request->all(), [
            'uuid' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $user->tenant_id,
            'restaurant_id' => $user->restaurant_id,
            'branch_id' => $user->branch_id,
        ]);

        $attendance = Attendance::create($data);

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'attendance' => $attendance->load('employee'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'check_in' => 'sometimes|required|date_format:H:i:s',
            'check_out' => 'nullable|date_format:H:i:s|after:check_in',
            'status' => 'sometimes|required|in:present,absent,late,half_day,leave',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return response()->json([
            'message' => 'Attendance updated successfully',
            'attendance' => $attendance->load('employee'),
        ]);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $user = auth()->user();
        $employee = Employee::findOrFail($request->employee_id);

        $attendance = Attendance::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $user->tenant_id,
            'restaurant_id' => $user->restaurant_id,
            'branch_id' => $user->branch_id,
            'employee_id' => $request->employee_id,
            'date' => now()->toDateString(),
            'check_in' => now()->toTimeString(),
            'status' => 'present',
        ]);

        return response()->json([
            'message' => 'Check-in successful',
            'attendance' => $attendance,
        ], 201);
    }

    public function checkOut(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        if ($attendance->check_out) {
            return response()->json([
                'message' => 'Already checked out',
            ], 422);
        }

        $attendance->update([
            'check_out' => now()->toTimeString(),
        ]);

        return response()->json([
            'message' => 'Check-out successful',
            'attendance' => $attendance,
        ]);
    }

    public function today()
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        $attendances = Attendance::whereDate('date', today())
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->with('employee')
            ->get();

        return response()->json([
            'attendances' => $attendances,
        ]);
    }
}
