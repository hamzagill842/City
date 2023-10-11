<?php

use App\Http\Controllers\ProvinceController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('sign-up', [\App\Http\Controllers\Auth\AuthController::class,'signup']);
Route::post('sign-in', [\App\Http\Controllers\Auth\AuthController::class,'signin']);


Route::post('password/reset-request',  [\App\Http\Controllers\Auth\ForgotPasswordController::class,'sendResetLinkEmail']);


Route::middleware('jwt')->group(function () {

    Route::get('user', [\App\Http\Controllers\UserController::class,'getUser']);
    Route::post('user', [\App\Http\Controllers\UserController::class,'updateUser']);
    Route::post('provinces', [\App\Http\Controllers\ProvinceController::class,'store']);
//    Route::get('provinces', [\App\Http\Controllers\ProvinceController::class,'index']);
    Route::get('cities', [\App\Http\Controllers\ProvinceController::class,'cities']);
    Route::post('logout', [\App\Http\Controllers\Auth\AuthController::class,'logout']);
});
Route::post('verify-otp', [\App\Http\Controllers\OtpVerificationController::class,'verifyOtp']);
Route::post('resend-otp', [\App\Http\Controllers\OtpVerificationController::class,'resendOtp']);
Route::get('provinces', [\App\Http\Controllers\ProvinceController::class,'index']);
