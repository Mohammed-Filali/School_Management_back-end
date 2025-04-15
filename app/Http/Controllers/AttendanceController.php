<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'date' => 'sometimes|date_format:Y-m-d',
                'month' => 'sometimes|date_format:Y-m',
                'user_id' => 'sometimes|exists:users,id'
            ]);

            $query = Attendance::with(['user' => function($q) {
                $q->select('id', 'name', 'email', 'classe_id');
            }]);

            // Filter by specific date
            if ($request->date) {
                $query->whereDate('date', $request->date);
            }

            // Filter by month
            if ($request->month) {
                $query->whereMonth('date', Carbon::parse($request->month)->month)
                      ->whereYear('date', Carbon::parse($request->month)->year);
            }

            // Filter by user
            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            $attendances = $query->orderBy('date', 'desc')
                               ->get()
                               ->groupBy(function($item) {
                                   return $item->date->format('Y-m-d');
                               });

            return response()->json([
                'success' => true,
                'data' => $attendances,
                'meta' => [
                    'total_days' => count($attendances),
                    'total_records' => $attendances->sum(function($items) {
                        return count($items);
                    })
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Attendance index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch attendance records'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'user_id' => 'required|exists:users,id',
                'status' => ['required', Rule::in(['present', 'absent', 'late', 'excused'])],
                'notes' => 'nullable|string|max:255'
            ]);

            $attendance = Attendance::updateOrCreate(
                [
                    'date' => $validated['date'],
                    'user_id' => $validated['user_id']
                ],
                [
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? null
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'data' => $attendance->load('user')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Attendance store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to record attendance'
            ], 500);
        }
    }

    public function bulkStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'attendances' => 'required|array',
                'attendances.*.user_id' => 'required|exists:users,id',
                'attendances.*.status' => ['required', Rule::in(['present', 'absent', 'late', 'excused'])],
                'attendances.*.notes' => 'nullable|string|max:255'
            ]);

            DB::beginTransaction();

            $results = [];
            foreach ($validated['attendances'] as $attendance) {
                $record = Attendance::updateOrCreate(
                    [
                        'date' => $validated['date'],
                        'user_id' => $attendance['user_id']
                    ],
                    [
                        'status' => $attendance['status'],
                        'notes' => $attendance['notes'] ?? null
                    ]
                );

                $results[] = $record->load('user');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk attendance saved successfully',
                'data' => $results,
                'meta' => [
                    'total_saved' => count($results),
                    'date' => $validated['date']
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk attendance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save bulk attendance'
            ], 500);
        }
    }




    public function stats(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
                'user_id' => 'sometimes|exists:users,id'
            ]);

            $query = Attendance::whereBetween('date', [
                $request->start_date,
                $request->end_date
            ]);

            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            $stats = $query->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count,
                    SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_count,
                    SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_count,
                    SUM(CASE WHEN status = "excused" THEN 1 ELSE 0 END) as excused_count
                ')
                ->first();

            return response()->json([
                'success' => true,
                'data' => $stats,
                'meta' => [
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'user_id' => $request->user_id
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Attendance stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch attendance statistics'
            ], 500);
        }
    }
}
