<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|employee'])->group(function () {
    Route::get("/", [ctr\API\Resources\Brand\BrandC::class, 'index'])->name("index");
    Route::get("/create", [ctr\API\Resources\Brand\BrandC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Resources\Brand\BrandC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Resources\Brand\BrandC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Resources\Brand\BrandC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Resources\Brand\BrandC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Resources\Brand\BrandC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\Resources\Brand\BrandC::class, 'delete'])->name("delete");
});
