<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|petugas'])->group(function () {
    Route::get("/", [ctr\API\Resources\Category\CategoryC::class, 'index'])->name("index");
    Route::get("/create", [ctr\API\Resources\Category\CategoryC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Resources\Category\CategoryC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Resources\Category\CategoryC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Resources\Category\CategoryC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Resources\Category\CategoryC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Resources\Category\CategoryC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\Resources\Category\CategoryC::class, 'delete'])->name("delete");
});
