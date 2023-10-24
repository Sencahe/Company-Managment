<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;

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
    Route::get("/auth/session", function () {return 200; });
    Route::post("/auth/logout", [AuthController::class, "logout"]);

    Route::get("/request/companies", [CompanyController::class,"index"]);
    Route::post("/request/company", [CompanyController::class,"store"]);
    Route::get("/request/company/{company}", [CompanyController::class,"show"]);
    Route::post("/request/company/{company}", [CompanyController::class,"update"]);
    Route::delete("/request/company/{company}", [CompanyController::class,"destroy"]);

});
