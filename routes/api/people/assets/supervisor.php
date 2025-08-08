<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor'])->group(function () {
    Route::get("/", [ctr\API\People\Supervisor\SupervisorC::class, 'index'])->name("index");
    Route::get("/create", [ctr\API\People\Supervisor\SupervisorC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\People\Supervisor\SupervisorC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\People\Supervisor\SupervisorC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\People\Supervisor\SupervisorC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\People\Supervisor\SupervisorC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\People\Supervisor\SupervisorC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\People\Supervisor\SupervisorC::class, 'delete'])->name("delete");
});
