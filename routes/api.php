<?php

use App\Http\Controllers\ParcelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/parcels', [ParcelController::class, 'store']);