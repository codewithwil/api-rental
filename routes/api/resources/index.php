<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/category", "as"        => "category."], __DIR__ . "/assets/category.php");