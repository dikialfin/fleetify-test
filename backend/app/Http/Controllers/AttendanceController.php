<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceHistory;
use App\Models\Employee;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validationRules = [
            "departement_id" => 'string|exists:departement,id',
            "date" => [Rule::date()->format('Y-m-d')],
        ];

        $validator = validator($request->post(), $validationRules);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $errors[] = [
                    'field' => $field,
                    'error' => $messages[0]
                ];
            }

            return response()->json([
                'code' => "VALIDATION_ERROR",
                'message' => 'Oops! Invalid data provided',
                'errors' => $errors,
            ], 422);
        }

        try {

            $attendanceQuery = Attendance::with([
                "employee",
                "employee.departement"
            ]);

            if ($request->filled('departement_id')) {
                $attendanceQuery->whereHas('employee', function ($employeeQuery) use ($request) {
                    $employeeQuery->where('departement_id',$request->input('departement_id'));
                });
            }

            if ($request->filled('date')) {
                $attendanceQuery->whereDate('created_at',$request->input('date'));
            }

            $attendanceData = $attendanceQuery->get();
            $result = $attendanceData->map(function($attendance){
                $employee = $attendance->employee;
                $departement = $employee->departement;

                $clockIn = null;
                $clockOut = null;
                $maxClockOut = Carbon::parse($departement->max_clock_out_time)->format("H:i:s");
                $maxClockIn = Carbon::parse($departement->max_clock_in_time)->format("H:i:s");

                $clockInState = null;
                $clockOutState = null;

                if ($attendance->clock_in) {
                    $clockIn = Carbon::parse($attendance->clock_in)->format("H:i:s");
                    $clockInState = $clockIn > $maxClockIn ? 'LATE' : "NICE";
                }
                if ($attendance->clock_out) {
                    $clockOut = Carbon::parse($attendance->clock_out)->format("H:i:s");
                    $clockOutState = $clockOut < $maxClockOut ? 'EARLY' : "NICE";
                }

                return [
                    'employee_id' => $employee->employee_id,
                    "employee_name" => $employee->name,
                    "departement_id" => $departement->id,
                    "departement_name" => $departement->departement_name,
                    "max_clock_in" => $maxClockIn,
                    "clock_in" => $clockIn,
                    'clock_in_state' => $clockInState,
                    'max_clock_out' => $maxClockOut,
                    'clockOut' => $clockOut,
                    'clock_out_state' => $clockOutState
                ];
            });

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Successfully got the data ',
                'data' => $result,
            ], 200);
        } catch (Exception $th) {
            return response()->json([
                'code' => "INTERNAL_SERVER_ERROR",
                'message' => 'Oops! something went wrong',
                'exception' => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validationRules = [
            "employee_id" => 'required|string|exists:employee,employee_id',
            "clock_in_time" => ['required', Rule::date()->format('Y-m-d H:i:s')],
        ];

        $validator = validator($request->post(), $validationRules);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $errors[] = [
                    'field' => $field,
                    'error' => $messages[0]
                ];
            }

            return response()->json([
                'code' => "VALIDATION_ERROR",
                'message' => 'Oops! Invalid data provided',
                'errors' => $errors,
            ], 422);
        }

        try {
            $attendance = new Attendance();
            $attendanceHistory = new AttendanceHistory();

            DB::transaction(function () use ($attendance, $attendanceHistory, $request) {
                $attendance->employee_id = $request->post('employee_id');
                $attendance->clock_in = $request->post('clock_in_time');
                $attendance->save();

                $attendanceHistory->employee_id = $request->post('employee_id');
                $attendanceHistory->attendance_id = $attendance->id;
                $attendanceHistory->date_attendance = $request->post('clock_in_time');
                $attendanceHistory->attendance_type = 1;
                $attendanceHistory->description = $request->post('description') ?? '';
                $attendanceHistory->save();
            });

            $employee = Employee::where("employee_id", $request->post('employee_id'))->with('departement')->first();

            $dateTime = new DateTime($attendance->clock_in);
            $clockIn = $dateTime->format('H:i:s');

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Data successfully inserted',
                'data' => [
                    "attendance_id" => $attendance->id,
                    "clock_in" => $attendance->clock_in,
                    "status" => $clockIn > $employee->departement->max_clock_in_time ? "LATE" : "NICE",
                ],
            ], 201);
        } catch (Exception $th) {
            return response()->json([
                'code' => "INTERNAL_SERVER_ERROR",
                'message' => 'Oops! something went wrong',
                'exception' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attendance = Attendance::where("id", $id)->first();

        if ($attendance == null) {
            return response()->json([
                'code' => "DATA_NOT_FOUND",
                'message' => 'the data you provided cannot be found',
            ], 404);
        }

        $validationRules = [
            "clock_out_time" => ["required", Rule::date()->format('Y-m-d H:i:s')],
        ];

        $validator = validator($request->post(), $validationRules);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $errors[] = [
                    'field' => $field,
                    'error' => $messages[0]
                ];
            }

            return response()->json([
                'code' => "VALIDATION_ERROR",
                'message' => 'Oops! Invalid data provided',
                'errors' => $errors,
            ], 422);
        }

        try {
            $attendanceHistory = new AttendanceHistory();

            DB::transaction(function () use ($attendance, $attendanceHistory, $request) {
                $attendance->clock_out = $request->post('clock_out_time');
                $attendance->save();

                $attendanceHistory->employee_id = $attendance->employee_id;
                $attendanceHistory->attendance_id = $attendance->id;
                $attendanceHistory->date_attendance = $request->post('clock_out_time');
                $attendanceHistory->attendance_type = 2;
                $attendanceHistory->description = $request->post('description') ?? '';
                $attendanceHistory->save();
            });

            $employee = Employee::where("employee_id", $attendance->employee_id)->with('departement')->first();

            $dateTime = new DateTime($attendance->clock_out);
            $clockOut = $dateTime->format('H:i:s');

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Data successfully inserted',
                'data' => [
                    "attendance_id" => $attendance->id,
                    "clock_in" => $attendance->clock_in,
                    "status" => $clockOut < $employee->departement->max_clock_out_time ? "EARLY" : "NICE",
                ],
            ], 201);
        } catch (Exception $th) {
            return response()->json([
                'code' => "INTERNAL_SERVER_ERROR",
                'message' => 'Oops! something went wrong',
                'exception' => $th->getMessage()
            ], 500);
        }
    }
}
