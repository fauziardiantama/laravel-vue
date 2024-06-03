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
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SesiUjianController;
use App\Http\Controllers\JadwalPropTAController;
use App\Http\Controllers\KondisiMahasiswaController;
use App\Http\Controllers\TopikKmmController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\RencanaMagangController;
use App\Http\Controllers\StatusPendaftaranController;
use App\Http\Controllers\SuratJawabanController;
use App\Http\Controllers\SuratMagangController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\PembimbingTaController;
use App\Http\Controllers\BimbinganInstansiController;
use App\Http\Controllers\BimbinganDosenController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\ParameterNilaiBimbinganController;
use App\Http\Controllers\ParameterNilaiInstansiController;
use App\Http\Controllers\ParameterNilaiSeminarController;
use App\Http\Controllers\BobotNilaiController;
use App\Http\Controllers\NilaiBimbinganController;
use App\Http\Controllers\NilaiSeminarController;

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
//Route::get('/verify', [AuthController::class, 'verify']);

// Route::get('/testing-channel', function () {
    //     event(new TestingEvent());
    //     return 'Event has been sent!';
// });

// Route::middleware('auth:passport')->get('/tes', function (Request $request) {
    //     return response()->json([
    //         'message' => 'Hello World!',
    //         'user' => $request->user()
    //     ]);
