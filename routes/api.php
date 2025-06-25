<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
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

Route::prefix('users')->middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/me', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'User data retrieved successfully',
            'data' => $request->user()
        ]);
    });

    Route::put('/me', [UserController::class, 'update']);
    Route::put('/me/password', [UserController::class, 'updatePassword']);
});
