<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|petugas'])->group(function () {
    Route::get("/", [ctr\API\Transactions\Debts\DebtsC::class, 'index'])->name("index");
});
