<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|petugas'])->group(function () {
    Route::get("/", [ctr\API\Transactions\ReturnRentCar\ReturnRentCarC::class, 'index'])->name("index");
    Route::get("/create", [ctr\API\Transactions\ReturnRentCar\ReturnRentCarC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Transactions\ReturnRentCar\ReturnRentCarC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Transactions\ReturnRentCar\ReturnRentCarC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Transactions\ReturnRentCar\ReturnRentCarC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Transactions\ReturnRentCar\ReturnRentCarC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Transactions\ReturnRentCar\ReturnRentCarC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\Transactions\ReturnRentCar\ReturnRentCarC::class, 'delete'])->name("delete");
});
