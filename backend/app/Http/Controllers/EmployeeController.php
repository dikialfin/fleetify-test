<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = Employee::select(['employee_id', 'name', 'address'])->paginate(15);

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Successfully got the data ',
                'data' => $employees,
            ], 200);
        } catch (Exception $th) {
            return response()->json([
                'code' => "INTERNAL_SERVER_ERROR",
                'message' => 'Oops! something went wrong',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validationRules = [
            "employee_id" => 'required|string|unique:employee,employee_id',
            "departement_id" => 'required|string|exists:departement,id',
            "name" => 'required|string',
            "address" => 'required|string',
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
            $employee = new Employee();
            $employee->employee_id = $request->post('employee_id');
            $employee->departement_id = $request->post('departement_id');
            $employee->name = $request->post('name');
            $employee->address = $request->post('address');
            $employee->save();

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Data successfully inserted',
                'data' => [
                    "id" => $employee->id,
                    "employee_id" => $employee->employee_id,
                    "departement_id" => $employee->departement_id,
                    "name" => $employee->name,
                    "address" => $employee->address,
                ],
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'code' => "INTERNAL_SERVER_ERROR",
                'message' => 'Oops! something went wrong',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        try {
            $employee = Employee::where("employee_id", $id)->with('departement')->first();

            if ($employee == null) {
                return response()->json([
                    'code' => "DATA_NOT_FOUND",
                    'message' => 'the data you provided cannot be found',
                ], 404);
            }

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Successfully got the data ',
                'data' => [
                    "employee_id" => $employee->employee_id,
                    "name" => $employee->name,
                    "address" => $employee->address,
                    "departement_id" => $employee->departement_id,
                    "departement_name" => $employee->departement->departement_name,
                ],
            ], 200);
        } catch (Exception $th) {
            return response()->json([
                'code' => "INTERNAL_SERVER_ERROR",
                'message' => 'Oops! something went wrong',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(String $id, Request $request)
    {
        $employee = Employee::where("employee_id", $id)->first();

        if ($employee == null) {
            return response()->json([
                'code' => "DATA_NOT_FOUND",
                'message' => 'the data you provided cannot be found',
            ], 404);
        }

        $validationRules = [
            "employee_id" => 'string|unique:employee,employee_id',
            "departement_id" => 'string|exists:departement,id',
            "name" => 'string',
            "address" => 'string',
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

            if ($request->input('employee_id')) {
                $employee->employee_id = $request->input('employee_id');
            }

            if ($request->input('departement_id')) {
                $employee->departement_id = $request->input('departement_id');
            }

            if ($request->input('name')) {
                $employee->name = $request->input('name');
            }

            if ($request->input('address')) {
                $employee->address = $request->input('address');
            }

            $employee->save();

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Data successfully updated',
                'data' => [
                    "id" => $employee->id,
                    "employee_id" => $employee->employee_id,
                    "departement_id" => $employee->departement_id,
                    "name" => $employee->name,
                    "address" => $employee->address,
                ],
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'code' => "INTERNAL_SERVER_ERROR",
                'message' => 'Oops! something went wrong',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            $employee = Employee::where("employee_id", $id)->first();

            if ($employee == null) {
                return response()->json([
                    'code' => "DATA_NOT_FOUND",
                    'message' => 'the data you provided cannot be found',
                ], 404);
            }

            $employee->delete();

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Data successfully deleted',
                'data' => [
                    "employee_id" => $employee->employee_id,
                ],
            ], 201);
        } catch (Exception $th) {
            return response()->json([
                'code' => "INTERNAL_SERVER_ERROR",
                'message' => 'Oops! something went wrong',
            ], 500);
        }
    }
}
