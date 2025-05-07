<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/test', [HomeController::class, 'test'])->name('test');

//User All Routes
Route::post('/user-registration', [UserController::class, 'UserRegistration'])->name('user.registration'); //user registration
Route::post('/user-login', [UserController::class, 'UserLogin'])->name('user.login'); //user login
Route:: post('/send-otp', [UserController::class, 'SendOtpCode'])->name('SendOtpCode'); //send otp

Route::middleware(TokenVerificationMiddleware::class)->group(function(){
    Route::get('/DashBoardPage', [UserController::class, 'DashBoardPage'])->name('dashboard.page'); //user dashboard page
Route::get('/user-logout', [UserController::class, 'UserLogout'])->name('user.logout'); //user logout
});