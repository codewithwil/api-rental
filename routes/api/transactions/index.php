<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/rentCar", "as"    => "rentCar."], __DIR__ . "/assets/rentCar.php");
Route::group(["prefix" => "/returnRentCar", "as"    => "returnRentCar."], __DIR__ . "/assets/returnRentCar.php");
Route::group(["prefix" => "/vehicleRepair", "as"    => "vehicleRepair."], __DIR__ . "/assets/vehicleRepair.php");
Route::group(["prefix" => "/vehicleRepairRealiz", "as"    => "vehicleRepairRealiz."], __DIR__ . "/assets/vehicleRepairRealiz.php");