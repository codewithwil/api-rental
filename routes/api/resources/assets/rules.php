<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|employee'])->group(function () {
    Route::get("/", [ctr\API\Resources\Rules\RulesC::class, 'index'])->name("index");
    Route::post("/update", [ctr\API\Resources\Rules\RulesC::class, 'update'])->name("update");
});
    