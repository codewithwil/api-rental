<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor'])->group(function () {
    Route::get("/", [ctr\API\People\Employee\EmployeeC::class, 'index'])->name("index");
    Route::get("/create", [ctr\API\People\Employee\EmployeeC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\People\Employee\EmployeeC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\People\Employee\EmployeeC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\People\Employee\EmployeeC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\People\Employee\EmployeeC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\People\Employee\EmployeeC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\People\Employee\EmployeeC::class, 'delete'])->name("delete");
});
