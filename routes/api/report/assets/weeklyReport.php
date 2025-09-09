<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor|employee'])->group(function () {
    Route::get("/", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'index'])->name("index");
    Route::get("/create", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'create'])->name("create");
    Route::get("/invoice", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'invoice'])->name("invoice");
    Route::get("/show/{id}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'show'])->name("show");
    Route::get("/edit/{id}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'edit'])->name("edit");
    Route::post("/store", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'store'])->name("store");
    Route::post("/update/{id}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'update'])->name("update");
    Route::post("/delete/{id}", [ctr\API\Report\WeeklyReport\WeeklyReportC::class, 'delete'])->name("delete");
});
