<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|employee'])->group(function () {
    Route::get("/", [ctr\API\Resources\Category\Categoryc::class, 'index'])->name("index");
    Route::get("/create", [ctr\API\Resources\Category\Categoryc::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Resources\Category\Categoryc::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Resources\Category\Categoryc::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Resources\Category\Categoryc::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Resources\Category\Categoryc::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Resources\Category\Categoryc::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\Resources\Category\Categoryc::class, 'delete'])->name("delete");
});
