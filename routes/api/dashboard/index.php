<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|employee'])->group(function () {
    Route::get("/users", [ctr\API\Dashboard\DashboardC::class, 'countUser'])->name("countUser");
    Route::get("/branch", [ctr\API\Dashboard\DashboardC::class, 'countBranch'])->name("countBranch");
    Route::get("/vehicle", [ctr\API\Dashboard\DashboardC::class, 'countVehicle'])->name("countVehicle");
    Route::get("/income", [ctr\API\Dashboard\DashboardC::class, 'chartIncome'])->name("chartIncome");
    Route::get("/outcome", [ctr\API\Dashboard\DashboardC::class, 'chartOutcome'])->name("chartOutcome");
    Route::get("/vehicleDepre", [ctr\API\Dashboard\DashboardC::class, 'chartVehicleDepreciate'])->name("chartVehicleDepreciate");
    Route::get("/chartProfitLoss", [ctr\API\Dashboard\DashboardC::class, 'chartProfitLoss'])->name("chartProfitLoss");
    Route::get("/chartReceivable", [ctr\API\Dashboard\DashboardC::class, 'chartReceivable'])->name("chartReceivable");
    Route::get("/chartPayable", [ctr\API\Dashboard\DashboardC::class, 'chartPayable'])->name("chartPayable");
});
