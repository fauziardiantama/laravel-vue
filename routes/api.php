<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:dosen')->get('/dosen', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:mahasiswa')->get('/mahasiswa', function (Request $request) {
    return $request->user()->mahasiswa()->first();
});

Route::middleware('auth:admin')->get('/admin', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:admin,dosen,mahasiswa')->post('/logout', [AuthController::class, 'logout']);

Route::prefix('/admin')->group(function () {
    Route::post('/login', [AuthController::class, 'loginAdmin']);
});
Route::prefix('/mahasiswa')->group(function () {
    Route::post('/login', [AuthController::class, 'loginMahasiswa']);
    Route::post('/register', [AuthController::class, 'registerMahasiswa']);
});
Route::prefix('/dosen')->group(function () {
    Route::post('/login', [AuthController::class, 'loginDosen']);
});

Route::prefix('/kmm')->group(function () {
    Route::prefix('/items')->group(function () {
        Route::middleware('auth:admin,dosen,mahasiswa')->group(function () {
            Route::get('/{id}', [ItemController::class, 'show']);
            Route::get('/', [ItemController::class, 'index']);
        });
        Route::middleware('auth:admin')->group(function () {
            Route::post('/', [ItemController::class, 'store']);
            Route::put('/{id}', [ItemController::class, 'update']);
            Route::delete('/{id}', [ItemController::class, 'destroy']);
        });
    });
    //resource of kmm
});

Route::prefix('/ta')->group(function() {
    Route::prefix('/items')->group(function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::get('/{id}', [ItemController::class, 'show']);
        Route::put('/{id}', [ItemController::class, 'update']);
        Route::delete('/{id}', [ItemController::class, 'destroy']);
    });
    //resource of ta
});