<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/rentCar", "as"    => "rentCar."], __DIR__ . "/assets/rentCar.php");
Route::group(["prefix" => "/returnRentCar", "as"    => "returnRentCar."], __DIR__ . "/assets/returnRentCar.php");