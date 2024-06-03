<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use App\Models\Dosen;
use Illuminate\Http\Request;

class SeminarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->user()->isMahasiswa()) {
            $user = request()->user();
            $seminar = $user->mahasiswa()->first()->magang()->first()->seminar()->with('ruangan','penguji')->first();
            if ($seminar) {
                return response()->json([
                    'message' => 'Data seminar berhasil dimuat',
                    'data' => $seminar
                ]);
            } else {
                return response()->json([
                    'message' => 'Data seminar tidak ditemukan',
                    'data' => null
                ], 404);
            }
        } else {
            $statusMapping = [
                "disetujui" => 1,
                "diterima" => 1,
                "ditolak" => -1,
                "menunggu" => 0
            ];

            $order = request()->order ?: 'id_seminar';
            $sort = request()->sort ?: 'asc';
            $limit = request()->limit ?: 10;

            $query = Seminar::query();

            if ($order == "nim")
            {
                // If 'order' is 'nim', join the 'Magang' table on 'id_magang'
                $query->join('Magang', 'Seminar.id_magang', '=', 'Magang.id_magang')
                // Then join the 'Mahasiswa' table on 'nim'
                ->join('Mahasiswa', 'Magang.nim', '=', 'Mahasiswa.nim')
                // Select all columns from 'Seminar' and 'nama' from 'Mahasiswa'
                ->select('Seminar.*', 'Mahasiswa.nim')
                // Order the results by 'nama' from 'Mahasiswa'
                ->orderBy('Mahasiswa.nim', $sort);
            } else if ($order == "nama")
            {
                // If 'order' is 'nim', join the 'Magang' table on 'id_magang'
                $query->join('Magang', 'Seminar.id_magang', '=', 'Magang.id_magang')
                // Then join the 'Mahasiswa' table on 'nim'
                ->join('Mahasiswa', 'Magang.nim', '=', 'Mahasiswa.nim')
                // Select all columns from 'Seminar' and 'nama' from 'Mahasiswa'
                ->select('Seminar.*', 'Mahasiswa.nama')
                // Order the results by 'nama' from 'Mahasiswa'
                ->orderBy('Mahasiswa.nama', $sort);
            } else {
                $query->orderBy($order, $sort);
            }

            if (array_key_exists(strtolower(request()->kueri), $statusMapping)) {
                $query->where('status', $statusMapping[strtolower(request()->kueri)]);
            } else {
                $query->where(function ($query) {
                    $query->whereHas('magang', function ($query) {
                        $query->whereHas('mahasiswa', function ($query) {
                            $query->where('nama', 'like', '%'.request()->kueri.'%');
                        })
                        ->orWhereHas('dosen', function ($query) {
                            $query->where('nama', 'like', '%'.request()->kueri.'%');
                        })
                        ->orWhere('nim', 'like', '%'.request()->kueri.'%')
                        ->orWhere('tahun', 'like', '%'.request()->kueri.'%');
                    })
                    ->orWhere('judul_kmm', 'like', '%'.request()->kueri.'%')
                    ->orWhere('tgl_seminar', 'like', '%'.request()->kueri.'%');
                });
            }
            if (request()->user()->isDosen()) {
                $query->whereHas('magang', function ($query) {
                    $query->where('id_dosen', request()->user()->dosen()->first()->id_dosen);
                });
            }
            $seminar = $query->with([
                'magang' => function ($query) {
                    $query->with('mahasiswa');
                },
                'ruangan',
                'penguji'
            ])->paginate($limit);
            
            return response()->json([
                'message' => 'Data seminar berhasil dimuat',
                'data' => $seminar
            ]);
        }
    }

    public function listUji()
    {
            $statusMapping = [
                "disetujui" => 1,
                "diterima" => 1,
                "ditolak" => -1,
                "menunggu" => 0
            ];

            $order = request()->order ?: 'id_seminar';
            $sort = request()->sort ?: 'asc';
            $limit = request()->limit ?: 10;

            $query = Seminar::query();

            if ($order == "nim")
            {
                // If 'order' is 'nim', join the 'Magang' table on 'id_magang'
                $query->join('Magang', 'Seminar.id_magang', '=', 'Magang.id_magang')
                // Then join the 'Mahasiswa' table on 'nim'
                ->join('Mahasiswa', 'Magang.nim', '=', 'Mahasiswa.nim')
                // Select all columns from 'Seminar' and 'nama' from 'Mahasiswa'
                ->select('Seminar.*', 'Mahasiswa.nim')
                // Order the results by 'nama' from 'Mahasiswa'
                ->orderBy('Mahasiswa.nim', $sort);
            } else if ($order == "nama")
            {
                // If 'order' is 'nim', join the 'Magang' table on 'id_magang'
                $query->join('Magang', 'Seminar.id_magang', '=', 'Magang.id_magang')
                // Then join the 'Mahasiswa' table on 'nim'
                ->join('Mahasiswa', 'Magang.nim', '=', 'Mahasiswa.nim')
                // Select all columns from 'Seminar' and 'nama' from 'Mahasiswa'
                ->select('Seminar.*', 'Mahasiswa.nama')
                // Order the results by 'nama' from 'Mahasiswa'
                ->orderBy('Mahasiswa.nama', $sort);
            } else {
                $query->orderBy($order, $sort);
            }

            if (array_key_exists(strtolower(request()->kueri), $statusMapping)) {
                $query->where('status', $statusMapping[strtolower(request()->kueri)]);
            } else {
                $query->where(function ($query) {
                    $query->whereHas('magang', function ($query) {
                        $query->whereHas('mahasiswa', function ($query) {
                            $query->where('nama', 'like', '%'.request()->kueri.'%');
                        })
                        ->orWhereHas('dosen', function ($query) {
                            $query->where('nama', 'like', '%'.request()->kueri.'%');
                        })
                        ->orWhere('nim', 'like', '%'.request()->kueri.'%')
                        ->orWhere('tahun', 'like', '%'.request()->kueri.'%');
                    })
                    ->orWhere('judul_kmm', 'like', '%'.request()->kueri.'%')
                    ->orWhere('tgl_seminar', 'like', '%'.request()->kueri.'%');
                });
            }
               $query->whereHas('penguji', function ($query) {
                    $query->where('dosen.id_dosen', request()->user()->dosen()->first()->id_dosen);
                });
            $seminar = $query->with([
                'magang' => function ($query) {
                    $query->with('mahasiswa');
                },
                'ruangan',
                'penguji'
            ])->paginate($limit);
            
            return response()->json([
                'message' => 'Data seminar berhasil dimuat',
                'data' => $seminar
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = request()->user();
        $magang = $user->mahasiswa()->first()->magang()->first();
        $seminar = new Seminar();
        $seminar->id_magang = $magang->id_magang;
        $seminar->tgl_daftar = date('Y-m-d');
        $seminar->tgl_seminar = null;
        $seminar->judul_kmm = $request->judul_kmm;

        //save file draft_kmm, krs, daftar_hadir, foto, lembar_revisi, selesai_kmm
        $draft_kmm = $request->file('draft_kmm');
        $krs = $request->file('krs');
        $foto = $request->file('foto');

        $seminar->draft_kmm =  $magang->id_magang.'_'.$magang->nim.'_draft_kmm.pdf';
        $draft_kmm->storeAs('public/seminar/draft_kmm', $seminar->draft_kmm);

        $seminar->krs =  $magang->id_magang.'_'.$magang->nim.'_krs.pdf';
        $krs->storeAs('public/seminar/krs', $seminar->krs);

        $seminar->foto =  $magang->id_magang.'_'.$magang->nim.'_foto.png';
        $foto->storeAs('public/seminar/foto', $seminar->foto);

        $seminar->status = 0;

        if ($seminar->save()) {
            return response()->json([
                'message' => 'Data seminar berhasil disimpan',
                'data' => $seminar
            ]);
        } else {
            return response()->json([
                'message' => 'Data seminar gagal disimpan',
                'data' => null
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Seminar $seminar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seminar $seminar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = request()->user();
        $magang = $user->mahasiswa()->first()->magang()->first();
        $seminar = $magang->seminar()->first();
        $seminar->judul_kmm = $request->judul_kmm ? $request->judul_kmm : $seminar->judul_kmm;

        //save file draft_kmm, krs, daftar_hadir, foto, lembar_revisi, selesai_kmm
        if ($request->file('draft_kmm')) {
            $draft_kmm = $request->file('draft_kmm');
            $seminar->draft_kmm =  $magang->id_magang.'_'.$magang->nim.'_draft_kmm.pdf';
            $draft_kmm->storeAs('public/seminar/draft_kmm', $seminar->draft_kmm);
        }
        if ($request->file('krs')) {
            $krs = $request->file('krs');
            $seminar->krs =  $magang->id_magang.'_'.$magang->nim.'_krs.pdf';
            $krs->storeAs('public/seminar/krs', $seminar->krs);
        }
        if ($request->file('foto')) {
            $foto = $request->file('foto');
            $seminar->foto =  $magang->id_magang.'_'.$magang->nim.'_foto.png';
            $foto->storeAs('public/seminar/foto', $seminar->foto);
        }
        $seminar->status = 0;
        if ($seminar->save()) {
            return response()->json([
                'message' => 'Data seminar berhasil diubah',
                'data' => $seminar
            ]);
        } else {
            return response()->json([
                'message' => 'Data seminar gagal diubah',
                'data' => null
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $user = request()->user();
        $magang = $user->mahasiswa()->first()->magang()->first();
        $seminar = $magang->seminar()->first();
        //delete file draft_kmm, krs, daftar_hadir, foto, lembar_revisi, selesai_kmm
        unlink(storage_path('app/public/seminar/draft_kmm/'.$seminar->draft_kmm));
        unlink(storage_path('app/public/seminar/krs/'.$seminar->krs));
        unlink(storage_path('app/public/seminar/foto/'.$seminar->foto));
        
        if ($seminar->delete()) {
            return response()->json([
                'message' => 'Data seminar berhasil dihapus',
            ]);
        } else {
            return response()->json([
                'message' => 'Data seminar gagal dihapus',
                'data' => null
            ], 400);
        }
    }

    public function approve($id)
    {
        $seminar = Seminar::find($id);
        $seminar->status = 1;
        if ($seminar->save()) {
            $seminar = $seminar->where('id_seminar',$id)->with([
                'magang' => function ($query) {
                    $query->with('mahasiswa');
                },
                'ruangan',
                'penguji'
            ])->first();
            return response()->json([
                'message' => 'Data seminar berhasil disetujui',
                'data' => $seminar
            ]);
        } else {
            return response()->json([
                'message' => 'Data seminar gagal disetujui',
                'data' => null
            ], 400);
        }
    }

    public function reject($id)
    {
        $seminar = Seminar::find($id);
        $seminar->status = -1;
        if ($seminar->save()) {
            $seminar = $seminar->where('id_seminar',$id)->with([
                'magang' => function ($query) {
                    $query->with('mahasiswa');
                },
                'ruangan',
                'penguji'
            ])->first();
            return response()->json([
                'message' => 'Data seminar berhasil ditolak',
                'data' => $seminar
            ]);
        } else {
            return response()->json([
                'message' => 'Data seminar gagal ditolak',
                'data' => null
            ], 400);
        }
    }

    public function tglSeminar(Request $request, $id)
    {
        $seminar = Seminar::find($id);
        $seminar->tgl_seminar = $request->tgl_seminar;
        if ($seminar->save()) {
            $seminar = $seminar->where('id_seminar',$id)->with([
                'magang' => function ($query) {
                    $query->with('mahasiswa');
                },
                'ruangan',
                'penguji'
            ])->first();
            return response()->json([
                'message' => 'Tanggal seminar berhasil diubah',
                'data' => $seminar
            ]);
        } else {
            return response()->json([
                'message' => 'Tanggal seminar gagal diubah',
                'data' => null
            ], 400);
        }
    }

    public function ruangan(Request $request, $id)
    {
        $seminar = Seminar::find($id);
        $seminar->id_ruangan = $request->id_ruangan;
        if ($seminar->save()) {
            $seminar = $seminar->where('id_seminar',$id)->with([
                'magang' => function ($query) {
                    $query->with('mahasiswa');
                },
                'ruangan',
                'penguji'
            ])->first();
            return response()->json([
                'message' => 'Ruangan seminar berhasil diubah',
                'data' => $seminar
            ]);
        } else {
            return response()->json([
                'message' => 'Ruangan seminar gagal diubah',
                'data' => null
            ], 400);
        }
    }

    public function penguji(Request $request, $id)
    {
        $seminar = Seminar::find($id);
        $penguji = Dosen::find($request->id_dosen);
        $seminar->penguji()->attach($penguji);
        if ($seminar->save()) {
            $seminar = $seminar->where('id_seminar',$id)->with([
                'magang' => function ($query) {
                    $query->with('mahasiswa');
                },
                'ruangan',
                'penguji'
            ])->first();
            return response()->json([
                'message' => 'Penguji seminar berhasil ditambahkan',
                'data' => $seminar
            ]);
        } else {
            return response()->json([
                'message' => 'Penguji seminar gagal ditambahkan',
                'data' => null
            ], 400);
        }
    }

    public function removePenguji(Request $request, $id)
    {
        $seminar = Seminar::find($id);
        $penguji = Dosen::find($request->id_dosen);
        $seminar->penguji()->detach($penguji);
        if ($seminar->save()) {
            $seminar = $seminar->where('id_seminar',$id)->with([
                'magang' => function ($query) {
                    $query->with('mahasiswa');
                },
                'ruangan',
                'penguji'
            ])->first();
            return response()->json([
                'message' => 'Penguji seminar berhasil ditambahkan',
                'data' => $seminar
            ]);
        } else {
            return response()->json([
                'message' => 'Penguji seminar gagal ditambahkan',
                'data' => null
            ], 400);
        }
    }
}
