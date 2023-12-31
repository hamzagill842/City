<?php

use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\UserHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('sign-up', [\App\Http\Controllers\Auth\AuthController::class,'signup']);
Route::post('sign-in', [\App\Http\Controllers\Auth\AuthController::class,'signin']);

Route::post('password/reset-request',  [\App\Http\Controllers\Auth\ForgotPasswordController::class,'sendResetLinkEmail']);

Route::post('verify-otp', [\App\Http\Controllers\OtpVerificationController::class,'verifyOtp']);
Route::post('resend-otp', [\App\Http\Controllers\OtpVerificationController::class,'resendOtp']);
Route::post('forget-password', [\App\Http\Controllers\OtpVerificationController::class,'forgetPassword']);
Route::post('reset-password', [\App\Http\Controllers\OtpVerificationController::class,'resetPassword']);


Route::middleware('jwt')->group(function () {

    Route::get('user', [\App\Http\Controllers\UserController::class,'getUser']);
    Route::post('user', [\App\Http\Controllers\UserController::class,'updateUser']);
    Route::post('update-password', [\App\Http\Controllers\UserController::class,'updatePassword']);
    Route::post('provinces', [\App\Http\Controllers\ProvinceController::class,'store']);
    Route::get('provinces', [\App\Http\Controllers\ProvinceController::class,'index']);
    Route::get('cities', [\App\Http\Controllers\ProvinceController::class,'cities']);


    Route::get('favorite-cities', [UserHistoryController::class, 'index']);
    Route::post('/favorite-cities/add', [UserHistoryController::class, 'create']);
    Route::post('favorite-cities/remove', [UserHistoryController::class, 'removeFavoriteCity']);
    Route::post('logout', [\App\Http\Controllers\Auth\AuthController::class,'logout']);
});
