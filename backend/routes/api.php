<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route ::get("/employee",[EmployeeController::class,'index']);
Route ::get("/employee/{id}",[EmployeeController::class,'show']);
Route ::post("/employee",[EmployeeController::class,'store']);
Route ::put("/employee/{id}",[EmployeeController::class,'update']);
Route ::delete("/employee/{id}",[EmployeeController::class,'destroy']);

Route ::get("/departement",[DepartementController::class,'index']);
Route ::get("/departement/{id}",[DepartementController::class,'show']);
Route ::post("/departement",[DepartementController::class,'store']);
Route ::put("/departement/{id}",[DepartementController::class,'update']);
Route ::delete("/departement/{id}",[DepartementController::class,'destroy']);

Route ::get("/attendance",[AttendanceController::class,'index']);
Route ::post("/clock-in",[AttendanceController::class,'store']);
Route ::put("/clock-out/{id}",[AttendanceController::class,'update']);
