<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/allPeople", "as"    => "allPeople."], __DIR__ . "/assets/allPeople.php");
Route::group(["prefix" => "/admin", "as"        => "admin."], __DIR__ . "/assets/admin.php");
Route::group(["prefix" => "/supervisor", "as"   => "supervisor."], __DIR__ . "/assets/supervisor.php");
Route::group(["prefix" => "/employee", "as"     => "employee."], __DIR__ . "/assets/employee.php");