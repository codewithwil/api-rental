<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|petugas'])->group(function () {
    Route::get("/", [ctr\API\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizC::class, 'index'])->name("index");
    Route::get("/create", [ctr\API\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\Transactions\Vehicle\VehicleRepairRealiz\VehicleRepairRealizC::class, 'delete'])->name("delete");
});
