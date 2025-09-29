<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|employee'])->group(function () {
    Route::get("/", [ctr\API\Report\Kas\KasC::class, 'index'])->name("index");
    Route::get("/show/{id}", [ctr\API\Report\Kas\KasC::class, 'show'])->name("show");
    Route::get("/getTotalMasuk", [ctr\API\Report\Kas\KasC::class, 'getTotalMasuk'])->name("getTotalMasuk");
    Route::get("/getTotalKeluar", [ctr\API\Report\Kas\KasC::class, 'getTotalKeluar'])->name("getTotalKeluar");
    Route::get("/getSaldo", [ctr\API\Report\Kas\KasC::class, 'getSaldo'])->name("getSaldo");
});
