<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:admin|supervisor'])->group(function () {
    Route::get("/", [ctr\API\People\AllPeople\AllPeopleC::class, 'index'])->name("index");
});
