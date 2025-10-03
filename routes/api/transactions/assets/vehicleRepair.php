<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|petugas'])->group(function () {
    Route::get("/", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'index'])->name("index");
    Route::get("/getTypeApprove", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'getTypeApprove'])->name("getTypeApprove");
    Route::get("/create", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'update'])->name("update");
    Route::post("/updateStatus/{id}", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'updateStatus'])->name("updateStatus");
    Route::post("/delete/{id}", [ctr\API\Transactions\Vehicle\VehicleRepair\VehicleRepairC::class, 'delete'])->name("delete");
});
