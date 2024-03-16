<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\InformasiController;

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
Route::prefix('/kmm')->group(function () {
    Route::get('/informasi/{file}', [InformasiController::class, 'showDokumenInformasi']);
});
Route::prefix('/mahasiswa')->group(function () {
    Route::prefix('/dokumen-registrasi')->group(function () {
        Route::get('/kartu-mahasiswa/{token}/{file}', [DokumenController::class, 'showKartuMahasiswa']);
        Route::get('/krs/{token}/{file}', [DokumenController::class, 'showKRS']);
        Route::get('/transkrip-nilai/{token}/{file}', [DokumenController::class, 'showTranskripNilai']);
        Route::get('/bukti-seminar/{token}/{file}', [DokumenController::class, 'showBuktiSeminar']);
    });
    Route::prefix('/ta')->group(function () {
        Route::get('/proposal-ta/{token}/{file}', [DokumenController::class, 'showProposalTA']);
    });
    Route::prefix('/magang')->group(function () {
        Route::get('/template-serah-terima', [DokumenController::class, 'showTemplateSerahTerima']);
        Route::get('/surat-pengantar/{token}/{file}', [DokumenController::class, 'showSuratPengantar']);
        Route::get('/surat-serah-terima/{token}/{file}', [DokumenController::class, 'showSuratSerahTerima']);
        Route::get('/surat-serah-terima-2024/{file}', [DokumenController::class, 'showSuratSerahTerimaNew']);
        Route::get('/surat-jawaban/{token}/{file}', [DokumenController::class, 'showSuratJawaban']);
    });
});
Route::get('/verifikasi-email', [AuthController::class, 'verifyEmail']);
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
