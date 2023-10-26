<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/{any}', function () {
    return view('index');
})->where('any', '^(?!auth|request).*$');

Route::post("/auth", [AuthController::class, "login"]);

Route::middleware(['auth:sanctum'])->group(function () {
    // Logout and Session
    Route::get("/auth/session", function (Request $request) {return $request->user(); });
    Route::post("/auth/logout", [AuthController::class, "logout"]);
    // Get Comapanies
    Route::get("/request/companies", [CompanyController::class,"index"]);
    Route::get("/request/companies/count", [CompanyController::class,"count"]);
    Route::get("/request/companies/by_employees/{companyCount}", [CompanyController::class,"companiesByEmployeesCount"]);
    Route::get("/request/company/{company}", [CompanyController::class,"show"]);
    // Get Employees
    Route::get("/request/employees", [EmployeeController::class,"index"]);
    Route::get("/request/employees/count", [EmployeeController::class,"count"]);
    Route::get("/request/employee/{employee}", [EmployeeController::class,"show"]);
});



Route::middleware(['admin'])->group(function () {
    // Modify Companies
    Route::post("/request/company", [CompanyController::class,"store"]);
    Route::post("/request/company/{company}", [CompanyController::class,"update"]);
    Route::delete("/request/company/{company}", [CompanyController::class,"destroy"]);
    // Modify Employees
    Route::post("/request/employee", [EmployeeController::class,"store"]);
    Route::put("/request/employee/{employee}", [EmployeeController::class,"update"]);
    Route::delete("/request/employee/{employee}", [EmployeeController::class,"destroy"]);

});

