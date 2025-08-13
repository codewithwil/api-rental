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
    Route::group(["prefix" => "/people", "as" => "people."], __DIR__ . "/api/people/index.php");
    Route::group(["prefix" => "/resources", "as" => "resources."], __DIR__ . "/api/resources/index.php");
});
