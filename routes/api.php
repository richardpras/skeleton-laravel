<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/logout', [AuthController::class, 'logout'])->name('Logout');
Route::post('/register', [AuthController::class, 'register'])->name('Register User');
Route::post('/login', [AuthController::class, 'login'])->name('Login');
Route::middleware('auth:api')->group(function () {

    Route::get('/user', [AuthController::class, 'user'])->middleware('AccessControl')->name('User Detail');
});