// });

Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->get('/user', function (Request $request) {
    return response()->json([
        'auth' => $request->user()->token(),
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
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/{id}', [ItemController::class, 'show']);
            Route::get('/', [ItemController::class, 'index']);
            Route::post('/', [ItemController::class, 'store']);
            Route::put('/{id}', [ItemController::class, 'update']);
            Route::delete('/{id}', [ItemController::class, 'destroy']);
        });
    }); //O
    Route::prefix('/dokumen_registrasi')->group(function () {
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [DokumenRegistrasiController::class, 'index']);
            Route::get('/mahasiswa', [DokumenRegistrasiController::class, 'indexWithMahasiswa']);
        });
        Route::middleware(['auth:passport','role:mahasiswa,admin'])->group(function () {
            Route::post('/', [DokumenRegistrasiController::class, 'store']);
            Route::put('/{nim}', [DokumenRegistrasiController::class, 'destroy']);
        });
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/{nim}', [DokumenRegistrasiController::class, 'show']);
            Route::get('{nim}/mahasiswa', [DokumenRegistrasiController::class, 'showWithMahasiswa']);
        });
    }); //O
    Route::prefix('/mahasiswa')->group(function () {
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [MahasiswaController::class, 'index']);
            Route::get('/search', [MahasiswaController::class, 'search']);
            Route::get('/{nim}', [MahasiswaController::class, 'show']);
            Route::put('/{nim}/aktif', [MahasiswaController::class, 'aktivasi']);
            Route::put('/{nim}/reject', [MahasiswaController::class, 'reject']);
            Route::put('/{nim}', [MahasiswaController::class, 'update']);
            Route::delete('/{nim}', [MahasiswaController::class, 'destroy']);
        });
    }); //O
    Route::prefix('/dosen')->group(function () {
        Route::middleware(['auth:passport', 'role:admin,mahasiswa'])->group(function () {
            Route::get('/all', [DosenController::class, 'indexAll']);
            Route::get('/all/{id_topik}', [DosenController::class, 'indexAllTopik']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [DosenController::class, 'index']);
            Route::get('/search', [DosenController::class, 'search']);
            Route::get('/magang', [DosenController::class, 'indexMagang']);
            Route::post('/', [DosenController::class, 'store']);
            Route::delete('/{nik}', [DosenController::class, 'destroy']);
        });
        Route::middleware(['auth:passport','role:admin,dosen'])->group(function () {
            Route::get('/{nik}', [DosenController::class, 'show']);
            Route::get('/{nik}/magang', [DosenController::class, 'showMagang']);
            Route::put('/{nik}', [DosenController::class, 'update']);
        });

  
    }); //--
    Route::prefix('/tahun')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/', [TahunController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/{tahun}/magang', [TahunController::class, 'showMagang']);
            Route::post('/', [TahunController::class, 'store']);
            Route::delete('/{tahun}', [TahunController::class, 'destroy']);
        });
    }); //O
    Route::prefix('/topik')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/', [TopikKmmController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:dosen'])->group(function () {
            Route::get('/mine', [TopikKmmController::class, 'indexMine']);
            Route::put('/assign', [TopikKmmController::class, 'assign']);
            Route::put('/unassign', [TopikKmmController::class, 'unassign']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/{id_topik}/magang', [TopikKmmController::class, 'showMagang']);
            Route::get('/{id_topik}/dosen', [TopikKmmController::class, 'showDosen']);
            Route::post('/', [TopikKmmController::class, 'store']);
            Route::put('/{id}', [TopikKmmController::class, 'update']);
            Route::delete('/{id}', [TopikKmmController::class, 'destroy']);
        });
    }); //--
    Route::prefix('/instansi')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin','active'])->group(function () {
            Route::get('/', [InstansiController::class, 'index']);
            Route::get('/search', [InstansiController::class, 'search']);
            Route::get('/all', [InstansiController::class, 'indexAll']);
            Route::post('/', [InstansiController::class, 'store']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/{id}/magang', [InstansiController::class, 'showMagang']);
            Route::put('/{id}', [InstansiController::class, 'update']);
            Route::put('/{id}/approve', [InstansiController::class, 'approve']);
            Route::put('/{id}/reject', [InstansiController::class, 'reject']);
            Route::delete('/{id}', [InstansiController::class, 'destroy']);
        });
    }); //--
    Route::prefix('/kondisi_mahasiswa')->group(function () {
        Route::middleware(['auth:passport','role:admin','active'])->group(function () {
            Route::get('/', [KondisiMahasiswaController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::post('/', [KondisiMahasiswaController::class, 'store']);
            Route::put('/{id}', [KondisiMahasiswaController::class, 'update']);
            Route::delete('/{id}', [KondisiMahasiswaController::class, 'destroy']);
        });
    }); //--
    Route::prefix('/magang')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin','active'])->group(function () {
            Route::get('/', [MagangController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:mahasiswa','active'])->group(function () {
            Route::prefix('/add')->group(function () {
                Route::post('/first_step', [MagangController::class, 'firstStep']);
                Route::put('/first_step', [MagangController::class, 'putFirstStep']);
                Route::put('/first_step/delete', [MagangController::class, 'deleteFirstStep']);
                Route::post('/second_step', [MagangController::class, 'secondStep']);
                Route::put('/second_step', [MagangController::class, 'putSecondStep']);
                Route::put('/second_step/delete', [MagangController::class, 'deleteSecondStep']);
                Route::post('/third_step', [MagangController::class, 'thirdStep']);
                Route::put('/third_step/{minggu}', [MagangController::class, 'putThirdStep']);
                Route::put('/third_step', [MagangController::class, 'deleteThirdStep']);
                Route::post('/fourth_step', [MagangController::class, 'fourthStep']);
                Route::put('/fourth_step', [MagangController::class, 'putFourthStep']);
                Route::put('/fourth_step/delete', [MagangController::class, 'deleteFourthStep']);
            });
            Route::prefix('/bimbingan')->group(function () {
                Route::prefix('/instansi')->group(function () {
                    Route::get('/', [BimbinganInstansiController::class, 'index']);
                    Route::post('/', [BimbinganInstansiController::class, 'store']);
                    Route::put('/{id}', [BimbinganInstansiController::class, 'update']);
                    Route::delete('/{id}', [BimbinganInstansiController::class, 'destroy']);
                });
                Route::prefix('/dosen')->group(function () {
                    Route::get('/', [BimbinganDosenController::class, 'index']);
                    Route::post('/', [BimbinganDosenController::class, 'store']);
                    Route::put('/{id}', [BimbinganDosenController::class, 'update']);
                    Route::delete('/{id}', [BimbinganDosenController::class, 'destroy']);
                });
            });
        });
        Route::middleware(['auth:passport','role:dosen,admin'])->group(function () {
            Route::get('/search', [MagangController::class, 'search']);
            Route::prefix('/{id}')->group(function () {
                Route::put('/approve', [MagangController::class, 'approve']);
                Route::put('/reject', [MagangController::class, 'reject']);
                Route::prefix('/bimbingan')->group(function () {
                    Route::prefix('/instansi')->group(function () {
                        Route::get('/', [BimbinganInstansiController::class, 'show']);
                        Route::put('/{id_bimbingan}/approve', [BimbinganInstansiController::class, 'approve']);
                        Route::put('/{id_bimbingan}/reject', [BimbinganInstansiController::class, 'reject']);
                    });
                    Route::prefix('/dosen')->group(function () {
                        Route::get('/', [BimbinganDosenController::class, 'show']);
                        Route::put('/{id_bimbingan}/approve', [BimbinganDosenController::class, 'approve']);
                        Route::put('/{id_bimbingan}/reject', [BimbinganDosenController::class, 'reject']);
                    });
                });
            });
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::post('/', [MagangController::class, 'store']);
            Route::prefix('/{id}')->group(function () {
                Route::get('/', [MagangController::class, 'show']);
                Route::put('/', [MagangController::class, 'update']);
                Route::delete('/', [MagangController::class, 'destroy']);
                Route::post('/instansi/approve', [MagangController::class, 'approveJawaban']);
                Route::post('/instansi/reject', [MagangController::class, 'rejectJawaban']);
            });
        });
    });
    Route::prefix('/progres')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin','active'])->group(function () {
            Route::get('/', [ProgresController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/{id}/magang', [ProgresController::class, 'showMagang']);
            Route::put('/{id}', [ProgresController::class, 'update']);
        });
    }); 
    Route::prefix('/rencana_magang')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin','active'])->group(function () {
            Route::get('/', [RencanaMagangController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::post('/', [RencanaMagangController::class, 'store']);
            Route::put('/{id}', [RencanaMagangController::class, 'update']);
            Route::delete('/{id}', [RencanaMagangController::class, 'destroy']);
        });
    }); 
    Route::prefix('/informasi')->group(function () {
        Route::get('/', [InformasiController::class, 'index']);
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/all', [InformasiController::class, 'indexPagination']);
            Route::get('/search', [InformasiController::class, 'search']);
            Route::post('/', [InformasiController::class, 'store']);
            Route::post('/{id}/update', [InformasiController::class, 'update']);
            Route::put('/{id}/publish', [InformasiController::class, 'publish']);
            Route::put('/{id}/unpublish', [InformasiController::class, 'unpublish']);
            Route::delete('/{id}', [InformasiController::class, 'destroy']);
        });
    });
    Route::prefix('/status_pendaftaran')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/', [StatusPendaftaranController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/{id}/magang', [StatusPendaftaranController::class, 'showMagang']);
            Route::post('/', [StatusPendaftaranController::class, 'store']);
            Route::put('/{id}', [StatusPendaftaranController::class, 'update']);
            Route::delete('/{id}', [StatusPendaftaranController::class, 'destroy']);
        });
    });
    Route::prefix('/surat')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,admin','active'])->group(function () {
            Route::get('/', [SuratMagangController::class, 'indexAll']); //get 3 surat
        });
    });
    Route::prefix('/surat_jawaban')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,admin','active'])->group(function () {
            Route::get('/', [SuratJawabanController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:mahasiswa','active'])->group(function () {
            Route::post('/', [SuratJawabanController::class, 'store']);
            Route::post('/update', [SuratJawabanController::class, 'update']);
            Route::delete('/', [SuratJawabanController::class, 'destroy']);
        });

        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/all', [SuratJawabanController::class, 'indexPaginate']);
            Route::get('/all/search', [SuratJawabanController::class, 'search']);
            Route::get('/all/{id}/dokumen', [SuratJawabanController::class, 'dokumen']);
        });
    });
    Route::prefix('/surat_magang')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa','active'])->group(function () {
            Route::get('/generate_serah_terima', [SuratMagangController::class, 'generateSerahTerima']);
        });
        Route::middleware(['auth:passport','role:mahasiswa,admin','active'])->group(function () {
            Route::get('/', [SuratMagangController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:mahasiswa,admin','active'])->group(function () {
            //Route::post('/pengantar', [SuratMagangController::class, 'storePengantar']);
            //Route::post('/serah_terima', [SuratMagangController::class, 'storeSerahTerima']);
            Route::post('/pengantar/update', [SuratMagangController::class, 'updatePengantar']);
            Route::post('/serah_terima/update', [SuratMagangController::class, 'updateSerahTerima']);
            Route::delete('/pengantar', [SuratMagangController::class, 'destroyPengantar']);
            Route::delete('/serah_terima', [SuratMagangController::class, 'destroySerahTerima']);
        });

        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/all', [SuratMagangController::class, 'indexPaginate']);
            Route::get('/all/search', [SuratMagangController::class, 'search']);
            Route::get('/all/{id}/dokumen', [SuratMagangController::class, 'dokumen']);
        });
    });
    Route::prefix('/program_studi')->group(function () {
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [ProgramStudiController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::post('/', [ProgramStudiController::class, 'store']);
            Route::put('/{id}', [ProgramStudiController::class, 'update']);
            Route::delete('/{id}', [ProgramStudiController::class, 'destroy']);
        });
    });
    Route::prefix('/fakultas')->group(function () {
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [FakultasController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::post('/', [FakultasController::class, 'store']);
            Route::put('/{id}', [FakultasController::class, 'update']);
            Route::delete('/{id}', [FakultasController::class, 'destroy']);
        });
    });
    Route::prefix('/seminar')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,admin,dosen'])->group(function () {
            Route::get('/', [SeminarController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:dosen'])->group(function () {
            Route::get('/penguji', [SeminarController::class, 'listUji']);
        });
        Route::middleware(['auth:passport','role:mahasiswa'])->group(function () {
            Route::post('/', [SeminarController::class, 'store']);
            Route::post('/update', [SeminarController::class, 'update']);
            Route::delete('/', [SeminarController::class, 'destroy']);
        });
        Route::middleware(['auth:passport','role:admin,dosen'])->group(function () {
            Route::get('/{id}', [SeminarController::class, 'show']);
            Route::put('/{id}/tgl_seminar', [SeminarController::class, 'tglSeminar']);
            Route::put('/{id}/approve', [SeminarController::class, 'approve']);
            Route::put('/{id}/reject', [SeminarController::class, 'reject']);
            Route::put('/{id}/ruangan', [SeminarController::class, 'ruangan']);
            Route::put('/{id}/penguji', [SeminarController::class, 'penguji']);
            Route::put('/{id}/penguji/remove', [SeminarController::class, 'removePenguji']);
        });
    });
    Route::prefix('/nilai')->group(function () {
        Route::prefix('/parameter')->group(function () {
            Route::middleware(['auth:passport','role:admin,dosen,mahasiswa'])->get('/bimbingan/year/{tahun}', [ParameterNilaiBimbinganController::class, 'year']);
            Route::middleware(['auth:passport','role:admin,dosen,mahasiswa'])->get('/seminar/year/{tahun}', [ParameterNilaiSeminarController::class, 'year']);
            Route::middleware(['auth:passport','role:admin'])->group(function () {
                Route::prefix('/bimbingan')->group(function () {
                    Route::get('/', [ParameterNilaiBimbinganController::class, 'index']);
                    Route::post('/', [ParameterNilaiBimbinganController::class, 'store']);
                    Route::put('/{id}', [ParameterNilaiBimbinganController::class, 'update']);
                    Route::delete('/{id}', [ParameterNilaiBimbinganController::class, 'destroy']);
                });
                Route::prefix('/instansi')->group(function () {
                    Route::get('/', [ParameterNilaiInstansiController::class, 'index']);
                    Route::post('/', [ParameterNilaiInstansiController::class, 'store']);
                    Route::put('/{id}', [ParameterNilaiInstansiController::class, 'update']);
                    Route::delete('/{id}', [ParameterNilaiInstansiController::class, 'destroy']);
                });
                Route::prefix('/seminar')->group(function () {
                    Route::get('/', [ParameterNilaiSeminarController::class, 'index']);
                    Route::post('/', [ParameterNilaiSeminarController::class, 'store']);
                    Route::put('/{id}', [ParameterNilaiSeminarController::class, 'update']);
                    Route::delete('/{id}', [ParameterNilaiSeminarController::class, 'destroy']);
                });
            });
        });
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/bimbingan', [NilaiBimbinganController::class, 'index']);
            Route::get('/seminar', [NilaiSeminarController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:dosen'])->group(function () {  
            Route::get('/bimbingan/{id_magang}', [NilaiBimbinganController::class, 'magang']);
            Route::post('/bimbingan', [NilaiBimbinganController::class, 'store']);
            Route::get('/seminar/{id_magang}', [NilaiSeminarController::class, 'magang']);
            Route::post('/seminar', [NilaiSeminarController::class, 'store']);
            Route::delete('/seminar/change/{id_nilai_seminar}', [NilaiSeminarController::class, 'destroy']);
        });
        Route::prefix('/bobot')->group(function () {
            Route::middleware(['auth:passport','role:admin'])->group(function () {
                Route::get('/', [BobotNilaiController::class, 'index']);
                Route::post('/', [BobotNilaiController::class, 'store']);
                Route::put('/{id}', [BobotNilaiController::class, 'update']);
                Route::delete('/{id}', [BobotNilaiController::class, 'destroy']);
            });
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
        Route::middleware(['auth:passport','role:admin'])->group(function () {
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
        Route::middleware(['auth:passport', 'role:admin,mahasiswa'])->group(function () {
            Route::get('/all', [DosenController::class, 'indexAll']);
            Route::get('/all/{id_topik}', [DosenController::class, 'indexAllTopik']);
        });

        Route::middleware(['auth:passport','role:admin,dosen'])->group(function () {
            Route::get('/{nik}', [DosenController::class, 'show']);
            Route::put('/{nik}', [DosenController::class, 'update']);
        });

        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [DosenController::class, 'index']);
            Route::post('/', [DosenController::class, 'store']);
            Route::delete('/{nik}', [DosenController::class, 'destroy']);
        });
    }); //--
    Route::prefix('/tahun')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin'])->group(function () {
            Route::get('/', [TahunController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/{tahun}/proposal_ta', [TahunController::class, 'showProposalTA']);
            Route::post('/', [TahunController::class, 'store']);
            Route::delete('/{tahun}', [TahunController::class, 'destroy']);
        });
    });
    Route::prefix('/proposal_ta')->group(function () {
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/all', [ProposalTAController::class, 'indexAll']);
            Route::put('/{nim}/approved', [ProposalTAController::class, 'approved']);
            Route::put('/{nim}/rejected', [ProposalTAController::class, 'rejected']);
        });
        Route::middleware(['auth:passport','role:mahasiswa,dosen,admin','active'])->group(function () {
            Route::get('/', [ProposalTAController::class, 'index']);
            Route::get('/{nim}', [ProposalTAController::class, 'show']);
        });
        Route::middleware(['auth:passport','role:mahasiswa,admin','active'])->group(function () {
            Route::post('/', [ProposalTAController::class, 'store']);
            Route::post('/{nim}/update', [ProposalTAController::class, 'update']);
            Route::delete('/{nim}', [ProposalTAController::class, 'destroy']);
        });
    });
    Route::prefix('/bimbingan')->group(function () {
        Route::middleware(['auth:passport','role:mahasiswa','active'])->group(function () {
            Route::get('/', [PembimbingTaController::class, 'index']);
            Route::post('/link', [PembimbingTaController::class, 'storeLink']);
            Route::put('/unlink', [PembimbingTaController::class, 'destroyLink']);
            Route::prefix('/detail')->group(function () {
                Route::get('/', [PembimbingTaController::class, 'indexDetail']);
                Route::post('/', [PembimbingTaController::class, 'storeDetail']);
                Route::put('/{id}', [PembimbingTaController::class, 'updateDetail']);
                Route::delete('/{id}', [PembimbingTaController::class, 'destroyDetail']);
            });
        });
        Route::middleware(['auth:passport','role:admin,dosen','active'])->group(function () {
            Route::get('/all', [BimbinganDosenController::class, 'indexTa']);
            Route::prefix('/{nim}')->group(function () {
                Route::get('/', [PembimbingTaController::class, 'show']);
                Route::prefix('/detail')->group(function () {
                    Route::get('/', [PembimbingTaController::class, 'showDetail']);
                    Route::put('/{id}/approve', [PembimbingTaController::class, 'approveDetail']);
                    Route::put('/{id}/reject', [PembimbingTaController::class, 'rejectDetail']);
                });
            });
        });
    });
    Route::prefix('/ruangan')->group(function () {
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [RuanganController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::post('/', [RuanganController::class, 'store']);
            Route::put('/{id}', [RuanganController::class, 'update']);
            Route::delete('/{id}', [RuanganController::class, 'destroy']);
        });
    });
    Route::prefix('/sesi_ujian')->group(function () {
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::get('/', [SesiUjianController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::post('/', [SesiUjianController::class, 'store']);
            Route::put('/{id}', [SesiUjianController::class, 'update']);
            Route::delete('/{id}', [SesiUjianController::class, 'destroy']);
        });
    });
    Route::prefix('/jadwal_proposal_ta')->group(function () {
        Route::middleware(['auth:passport','role:admin,dosen,mahasiswa','active'])->group(function () {
            Route::get('/', [JadwalPropTAController::class, 'index']);
        });
        Route::middleware(['auth:passport','role:admin'])->group(function () {
            Route::post('/', [JadwalPropTAController::class, 'store']);
            Route::put('/{id}', [JadwalPropTAController::class, 'update']);
            Route::delete('/{id}', [JadwalPropTAController::class, 'destroy']);
            Route::prefix('/{id}')->group(function () {
                Route::prefix('/add')->group(function () {
                    Route::post('/dosen', [JadwalPropTAController::class, 'addDosen']);
                    Route::post('/mahasiswa', [JadwalPropTAController::class, 'addMahasiswa']);
                });
                Route::prefix('/remove')->group(function () {
                    Route::delete('/dosen', [JadwalPropTAController::class, 'removeDosen']);
                    Route::delete('/mahasiswa', [JadwalPropTAController::class, 'removeMahasiswa']);
                });
            });
        });
    });
    //resource of ta
});

// Route::prefix('/generate')->group(function () {
    //     Route::prefix('/proposal_ta')->group(function () {
    //         Route::middleware(['auth:passport','role:mahasiswa,admin'])->group(function () {
    //             Route::get('/{nim}', [DokumenController::class, 'getProposal']);
    //         });
    //     });
    //     Route::prefix('/dokumen_registrasi')->group(function () {
    //         Route::middleware(['auth:passport','role:mahasiswa,admin'])->group(function () {
    //             Route::get('/{nim}', [DokumenController::class, 'getDokumenRegistrasi']);
    //         });
    //     });
// });