<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/activityLog", "as"  => "activityLog."], __DIR__ . "/assets/activityLog.php");