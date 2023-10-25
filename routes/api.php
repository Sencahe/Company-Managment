<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/auth/token", [AuthController::class,"login"]);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get("/companies", [CompanyController::class,"index"]);

    Route::get("/employees", [EmployeeController::class,"index"]);

});




