<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\ProfileController;

//Регистрация

Route::middleware(['api.prev'])->group(function () {
    Route::get('/cities', [CityController::class, 'index']);

    Route::controller(AuthController::class)->group(function () {
        Route::post('/signup', 'signup');
        Route::post('/signin', 'signin');
        Route::post('/forget_pass', 'forgetPass');
        Route::post('/enter_pincode', 'enterPincode');
    });
    
    Route::middleware(['api.auth'])->group(function () {
        Route::post('/restore_pass', [AuthController::class, 'restorePass']);

        Route::controller(ProfileController::class)->group(function () {
            Route::post('/current_user', 'getCurrentUser');
            Route::post('/save_user_data', 'saveUserData');
        });
    });
});
