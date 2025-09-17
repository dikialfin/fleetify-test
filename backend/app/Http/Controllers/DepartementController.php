<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $departement = Departement::select(['id', 'departement_name', 'max_clock_in_time', 'max_clock_out_time'])->paginate(15);

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Successfully got the data ',
                'data' => $departement,
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
            "departement_name" => 'required|string|unique:departement,departement_name',
            "max_clock_in_time" => ['required', Rule::date()->format('H:i:s')],
            "max_clock_out_time" => ['required', Rule::date()->format('H:i:s')],
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
            $departement = new Departement();
            $departement->departement_name = $request->post('departement_name');
            $departement->max_clock_in_time = $request->post('max_clock_in_time');
            $departement->max_clock_out_time = $request->post('max_clock_out_time');
            $departement->save();

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Data successfully inserted',
                'data' => [
                    "id" => $departement->id,
                    "departement_name" => $departement->departement_name,
                    "max_clock_in_time" => $departement->max_clock_in_time,
                    "max_clock_out_time" => $departement->max_clock_out_time,
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
            $departement = Departement::find($id)->first();

            if ($departement == null) {
                return response()->json([
                    'code' => "DATA_NOT_FOUND",
                    'message' => 'the data you provided cannot be found',
                ], 404);
            }

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Successfully got the data ',
                'data' => [
                    "id" => $departement->id,
                    "departement_name" => $departement->departement_name,
                    "max_clock_in_time" => $departement->max_clock_in_time,
                    "max_clock_out_time" => $departement->max_clock_out_time,
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
        $departement = Departement::find($id)->first();

        if ($departement == null) {
            return response()->json([
                'code' => "DATA_NOT_FOUND",
                'message' => 'the data you provided cannot be found',
            ], 404);
        }

        $validationRules = [
            "departement_name" => 'string|unique:departement,departement_name',
            "max_clock_in_time" => [Rule::date()->format('H:i:s')],
            "max_clock_out_time" => [Rule::date()->format('H:i:s')],
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

            if ($request->input('departement_name')) {
                $departement->departement_name = $request->input('departement_name');
            }

            if ($request->input('max_clock_in_time')) {
                $departement->max_clock_in_time = $request->input('max_clock_in_time');
            }

            if ($request->input('max_clock_out_time')) {
                $departement->max_clock_out_time = $request->input('max_clock_out_time');
            }

            $departement->save();

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Data successfully updated',
                'data' => [
                    "id" => $departement->id,
                    "departement_name" => $departement->departement_name,
                    "max_clock_in_time" => $departement->max_clock_in_time,
                    "max_clock_out_time" => $departement->max_clock_out_time,
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
            $departement = Departement::where("id", $id)->first();

            if ($departement == null) {
                return response()->json([
                    'code' => "DATA_NOT_FOUND",
                    'message' => 'the data you provided cannot be found',
                ], 404);
            }

            $departement->delete();

            return response()->json([
                'code' => "SUCCESS",
                'message' => 'Data successfully deleted',
                'data' => [
                    "id" => $departement->id,
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
