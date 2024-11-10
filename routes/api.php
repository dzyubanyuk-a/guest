<?php

use App\Http\Controllers\Api\GuestController;
use Illuminate\Support\Facades\Route;

Route::resource('guests', GuestController::class);
