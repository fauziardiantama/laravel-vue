<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware('auth:passport')->group(function() {
    Route::prefix('/mahasiswa')->group(function () {
        Route::prefix('/dokumen-registrasi')->group(function () {
            Route::get('/kartu-mahasiswa/{file}', [DokumenController::class, 'kartuMahasiswa']);
            Route::get('/krs/{file}', [DokumenController::class, 'KRS']);
            Route::get('/transkrip-nilai/{file}', [DokumenController::class, 'transkripNilai']);
            Route::get('/bukti-seminar/{file}', [DokumenController::class, 'buktiSeminar']);
        });
        Route::prefix('/ta')->group(function () {
            Route::get('/proposal-ta/{file}', [DokumenController::class, 'proposalTA']);
        });
    });
});
Route::get('/verifikasi-email', [AuthController::class, 'verifyEmail']);
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
