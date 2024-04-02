<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use App\Models\Dosen;
use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Http\Requests\FirstStepRequest;
use App\Http\Requests\SecondStepRequest;
use App\Http\Requests\ThirdStepRequest;
use App\Http\Requests\FourthStepRequest;
use App\Http\Requests\UpFirstStepRequest;
use App\Http\Requests\UpSecondStepRequest;
use App\Http\Requests\UpFourthStepRequest;
use App\Models\RencanaMagang;
use App\Events\Mgng;
use App\Events\RncMgng;
use App\Models\Mahasiswa;
use App\Models\Progres;


class MagangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->isMahasiswa()) {
            $magang = $request->user()->mahasiswa()->first()->magang()->with([
                'mahasiswa',
                'tahun',
                'topik',
                'instansi',
                'dosen',
                'progres',
                'rencanaMagang',
                'proposalTa'
            ])->first();
            if (!$magang) {
                return response()->json([
                    'message' => 'Data magang tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'message' => 'Berhasil menampilkan data magang',
                'data' => $magang
            ]);
        } else {
            $statusMapping = [
                "disetujui" => 1,
                "diterima" => 1,
                "ditolak" => -1,
                "menunggu" => 0
            ];

            $order = $request->order ?: 'id_magang';
            $sort = $request->sort ?: 'asc';
            $limit = $request->limit ?: 10;

            $query = Magang::query();

            if ($order=="nama") {
                $query->addSelect([
                    $order => Mahasiswa::select('nama')
                    ->whereColumn('mahasiswa.nim', 'magang.nim')
                    ->limit(1)
                ]);
                $query->orderBy($order, $sort);
            } else if ($order=="instansi") {
                $query->addSelect([
                    $order => Instansi::select('nama')
                    ->whereColumn('instansi.id_instansi', 'magang.id_instansi')
                    ->limit(1)
                ]);
                $query->orderBy($order, $sort);
            } else if ($order=="progres") {
                $query->addSelect([
                    $order => Progres::select('progres')
                    ->whereColumn('progres.id_progres', 'magang.id_progres')
                    ->limit(1)
                ]);
                $query->orderBy($order, $sort);
            } else {
                $query->orderBy($order, $sort);
            }

            if (array_key_exists(strtolower($request->status), $statusMapping)) {
                $query->where('status_dosen', $statusMapping[strtolower($request->status)]);
            } else {
                $query->where(function ($query) {

                    $query->where('tahun', 'like', '%'.request()->kueri.'%')
                    ->orWhereHas('mahasiswa', function ($query) {
                        $query->where('mahasiswa.nim', 'like', '%'.request()->kueri.'%')
                        ->orWhere('mahasiswa.nama', 'like', '%'.request()->kueri.'%')
                        ->orWhere('mahasiswa.email', 'like', '%'.request()->kueri.'%')
                        ->orWhere('mahasiswa.no_telp', 'like', '%'.request()->kueri.'%');
                    })
                    ->orWhereHas('topik', function ($query) {
                        $query->where('topik_kmm.nama_topik', 'like', '%'.request()->kueri.'%');    
                    })
                    ->orWhereHas('instansi', function ($query) {
                        $query->where('instansi.nama', 'like', '%'.request()->kueri.'%')
                        ->orWhere('instansi.no_telp', 'like', '%'.request()->kueri.'%')
                        ->orWhere('instansi.web', 'like', '%'.request()->kueri.'%');
                    })
                    ->orWhereHas('dosen', function ($query) {
                        $query->where('dosen.nik', 'like', '%'.request()->kueri.'%')
                        ->orWhere('dosen.nama', 'like', '%'.request()->kueri.'%')
                        ->orWhere('dosen.email', 'like', '%'.request()->kueri.'%');
                    })
                    ->orWhereHas('progres', function ($query) {
                        $query->where('progres.progres', 'like', '%'.request()->kueri.'%');
                    });

                });
            }
            //if dosen
            if ($request->user()->isDosen()) {
                $user = $request->user()->dosen()->first();
                $query->where('id_dosen', $user->id_dosen);
            }
            $magang = $query->with([
                'mahasiswa',
                'tahun',
                'topik',
                'instansi',
                'dosen',
                'progres',
                'rencanaMagang',
                'proposalTa'
            ])->paginate($limit);

            return response()->json([
                'message' => 'Berhasil menampilkan data magang',
                'data' => $magang
            ]);
        }
        return response()->json([
            'message' => 'Unauthorized probably'
        ], 401);
        
    }

    public function search(Request $request)
    {
        $statusMapping = [
            "disetujui" => 1,
            "diterima" => 1,
            "ditolak" => -1,
            "Ditolak" => -1,
            "Diterima" => 1,
            "Disetujui" => 1,
            "menunggu" => 0,
            "Menunggu" => 0,
        ];

        if (array_key_exists($request->kueri, $statusMapping)) {
            $magang = Magang::where('status_dosen', $statusMapping[$request->kueri]);
        } else {
            $magang = Magang::where(function ($query) use ($request) {
                $query->where('tahun', 'like', '%'.$request->kueri.'%')
                ->orWhereHas('mahasiswa', function ($query) use ($request) {
                    $query->where('mahasiswa.nim', 'like', '%'.$request->kueri.'%')
                    ->orWhere('mahasiswa.nama', 'like', '%'.$request->kueri.'%')
                    ->orWhere('mahasiswa.email', 'like', '%'.$request->kueri.'%')
                    ->orWhere('mahasiswa.no_telp', 'like', '%'.$request->kueri.'%');
                })
                ->orWhereHas('topik', function ($query) use ($request) {
                    $query->where('topik_kmm.nama_topik', 'like', '%'.$request->kueri.'%');    
                })
                ->orWhereHas('instansi', function ($query) use ($request) {
                    $query->where('instansi.nama', 'like', '%'.$request->kueri.'%')
                    ->orWhere('instansi.alamat', 'like', '%'.$request->kueri.'%')
                    ->orWhere('instansi.no_telp', 'like', '%'.$request->kueri.'%')
                    ->orWhere('instansi.web', 'like', '%'.$request->kueri.'%');
                })
                ->orWhereHas('dosen', function ($query) use ($request) {
                    $query->where('dosen.nik', 'like', '%'.$request->kueri.'%')
                    ->orWhere('dosen.nama', 'like', '%'.$request->kueri.'%')
                    ->orWhere('dosen.email', 'like', '%'.$request->kueri.'%');
                })
                ->orWhereHas('progres', function ($query) use ($request) {
                    $query->where('progres.progres', 'like', '%'.$request->kueri.'%');
                });
            });
        }

        if ($request->user()->isDosen()) {
            $user = $request->user()->dosen()->first();
            $magang = $magang->where('id_dosen', $user->id_dosen);
        }
        $magang = $magang->orderBy('nim', 'desc')
        ->with([
            'mahasiswa',
            'tahun',
            'topik',
            'instansi',
            'dosen',
            'progres',
            'rencanaMagang'
        ])
        ->paginate(10);

        return response()->json([
            'message' => 'Berhasil menampilkan dosen',
            'data' => $magang
        ]);
    }

    public function approve(Request $request, $id)
    {
        $magang = Magang::where('id_magang', $id)->first();
        if (!$request->user()->isAdmin()) {
            $user = $request->user()->dosen()->first();
            if ($magang->id_dosen != $user->id_dosen) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        }
        $magang->status_dosen = 1;
        $magang->id_progres = 4;
        $magang->save();
        $dosen = Dosen::where('id_dosen', $magang->id_dosen)->first();
        event(new Mgng("update", ["Admin","Duser.".$dosen->nik,"User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil menyetujui magang',
            'data' => $magang
        ]);
    }

    public function reject(Request $request, $id)
    {
        $magang = Magang::where('id_magang', $id)->first();
        if (!$request->user()->isAdmin()) {
            $user = $request->user()->dosen()->first();
            if ($magang->id_dosen != $user->id_dosen) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        }
        $magang->status_dosen = -1;
        $magang->id_progres = 3;
        $magang->save();
        $dosen = Dosen::where('id_dosen', $magang->id_dosen)->first();
        event(new Mgng("update", ["Admin","Duser.".$dosen->nik,"User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil menolak magang',
            'data' => $magang
        ]);
    }

    public function firstStep(FirstStepRequest $request)
    {
        $user = $request->user()->mahasiswa()->first();
        if ($user->magang()->exists()) {
            return response()->json([
                'message' => 'Anda sudah melakukan pendaftaran magang'
            ], 400);
        }
        $magang = new Magang();
        $magang->nim = $user->nim;
        $magang->tahun = $request->tahun;
        $magang->id_topik = $request->id_topik;
        $magang->status_pengajuan_instansi = 0;
        $magang->status_diterima_instansi = 0;
        $magang->status_dosen = 0;
        $magang->id_progres = 1;
        $magang->save();
        event(new Mgng("store", ["Admin","User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil melakukan pendaftaran magang',
            'data' => $magang
        ]);
    }

    public function putFirstStep(UpFirstStepRequest $request)
    {
        $user = $request->user()->mahasiswa()->first();
        if (!$user->magang()->exists()) {
            return response()->json([
                'message' => 'Data magang tidak ditemukan'
            ], 404);
        }
        $magang = $user->magang()->first();
        if ($magang->instansi()->exists()) {
            return response()->json([
                'message' => 'Tidak bisa mengubah data magang bila sudah menambahkan instansi'
            ], 400);
        }
        $magang->tahun = $request->tahun ?? $magang->tahun;
        $magang->id_topik = $request->id_topik ?? $magang->id_topik;
        $magang->status_pengajuan_instansi = 0;
        $magang->status_diterima_instansi = 0;
        $magang->status_dosen = 0;
        $magang->id_progres = 1;
        $magang->save();
        event(new Mgng("update", ["Admin","User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil mengubah data magang',
            'data' => $magang
        ]);
    }

    public function deleteFirstStep(Request $request)
    {
        $user = $request->user()->mahasiswa()->first();
        if (!$user->magang()->exists()) {
            return response()->json([
                'message' => 'Data magang tidak ditemukan'
            ], 404);
        }
        $magang = $user->magang()->first();
        if ($magang->instansi()->exists()) {
            return response()->json([
                'message' => 'Tidak bisa mengubah data magang bila sudah menambahkan instansi'
            ], 400);
        }
        $legacy = $magang;
        $magang->delete();
        event(new Mgng("destroy", ["Admin","User.".$magang->nim], true, $legacy));
        return response()->json([
            'message' => 'Berhasil menghapus data magang',
            'data' => $legacy
        ]);
    }

    public function secondStep(SecondStepRequest $request)
    {
        $user = $request->user()->mahasiswa()->first();
        if (!$user->magang()->exists()) {
            return response()->json([
                'message' => 'Anda belum melakukan pendaftaran magang'
            ], 400);
        }
        $magang = $user->magang()->first();
        if ($magang->rencanaMagang()->exists()) {
            return response()->json([
                'message' => 'Tidak bisa menambahkan instansi bila sudah menambahkan rencana magang'
            ], 400);
        }
        $instansi = Instansi::where('id_instansi', $request->id_instansi)->first();
        if ($instansi->status_instansi == 1) {
            $magang->id_progres = 2;
        } else {
            $magang->id_progres = 1;
        }
        $magang->id_instansi = $request->id_instansi;
        $magang->save();
        event(new Mgng("update", ["Admin","User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil mengajukan instansi magang',
            'data' => $magang
        ]);
    }

    public function putSecondStep(UpSecondStepRequest $request)
    {
        $user = $request->user()->mahasiswa()->first();
        if (!$user->magang()->exists()) {
            return response()->json([
                'message' => 'Anda belum melakukan pendaftaran magang'
            ], 400);
        }
        $magang = $user->magang()->first();
        if ($magang->rencanaMagang()->exists()) {
            return response()->json([
                'message' => 'Tidak bisa menambahkan instansi bila sudah menambahkan rencana magang'
            ], 400);
        }
        if ($request->id_instansi) {
            $instansi = Instansi::where('id_instansi', $request->id_instansi)->first();
            if ($instansi->status_instansi == 1) {
                $magang->id_progres = 2;
            } else {
                $magang->id_progres = 1;
            }
        }
        $magang->id_instansi = $request->id_instansi ?? $magang->id_instansi;
        $magang->save();
        event(new Mgng("update", ["Admin","User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil mengajukan instansi magang',
            'data' => $magang
        ]);
    }

    public function deleteSecondStep(Request $request)
    {
        $user = $request->user()->mahasiswa()->first();
        if (!$user->magang()->exists()) {
            return response()->json([
                'message' => 'Anda belum melakukan pendaftaran magang'
            ], 400);
        }
        $magang = $user->magang()->first();
        if ($magang->rencanaMagang()->exists()) {
            return response()->json([
                'message' => 'Tidak bisa menghapus instansi bila sudah menambahkan rencana magang'
            ], 400);
        }
        $magang->id_instansi = null;
        $magang->id_progres = 1;
        $magang->save();
        event(new Mgng("update", ["Admin","User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil menghapus instansi magang',
            'data' => $magang
        ]);
    }

    public function thirdStep(ThirdStepRequest $request)
    {
        $user = $request->user()->mahasiswa()->first();
        if (!$user->magang()->exists()) {
            return response()->json([
                'message' => 'Anda belum melakukan pendaftaran magang'
            ], 400);
        }
        $magang = $user->magang()->first();
        if (!$magang->instansi()->exists() || $magang->instansi()->first()->status_instansi != 1) {
            return response()->json([
                'message' => 'Magang belum disertai instansi yang disetujui'
            ], 400);
        }
        if ($magang->dosen()->exists() && $magang->status_dosen == 1) {
            return response()->json([
                'message' => 'Tidak bisa menambah rencana magang bila sudah ada pembimbing yang disetujui'
            ], 400);
        }
        $rencanaMagang = new RencanaMagang();
        $rencanaMagang->id_magang = $magang->id_magang;
        $rencanaMagang->minggu = 1;
        if ($magang->rencanaMagang()->exists()) {
            $lastRencana = $magang->rencanaMagang()->orderBy('minggu', 'desc')->first();
            $rencanaMagang->minggu = $lastRencana->minggu + 1;
            if ($lastRencana->minggu == 5) {
                $magang->id_progres = 3;
                $magang->save();
            }
        }
        $rencanaMagang->kegiatan = $request->kegiatan;
        $rencanaMagang->save();
        if ($magang->id_dosen != null) {
            $dosen = Dosen::where('id_dosen', $magang->id_dosen)->first();
            event(new Mgng("update", ["Duser.".$dosen->nik], false, $magang));
            event(new RncMgng("store", ["Duser.".$dosen->nik], false, $rencanaMagang));
        }
        event(new Mgng("update", ["Admin","User.".$magang->nim], false, $magang));
        event(new RncMgng("store", ["Admin","User.".$magang->nim], false, $rencanaMagang));
        return response()->json([
            'message' => 'Berhasil menambahkan rencana magang',
            'data' => $magang->rencanaMagang()->get()
        ]);
    }

    public function putThirdStep(Request $request, $minggu)
    {
        $user = $request->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        if ($magang) {
            if (!$magang->instansi()->exists() || $magang->instansi()->first()->status_instansi != 1) {
                return response()->json([
                    'message' => 'Magang belum disertai instansi yang disetujui'
                ], 400);
            }
            if ($magang->dosen()->exists() && $magang->status_dosen == 1) {
                return response()->json([
                    'message' => 'Tidak bisa menambah rencana magang bila sudah ada pembimbing yang disetujui'
                ], 400);
            }
            if ($magang->rencanaMagang()->exists()) {
                $rencanaMagang = $magang->rencanaMagang()->where('minggu', $minggu)->first();
                $rencanaMagang->kegiatan = $request->kegiatan ?? $rencanaMagang->kegiatan;
                $rencanaMagang->save();
                if ($magang->id_dosen != null) {
                    $dosen = Dosen::where('id_dosen', $magang->id_dosen)->first();
                    event(new Mgng("update", ["Duser.".$dosen->nik], false, $magang));
                    event(new RncMgng("update", ["Duser.".$dosen->nik], false, $rencanaMagang));
                }
                event(new Mgng("update", ["Admin","User.".$magang->nim], false, $magang));
                event(new RncMgng("update", ["Admin","User.".$magang->nim], false, $rencanaMagang));
                return response()->json([
                    'message' => 'Berhasil mengubah rencana magang',
                    'data' => $magang->rencanaMagang()->get()
                ]);
            } else {
                return response()->json([
                    'message' => 'Rencana magang tidak ditemukan'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Data magang tidak ditemukan'
            ], 404);
        }
    }

    public function deleteThirdStep(Request $request)
    {
        $user = $request->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        if ($magang) {
            if (!$magang->instansi()->exists() || $magang->instansi()->first()->status_instansi != 1) {
                return response()->json([
                    'message' => 'Magang belum disertai instansi yang disetujui'
                ], 400);
            }
            if ($magang->dosen()->exists() && $magang->status_dosen == 1) {
                return response()->json([
                    'message' => 'Tidak bisa menambah rencana magang bila sudah ada pembimbing yang disetujui'
                ], 400);
            }
            if ($magang->rencanaMagang()->exists()) {
                $rencanaMagang = $magang->rencanaMagang()->orderBy('minggu', 'desc')->first();
                $rencanaMagang->delete();
                $dosen = null;
                if ($magang->id_dosen != null) {
                    $dosen = Dosen::where('id_dosen', $magang->id_dosen)->first();
                }
                if ($dosen != null) {
                    event(new Mgng("update", ["Duser.".$dosen->nik], false, $magang));
                }
                event(new Mgng("update", ["Admin","User.".$magang->nim], false, $magang));
                if ($magang->rencanaMagang()->exists()) {
                    if ($dosen != null) {
                        event(new RncMgng("destroy", ["Duser.".$dosen->nik], false, $magang->rencanaMagang()->get()));
                    }
                    event(new RncMgng("destroy", ["Admin","User.".$magang->nim], false, $magang->rencanaMagang()->get()));
                    return response()->json([
                        'message' => 'Berhasil menghapus rencana magang',
                        'data' => $magang->rencanaMagang()->get()
                    ]);
                } else {
                    $magang->id_progres = 2;
                    $magang->save();
                    if ($dosen != null) {
                        event(new RncMgng("destroy", ["Duser.".$dosen->nik], true, null));
                    }
                    event(new RncMgng("destroy", ["Admin","User.".$magang->nim], true, null));
                    return response()->json([
                        'message' => 'Berhasil menghapus rencana magang',
                        'data' => null
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Rencana magang tidak ditemukan'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Data magang tidak ditemukan'
            ], 404);
        }
    }

    public function fourthStep(FourthStepRequest $request)
    {
        $user = $request->user()->mahasiswa()->first();
        if (!$user->magang()->exists()) {
            return response()->json([
                'message' => 'Anda belum melakukan pendaftaran magang'
            ], 400);
        }
        $magang = $user->magang()->first();
        if (!$magang->rencanaMagang()->exists() || $magang->rencanaMagang()->count() < 5) {
            return response()->json([
                'message' => 'Anda belum menambahkan rencana magang minimal 5'
            ], 400);
        }
        if ($magang->dosen()->exists()) {
            return response()->json([
                'message' => 'Tidak bisa menambah pembimbing bila sudah ada'
            ], 400);
        }
        $dosen = Dosen::where('id_dosen', $request->id_dosen)->first();
        if (!$dosen->topik()->where('topik_kmm.id_topik', $magang->id_topik)->exists()) {
            return response()->json([
                'message' => 'Topik magang tidak sesuai dengan topik dosen'
            ], 400);
        }
        $magang->id_dosen = $request->id_dosen;
        $magang->status_dosen = 0;
        $magang->id_progres = 3;
        $magang->save();
        event(new Mgng("update", ["Admin","Duser.".$dosen->nik,"User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil mengajukan magang',
            'data' => $magang
        ]);
    }

    public function putFourthStep(UpFourthStepRequest $request)
    {
        $user = $request->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        if ($magang) {
            if (!$magang->rencanaMagang()->exists() || $magang->rencanaMagang()->count() < 5) {
                return response()->json([
                    'message' => 'Anda belum menambahkan rencana magang minimal 5'
                ], 400);
            }
            if ($magang->dosen()->exists() && $magang->status_dosen == 1) {
                return response()->json([
                    'message' => 'Tidak bisa mengubah pembimbing bila sudah disetujui'
                ], 400);
            }
            $dosen = null;
            if ($request->id_dosen) {
                $dosen = Dosen::where('id_dosen', $request->id_dosen)->first();
                if (!$dosen->topik()->where('topik_kmm.id_topik', $magang->id_topik)->exists()) {
                    return response()->json([
                        'message' => 'Topik magang tidak sesuai dengan topik dosen'
                    ], 400);
                }
            }
            $magang->id_dosen = $request->id_dosen ?? $magang->id_dosen;
            $magang->status_dosen = 0;
            $magang->save();
            if ($dosen != null) {
                event(new Mgng("update", ["Duser.".$dosen->nik], false, $magang));
            }
            event(new Mgng("update", ["Admin","User.".$magang->nim], false, $magang));
            return response()->json([
                'message' => 'Berhasil mengubah data magang',
                'data' => $magang
            ]);
        } else {
            return response()->json([
                'message' => 'Data magang tidak ditemukan'
            ], 404);
        }
    }

    public function deleteFourthStep(Request $request)
    {
        $user = $request->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        if ($magang) {
            if (!$magang->rencanaMagang()->exists() || $magang->rencanaMagang()->count() < 5) {
                return response()->json([
                    'message' => 'Anda belum menambahkan rencana magang minimal 5'
                ], 400);
            }
            $dosen = $magang->dosen()->first();
            if ($magang->dosen()->exists() && $magang->status_dosen == 1) {
                return response()->json([
                    'message' => 'Tidak bisa menghapus pembimbing bila sudah disetujui'
                ], 400);
            }
            $magang->id_dosen = null;
            $magang->status_dosen = 0;
            $magang->id_progres = 3;
            $magang->save();
            event(new Mgng("update", ["Admin","User.".$magang->nim], false, $magang));
            event(new Mgng("destroy", ["Duser.".$dosen->nik], true, null));
            return response()->json([
                'message' => 'Berhasil menghapus data magang',
                'data' => $magang
            ]);
        } else {
            return response()->json([
                'message' => 'Data magang tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $magang = Magang::where('id_magang', $id)->with([
            'mahasiswa',
            'tahun',
            'topik',
            'instansi',
            'dosen',
            'progres'
        ])->first();
        if ($magang) {
            return response()->json([
                'message' => 'Detail data magang',
                'data' => $magang
            ]);
        } else {
            return response()->json([
                'message' => 'Data magang tidak ditemukan'
            ], 404);
        }
    }
    
    public function approveJawaban(Request $request, $id)
    {
        $magang = Magang::where('id_magang', $id)->first();
        if (!$request->user()->isAdmin()) {
            $user = $request->user()->dosen()->first();
            if ($magang->id_dosen != $user->id_dosen) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        }
        //if magang->status_pengajuan_instansi != 1 then return error
        if ($magang->status_pengajuan_instansi != 1) {
            return response()->json([
                'message' => 'Magang belum disetujui instansi'
            ], 400);
        }
        $magang->status_diterima_instansi = 1;
        $magang->save();
        $dosen = Dosen::where('id_dosen', $magang->id_dosen)->first();
        event(new Mgng("update", ["Admin","Duser.".$dosen->nik,"User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil menyetujui magang',
            'data' => $magang
        ]);
    }

    public function rejectJawaban(Request $request, $id)
    {
        $magang = Magang::where('id_magang', $id)->first();
        if (!$request->user()->isAdmin()) {
            $user = $request->user()->dosen()->first();
            if ($magang->id_dosen != $user->id_dosen) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        }
        //if magang->status_pengajuan_instansi != 1 then return error
        if ($magang->status_pengajuan_instansi != 1) {
            return response()->json([
                'message' => 'Magang belum disetujui instansi'
            ], 400);
        }
        $magang->status_diterima_instansi = -1;
        $magang->save();
        $dosen = Dosen::where('id_dosen', $magang->id_dosen)->first();
        event(new Mgng("update", ["Admin","Duser.".$dosen->nik,"User.".$magang->nim], false, $magang));
        return response()->json([
            'message' => 'Berhasil menolak magang',
            'data' => $magang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Magang $magang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Magang $magang)
    {
        //
    }
}
