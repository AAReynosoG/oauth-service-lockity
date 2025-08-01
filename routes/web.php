<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;
use Laravel\Passport\Http\Controllers\AccessTokenController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('', '/login');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.view');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/code', [LoginController::class, 'showCodeForm'])->name('code.view');
Route::post('/code', [LoginController::class, 'verifyCode'])->name('code');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.view');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/email-verification', [RegisterController::class, 'emailVerification'])->name('email.verification');


Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize'])
        ->name('passport.authorizations.authorize');

    Route::post('/oauth/authorize', [ApproveAuthorizationController::class, 'approve'])
        ->name('passport.authorizations.approve');

    Route::delete('/oauth/authorize', [DenyAuthorizationController::class, 'deny'])
        ->name('passport.authorizations.deny');
});

Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])
    ->middleware('throttle');

Route::any('/web-logout', function () {
    Auth::logout();
    Session::invalidate();
    Session::regenerateToken();

    return response()->json([
        'success' => true,
        'message' => "Logged out from web successfully",
        'data' => null
    ]);
})->middleware('web');

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.view');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.view');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password');