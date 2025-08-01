<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
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

Route::prefix('users')->middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/me', [UserController::class, 'me']);
    Route::put('/me', [UserController::class, 'update']);
    Route::put('/me/password', [UserController::class, 'updatePassword']);
    Route::get('/has-lockers', [UserController::class, 'hasLockers']);
});
