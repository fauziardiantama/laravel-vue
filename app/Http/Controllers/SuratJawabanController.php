<?php

namespace App\Http\Controllers;

use App\Models\SuratJawaban;
use Illuminate\Http\Request;
use App\Http\Requests\SuratJawaban as SuratJawabanRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Magang;

class SuratJawabanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        $suratJawaban = $magang->suratJawaban()->first();

        return response()->json([
            'message' => 'Berhasil menampilkan surat jawaban',
            'data' => $suratJawaban
        ]);
    }

    public function indexPaginate()
    {
        $statusMapping = [
            "disetujui" => 1,
            "diterima" => 1,
            "ditolak" => -1,
            "menunggu" => 0,
            "Menunggu" => 0,
            "Ditolak" => -1,
            "Diterima" => 1,
            "Disetujui" => 1
        ];
    
        $order = request()->order ?: 'id_surat';
        $sort = request()->sort ?: 'asc';
        $limit = request()->limit ?: 10;
    
        $query = SuratJawaban::query();
    
        if ($order == 'nim' || $order == 'nama') {
            $query->addSelect([
                $order => Magang::select('mahasiswa.'.$order)
                    ->join('mahasiswa', 'magang.nim', '=', 'mahasiswa.nim')
                    ->whereColumn('magang.id_magang', 'surat_jawaban.id_magang')
                    ->limit(1)
            ]);
            $query->orderBy($order, $sort);
        } else if ($order == 'status_diterima_instansi') {
            $query->addSelect([
                'status_diterima_instansi' => Magang::select('status_diterima_instansi')
                    ->whereColumn('magang.id_magang', 'surat_jawaban.id_magang')
                    ->limit(1)
            ]);
            $query->orderBy('status_diterima_instansi', $sort);
        } else {
            $query->orderBy($order, $sort);
        }
    
        if (array_key_exists(request()->kueri, $statusMapping)) {
            $query->whereHas('magang', function ($query) use ($statusMapping) {
                $query->where('status_diterima_instansi', $statusMapping[request()->kueri]);
            });
        } else {
            $query->where(function ($query) {
                $query->whereHas('magang', function ($query) {
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
                        ->orWhere('instansi.alamat', 'like', '%'.request()->kueri.'%')
                        ->orWhere('instansi.email', 'like', '%'.request()->kueri.'%')
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
                })
                ->orWhere('file_surat', 'like', "%" . request()->kueri . "%");
            });
        }
    
        $suratJawaban = $query->with('magang.mahasiswa')->paginate($limit);
    
        return response()->json([
            'message' => 'Berhasil menampilkan semua surat jawaban',
            'data' => $suratJawaban
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->kueri;
        $suratJawaban = SuratJawaban::where(function ($query) use ($search) {
            $query->whereHas('magang', function ($query) use ($search) {
                $query->where('tahun', 'like', '%'.$search.'%')
                ->orWhereHas('mahasiswa', function ($query) use ($search) {
                    $query->where('mahasiswa.nim', 'like', '%'.$search.'%')
                    ->orWhere('mahasiswa.nama', 'like', '%'.$search.'%')
                    ->orWhere('mahasiswa.email', 'like', '%'.$search.'%')
                    ->orWhere('mahasiswa.no_telp', 'like', '%'.$search.'%');
                })
                ->orWhereHas('topik', function ($query) use ($search) {
                    $query->where('topik_kmm.nama_topik', 'like', '%'.$search.'%');    
                })
                ->orWhereHas('instansi', function ($query) use ($search) {
                    $query->where('instansi.nama', 'like', '%'.$search.'%')
                    ->orWhere('instansi.alamat', 'like', '%'.$search.'%')
                    ->orWhere('instansi.email', 'like', '%'.$search.'%')
                    ->orWhere('instansi.no_telp', 'like', '%'.$search.'%')
                    ->orWhere('instansi.web', 'like', '%'.$search.'%');
                })
                ->orWhereHas('dosen', function ($query) use ($search) {
                    $query->where('dosen.nik', 'like', '%'.$search.'%')
                    ->orWhere('dosen.nama', 'like', '%'.$search.'%')
                    ->orWhere('dosen.email', 'like', '%'.$search.'%');
                })
                ->orWhereHas('progres', function ($query) use ($search) {
                    $query->where('progres.progres', 'like', '%'.$search.'%');
                });
            })
            ->orWhere('file_surat', 'like', "%" . $search . "%");
        });

        $suratJawaban = SuratJawaban::orderBy('id_surat', 'DESC')->with(['magang' => function ($query) {
            $query->with('mahasiswa');
        }])->paginate(10);
        
        return response()->json([
            'message' => 'Berhasil menampilkan hasil pencarian surat jawaban',
            'data' => $suratJawaban
        ]);
    }

    public function dokumen($id)
    {
        $suratMagang = SuratJawaban::find($id);
        if (!$suratMagang) {
            return response()->json([
                'message' => 'Surat jawaban tidak ditemukan',
            ], 404);
        }
        $suratMagang->token = bin2hex(random_bytes(32));
        $suratMagang->token_expired = now()->addHours(2);
        $suratMagang->save();
        return response()->json([
            'message' => 'Berhasil menampilkan surat magang',
            'data' => $suratMagang
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuratJawabanRequest $request)
    {
        $user = request()->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        if (!$magang->instansi()->exists() || $magang->instansi()->first()->status_instansi != 1) {
            return response()->json([
                'message' => 'Instansi belum disetujui',
            ], 400);
        }
        if ($magang->status_dosen != 1) {
            return response()->json([
                'message' => 'Dosen belum disetujui',
            ], 400);
        }
        if ($magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban sudah ada',
            ], 400);
        }
        //get last 3 digit of nim
        $nim = substr($magang->nim, -3);
        $tahun = $magang->tahun;
        
        $filename = $tahun."_".$nim."_surat_jawaban.pdf";
        $file = $request->file('file_surat');
        $file->storeAs('public/surat_jawaban', $filename);

        $suratJawaban = new SuratJawaban;
        $suratJawaban->id_magang = $magang->id_magang;
        $suratJawaban->file_surat = $filename;
        $suratJawaban->save();
        $magang->status_pengajuan_instansi = 1;
        $magang->save();
        return response()->json([
            'message' => 'Surat Jawaban berhasil diupload',
            'data' => $suratJawaban
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = request()->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        if (!$magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban belum diupload',
            ], 400);
        }
        if ($magang->status_diterima_instansi == 1) {
            return response()->json([
                'message' => 'Magang sudah diterima instansi',
            ], 400);
        }
        $suratJawaban = $magang->suratJawaban()->first();
        //get last 3 digit of nim
        $nim = substr($magang->nim, -3);
        $tahun = $magang->tahun;
        
        $filename = $tahun."_".$nim."_surat_jawaban.pdf";
        $file = $request->file('file_surat');
        //delete the old file
        Storage::delete('public/surat_jawaban/'.$suratJawaban->file_surat);
        $file->storeAs('public/surat_jawaban', $filename);

        $suratJawaban->file_surat = $filename;
        $suratJawaban->save();
        $magang->status_pengajuan_instansi = 1;
        $magang->save();
        return response()->json([
            'message' => 'Surat Jawaban berhasil diupload',
            'data' => $suratJawaban
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $user = request()->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        if (!$magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban belum diupload',
            ], 400);
        }
        if ($magang->status_diterima_instansi == 1) {
            return response()->json([
                'message' => 'Magang sudah diterima instansi',
            ], 400);
        }
        $suratJawaban = $magang->suratJawaban()->first();
        Storage::delete('public/surat_jawaban/'.$suratJawaban->file_surat);
        $suratJawaban->delete();
        $magang->status_pengajuan_instansi = 0;
        $magang->save();
        return response()->json([
            'message' => 'Surat Jawaban berhasil dihapus',
        ]);
    }
}
