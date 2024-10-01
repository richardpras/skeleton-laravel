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
Route::middleware('verifyweb')->group(function () { /** Uncoment on production **/
    Route::post('/logout', [AuthController::class, 'logout'])->name('Logout');
    Route::post('/login', function(LoginRequest $request){
        // $request->merge([
        //     'role' => [0,1,2]
        // ]);
        $controller=new AuthController();
        return $controller->login($request);
    })->name('Login');
});

Route::middleware('verifyweb')->group(function () { /** Uncoment on production **/
    
    Route::get('/verify_token', [AuthController::class, 'user'])->name('Verification User');
    Route::get('/user', [AuthController::class, 'user'])->name('User Detail');
});


Route::middleware('verifykey')->prefix('apps')->group(function () {
    Route::post('/login', function(LoginRequest $request){
        // $request->merge([
        //     'role' => [0,1]
        // ]);
        $controller=new AuthController();
        return $controller->login($request);
    })->name('Login Apps');
    Route::post('/logout', [AuthController::class, 'logout'])->name('Logout Apps');
    Route::post('/register', [AuthController::class, 'register'])->name('Register User Apps');
});

Route::middleware('auth:api')->group(function () {
    Route::middleware('verifykey')->prefix('apps')->group(function () {
        Route::get('/verify_token', [AuthController::class, 'user'])->name('Verification User Apps');
        Route::get('/user', [AuthController::class, 'user']);
    });
});