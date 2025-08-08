<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    CompanyController,
    BranchController,
    AdminController,
    SupervisorController,
    EmployeeController,
    AuthController 
};

// Route publik (tidak perlu token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route private (butuh token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('admins', AdminController::class);
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('supervisors', SupervisorController::class);
});

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
});
