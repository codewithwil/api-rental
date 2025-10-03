<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|petugas'])->group(function () {
    Route::get("/", [ctr\API\Resources\Vehicle\VehicleC::class, 'index'])->name("index");
    Route::get("/selected", [ctr\API\Resources\Vehicle\VehicleC::class, 'selected'])->name("selected");
    Route::get("/create", [ctr\API\Resources\Vehicle\VehicleC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Resources\Vehicle\VehicleC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Resources\Vehicle\VehicleC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Resources\Vehicle\VehicleC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Resources\Vehicle\VehicleC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Resources\Vehicle\VehicleC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\Resources\Vehicle\VehicleC::class, 'delete'])->name("delete");
});
