<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|petugas'])->group(function () {
    Route::get("/", [ctr\API\Resources\Company\CompanyC::class, 'index'])->name("index");
    Route::post("/update", [ctr\API\Resources\Company\CompanyC::class, 'update'])->name("update");
});
    