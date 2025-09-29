<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/weeklyReport", "as"    => "weeklyReport."], __DIR__ . "/assets/weeklyReport.php");
Route::group(["prefix" => "/kas", "as"    => "kas."], __DIR__ . "/assets/kas.php");