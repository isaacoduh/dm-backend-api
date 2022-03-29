<?php

use App\Http\Controllers\API\Auth\ForgotPasswordController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use Illuminate\Auth\Notifications\ResetPassword;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);


Route::post('password/forgot-pasword', [ForgotPasswordController::class,'sendResetLinkResponse'])->name('password.sent');
Route::post('password/reset', [ResetPasswordController::class, 'submitResetPassword'])->name('passwords.reset');