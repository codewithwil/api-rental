<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|petugas'])->group(function () {
    Route::get("/", [ctr\API\Transactions\RentCar\RentCarC::class, 'index'])->name("index");
    Route::get("/getSelected", [ctr\API\Transactions\RentCar\RentCarC::class, 'getSelected'])->name("getSelected");
    Route::get("/create", [ctr\API\Transactions\RentCar\RentCarC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Transactions\RentCar\RentCarC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Transactions\RentCar\RentCarC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Transactions\RentCar\RentCarC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Transactions\RentCar\RentCarC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Transactions\RentCar\RentCarC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\Transactions\RentCar\RentCarC::class, 'delete'])->name("delete");
});
