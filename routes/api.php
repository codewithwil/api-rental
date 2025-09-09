<?php

use App\{
    Http\Controllers\API\Auth\AuthC
};
use Illuminate\{
    Support\Facades\Route
};

Route::post('/login', [AuthC::class, 'login']);
Route::post('/register', [AuthC::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthC::class, 'logout']);
    Route::group(["prefix" => "/dashboard", "as" => "dashboard."], __DIR__ . "/api/dashboard/index.php");
    Route::group(["prefix" => "/people", "as" => "people."], __DIR__ . "/api/people/index.php");
    Route::group(["prefix" => "/resources", "as" => "resources."], __DIR__ . "/api/resources/index.php");
    Route::group(["prefix" => "/report", "as" => "report."], __DIR__ . "/api/report/index.php");
    Route::group(["prefix" => "/history", "as" => "history."], __DIR__ . "/api/history/index.php");
});
//
