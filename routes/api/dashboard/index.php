<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|employee'])->group(function () {
    Route::get("/users", [ctr\API\Dashboard\DashboardC::class, 'countUser'])->name("countUser");
    Route::get("/nranch", [ctr\API\Dashboard\DashboardC::class, 'countBranch'])->name("countBranch");
    Route::get("/vehicle", [ctr\API\Dashboard\DashboardC::class, 'countVehicle'])->name("countVehicle");
});
