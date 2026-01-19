<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|petugas'])->group(function () {
    Route::get("/", [ctr\API\Resources\Branch\BranchC::class, 'index'])->name("index");
    Route::get("/create", [ctr\API\Resources\Branch\BranchC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Resources\Branch\BranchC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Resources\Branch\BranchC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Resources\Branch\BranchC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Resources\Branch\BranchC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Resources\Branch\BranchC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\Resources\Branch\BranchC::class, 'delete'])->name("delete");
});
