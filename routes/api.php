<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DokumenRegistrasiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProposalTAController;
use App\Http\Controllers\TahunController;
use App\Events\TestingEvent;

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
Route::get('/verify', [AuthController::class, 'verify']);

Route::get('/testing-channel', function () {
    event(new TestingEvent());
    return 'Event has been sent!';
});

Route::middleware('auth:passport')->get('/tes', function (Request $request) {
    return response()->json([
        'message' => 'Hello World!',
        'user' => $request->user()
    ]);
});

Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->get('/user', function (Request $request) {
    return response()->json([
        'token' => $request->user()->token(),
        'user' => ($request->user()->isMahasiswa()) ? $request->user()->mahasiswa()->first() : (($request->user()->isDosen()) ? $request->user()->dosen()->first() : $request->user()->admin()->first())
    ]);
});

Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->post('/logout', [AuthController::class, 'logout']);

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
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/{id}', [ItemController::class, 'show']);
            Route::get('/', [ItemController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
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
    Route::prefix('/dokumen_registrasi')->group(function () {
        Route::middleware(['auth:passport','role:dosen,admin'])->group(function () {
            Route::get('/', [DokumenRegistrasiController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:mahasiswa,admin'])->group(function () {
            Route::post('/', [DokumenRegistrasiController::class, 'store']);
            Route::put('/{nim}', [DokumenRegistrasiController::class, 'destroy']);
        });
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/{nim}', [DokumenRegistrasiController::class, 'show']);
        });
    });
    Route::prefix('/mahasiswa')->group(function () {
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [MahasiswaController::class, 'index']);
            Route::get('/{nim}', [MahasiswaController::class, 'show']);
            Route::put('/{nim}/aktif', [MahasiswaController::class, 'aktivasi']);
            Route::put('/{nim}/reject', [MahasiswaController::class, 'reject']);
            Route::put('/{nim}', [MahasiswaController::class, 'update']);
            Route::delete('/{nim}', [MahasiswaController::class, 'destroy']);
        });
    });
    Route::prefix('/dosen')->group(function () {
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [DosenController::class, 'index']);
        });
    });
    Route::prefix('/tahun')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/', [TahunController::class, 'index']);
            Route::get('/{tahun}', [TahunController::class, 'show']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::post('/', [TahunController::class, 'store']);
            Route::put('/{tahun}', [TahunController::class, 'update']);
            Route::delete('/{tahun}', [TahunController::class, 'destroy']);
        });
    });
    Route::prefix('/proposal_ta')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/', [ProposalTAController::class, 'index']);
            Route::get('/{nim}', [ProposalTAController::class, 'show']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::put('/{nim}/approved', [ProposalTAController::class, 'approved']);
            Route::put('/{nim}/rejected', [ProposalTAController::class, 'rejected']);
        });
        Route::middleware(['auth:passport','role:mahasiswa,admin'])->group(function () {
            Route::post('/', [ProposalTAController::class, 'store']);
            Route::post('/{nim}/update', [ProposalTAController::class, 'update']);
            Route::delete('/{nim}', [ProposalTAController::class, 'destroy']);
        });
    });
    //resource of ta
});