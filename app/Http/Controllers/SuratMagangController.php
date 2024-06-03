<?php

namespace App\Http\Controllers;

use App\Models\SuratMagang;
use App\Models\SuratJawaban;
use Illuminate\Http\Request;
use App\Http\Requests\SuratMagang as SuratMagangRequest;
use Illuminate\Support\Facades\Storage;
use Jstewmc\Rtf\Document;
use Illuminate\Support\Facades\Mail;
use App\Mail\baseMail;
use App\Models\Magang;

class SuratMagangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        $magang->suratMagang()->update([
            'token' => bin2hex(random_bytes(32)),
            'token_expired' => now()->addHours(2)
        ]);
        $surat = $magang->suratMagang()->get();
        return response()->json([
            'message' => 'Berhasil menampilkan surat magang',
            'data' => $surat
        ]);
    }

    public function indexAll()
    {
        $user = request()->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        $magang->suratMagang()->update([
            'token' => bin2hex(random_bytes(32)),
            'token_expired' => now()->addHours(2)
        ]);
        $magang->suratJawaban()->update([
            'token' => bin2hex(random_bytes(32)),
            'token_expired' => now()->addHours(2)
        ]);
        $suratPengantar = $magang->suratMagang()->where('jenis_surat',1)->first();
        $suratSerahTerima = $magang->suratMagang()->where('jenis_surat',2)->first();
        $suratJawaban = $magang->suratJawaban()->first();

        return response()->json([
            'message' => 'Berhasil menampilkan surat magang',
            'data' => [
                'surat_pengantar' => $suratPengantar,
                'surat_serah_terima' => $suratSerahTerima,
                'surat_jawaban' => $suratJawaban
            ]
        ]);
    }

    public function indexPaginate()
    {
        $jenisMapping = [
            "serah" => 2,
            "terima" => 2,
            "pengajuan" => 1,
            "pengantar" => 1,
        ];

        $statusMapping = [
            "sudah" => 1,
            "belum" => 0,
            "sudah mengajukan" => 1,
            "belum mengajukan" => 0,
        ];

        $order = request()->order ?: 'id_surat';
        $sort = request()->sort ?: 'asc';
        $limit = request()->limit ?: 10;

        $query = SuratMagang::query();

        if ($order == 'nim' || $order == 'status_pengajuan_instansi')
        {
            $query->addSelect([
                $order => Magang::select($order)
                ->whereColumn('id_magang', 'surat_magang.id_magang')
                ->limit(1)
            ]);
            $query->orderBy($order, $sort);
        } else {
            $query->orderBy($order, $sort);
        }
        
        if (array_key_exists(strtolower(request()->kueri), $jenisMapping)) {
            $query->where('jenis_surat', $jenisMapping[request()->kueri]);
        } else if (array_key_exists(strtolower(request()->kueri), $statusMapping)) {
            $query->whereHas('magang', function ($query) use ($statusMapping) {
                $query->where('status_pengajuan_instansi', $statusMapping[request()->kueri]);
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
                ->orWhere('file_surat', 'like', "%" . request()->kueri . "%")
                ->orWhere('nomor_surat', 'like', "%" . request()->kueri . "%");
            });
        }

        $suratMagang = $query->with('magang.mahasiswa')->paginate($limit);

        return response()->json([
            'message' => 'Berhasil menampilkan surat magang',
            'data' => $suratMagang
        ]);
    }

    public function search(Request $request)
    {
        $jenisMapping = [
            "serah" => 2,
            "terima" => 2,
            "pengajuan" => 1,
            "pengantar" => 1,
        ];

        $search = $request->kueri;

        //check if search have jenisMapping inside it by regex. ex: Surat serah terima, surat pengajuan, surat pengantar
        if (preg_match('/\b(?:' . implode('|', array_keys($jenisMapping)) . ')\b/', $search, $matches)) {
            $search = $jenisMapping[$matches[0]]; // Get the value from jenisMapping based on the matched key
            $suratMagang = SuratMagang::where('jenis_surat', $search);
        } else {
            $suratMagang = SuratMagang::where(function ($query) use ($search) {
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
                ->orWhere('file_surat', 'like', "%" . $search . "%")
                ->orWhere('nomor_surat', 'like', "%" . $search . "%");
            });
        }


        $suratMagang = $suratMagang->orderBy('id_surat', 'DESC')->with([
            'magang'
        ])->paginate(10);

        return response()->json([
            'message' => 'Berhasil menampilkan hasil pencarian surat magang',
            'data' => $suratMagang
        ]);
    }

    public function dokumen($id)
    {
        $suratMagang = SuratMagang::find($id);
        if (!$suratMagang) {
            return response()->json([
                'message' => 'Surat magang tidak ditemukan',
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
    public function storePengantar(SuratMagangRequest $request)
    {
        $user = request()->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        
        if (!$magang->instansi()->exists() || $magang->instansi()->first()->status_instansi != 1) {
            return response()->json([
                'message' => 'Instansi belum disetujui',
            ], 400);
        }
        if ($magang->suratMagang()->where('jenis_surat',1)->exists()) {
            return response()->json([
                'message' => 'Surat pengantar sudah ada',
            ], 400);
        }
        //check if already have surat jawaban
        if ($magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban sudah ada',
            ], 400);
        }
        //example of nomor_surat = 026/UN7.../2026 parted by /, can be many array
        $no_surat =explode('/',$request->nomor_surat);
        //get the first part of the nomor_surat and convert it to integer
        $no_urut= $no_surat[0];
        $no_urut_int = (int)$no_urut;
        //get the last part of the nomor_surat and convert it to integer
        $tahun = (int)end($no_surat);
        if ($no_urut_int == 0 || $tahun == 0) {
            return response()->json([
                'message' => 'Nomor surat tidak valid',
            ], 400);
        }
        $filename = $tahun."_".$no_urut."_surat_pengantar_magang.pdf";
        $file = $request->file('file_surat');
        $file->storeAs('public/surat_magang/pengantar', $filename);

        $suratMagang = new SuratMagang;
        $suratMagang->jenis_surat = 1;
        $suratMagang->id_magang = $magang->id_magang;
        $suratMagang->no_urut = $no_urut;
        $suratMagang->nomor_surat = $request->nomor_surat;
        $suratMagang->file_surat = $filename;

        $suratMagang->save();
        $magang->status_pengajuan_instansi = 1;
        $magang->save();
        return response()->json([
            'message' => 'Surat pengantar berhasil ditambahkan',
            'data' => $suratMagang
        ]);
    }

    public function storeSerahTerima(SuratMagangRequest $request)
    {
        $user = request()->user()->mahasiswa()->first();
        $magang = $user->magang()->first();
        if (!$magang->suratMagang()->where('jenis_surat',1)->exists()) {
            return response()->json([
                'message' => 'Surat pengantar belum diupload',
            ], 400);
        }
        //check if didn't have surat jawaban
        if (!$magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban belum diupload',
            ], 400);
        }
        if ($magang->suratMagang()->where('jenis_surat',2)->exists()) {
            return response()->json([
                'message' => 'Surat serah terima sudah ada',
            ], 400);
        }
        //example of nomor_surat = 026/UN7.../2026 parted by /, can be many array
        $no_surat =explode('/',$request->nomor_surat);
        //get the first part of the nomor_surat and convert it to integer
        $no_urut= $no_surat[0];
        $no_urut_int = (int)$no_urut;
        //get the last part of the nomor_surat and convert it to integer
        $tahun = (int)end($no_surat);
        if ($no_urut_int == 0 || $tahun == 0) {
            return response()->json([
                'message' => 'Nomor surat tidak valid',
            ], 400);
        }
        $filename = $tahun."_".$no_urut."_surat_serah_terima.pdf";
        $file = $request->file('file_surat');
        $file->storeAs('public/surat_magang/serah_terima', $filename);

        $suratMagang = new SuratMagang;
        $suratMagang->jenis_surat = 2;
        $suratMagang->id_magang = $magang->id_magang;
        $suratMagang->no_urut = $no_urut;
        $suratMagang->nomor_surat = $request->nomor_surat;
        $suratMagang->file_surat = $filename;

        $suratMagang->save();
        return response()->json([
            'message' => 'Surat serah terima berhasil ditambahkan',
            'data' => $suratMagang
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($magang_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePengantar(Request $request)
    {
        $user = request()->user()->mahasiswa()->first();
        //check if user have magang with magang_id
        $magang = $user->magang()->first();
        if (!$magang) {
            return response()->json([
                'message' => 'Magang tidak ditemukan',
            ], 404);
        }
        if (!$magang->instansi()->exists() || $magang->instansi()->first()->status_instansi != 1) {
            return response()->json([
                'message' => 'Instansi belum disetujui',
            ], 400);
        }
        if ($magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban sudah ada',
            ], 400);
        }
        //check if didn't have surat pengantar
        if (!$magang->suratMagang()->where('jenis_surat',1)->exists()) {
            return response()->json([
                'message' => 'Surat pengantar belum ada',
            ], 400);
        }
        //if request have file
        if ($request->hasFile('file_surat')) {
            //check if nomor_surat is same with the previous surat
            $suratPenganatar = $magang->suratMagang()->where('jenis_surat',1)->first();
            if (!$request->has('nomor_surat') || $suratPenganatar->nomor_surat == $request->nomor_surat) {
                //replace the file
                $filename = $suratPenganatar->file_surat;
                $file = $request->file('file_surat');
                $file->storeAs('public/surat_magang/pengantar', $filename);
                return response()->json([
                    'message' => 'Surat pengantar berhasil diupdate',
                    'data' => $suratPenganatar
                ]);
            } else {
                //example of nomor_surat = 026/UN7.../2026 parted by /, can be many array
                $no_surat =explode('/',$request->nomor_surat);
                //get the first part of the nomor_surat and convert it to integer
                $no_urut= $no_surat[0];
                $no_urut_int = (int)$no_urut;
                //get the last part of the nomor_surat and convert it to integer
                $tahun = (int)end($no_surat);
                if ($no_urut_int == 0 || $tahun == 0) {
                    return response()->json([
                        'message' => 'Nomor surat tidak valid',
                    ], 400);
                }
                $filename = $tahun."_".$no_urut."_surat_pengantar_magang.pdf";
                //delete the old file
                Storage::delete('public/surat_magang/pengantar/'.$suratPenganatar->file_surat);
                $file = $request->file('file_surat');
                $file->storeAs('public/surat_magang/pengantar', $filename);

                $suratMagang = new SuratMagang;
                $suratMagang->jenis_surat = 1;
                $suratMagang->id_magang = $magang->id_magang;
                $suratMagang->no_urut = $no_urut;
                $suratMagang->nomor_surat = $request->nomor_surat;
                $suratMagang->file_surat = $filename;

                $suratMagang->save();
                $magang->status_pengajuan_instansi = 1;
                $magang->save();
                return response()->json([
                    'message' => 'Surat pengantar berhasil ditambahkan',
                    'data' => $suratMagang
                ]);
            }
        } else {
            if ($request->has('nomor_surat')) {
                //change nomor surat in the database
                $suratPengantar = $magang->suratMagang()->where('jenis_surat',1)->first();
                $suratPengantar->nomor_surat = $request->nomor_surat;
                $suratPengantar->save();
                return response()->json([
                    'message' => 'Surat pengantar berhasil diupdate',
                    'data' => $suratPengantar
                ]);
            }

            return response()->json([
                'message' => 'request tidak memiliki file atau nomor surat',
            ], 400);
        }
        return response()->json([
            'message' => 'request tidak memiliki file atau nomor surat',
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyPengantar()
    {
        $user = request()->user()->mahasiswa()->first();
        //check if user have magang with magang_id
        $magang = $user->magang()->first();
        if (!$magang) {
            return response()->json([
                'message' => 'Magang tidak ditemukan',
            ], 404);
        }
        if (!$magang->suratMagang()->where('jenis_surat',1)->exists()) {
            return response()->json([
                'message' => 'Surat pengantar belum ada',
            ], 400);
        }
        if ($magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban sudah ada',
            ], 400);
        }
        $suratPenganatar = $magang->suratMagang()->where('jenis_surat',1)->first();
        Storage::delete('public/surat_magang/pengantar/'.$suratPenganatar->file_surat);
        $suratPenganatar->delete();
        return response()->json([
            'message' => 'Surat pengantar berhasil dihapus',
        ]);
    }

    public function updateSerahTerima(Request $request)
    {
        $user = request()->user()->mahasiswa()->first();
        //check if user have magang with magang_id
        $magang = $user->magang()->first();
        if (!$magang) {
            return response()->json([
                'message' => 'Magang tidak ditemukan',
            ], 404);
        }
        if (!$magang->suratMagang()->where('jenis_surat',1)->exists()) {
            return response()->json([
                'message' => 'Surat pengantar belum diupload',
            ], 400);
        }
        //check if didn't have surat jawaban
        if (!$magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban belum diupload',
            ], 400);
        }
        if (!$magang->suratMagang()->where('jenis_surat',2)->exists()) {
            return response()->json([
                'message' => 'Surat serah terima belum ada',
            ], 400);
        }
        //if request have file
        if ($request->hasFile('file_surat')) {
            //check if nomor_surat is same with the previous surat
            $suratSerahTerima = $magang->suratMagang()->where('jenis_surat',2)->first();
            if (!$request->has('nomor_surat') || $suratSerahTerima->nomor_surat == $request->nomor_surat) {
                //replace the file
                $filename = $suratSerahTerima->file_surat;
                $file = $request->file('file_surat');
                $file->storeAs('public/surat_magang/serah_terima', $filename);
                return response()->json([
                    'message' => 'Surat serah terima berhasil diupdate',
                    'data' => $suratSerahTerima
                ]);
            } else {
                //example of nomor_surat = 026/UN7.../2026 parted by /, can be many array
                $no_surat =explode('/',$request->nomor_surat);
                //get the first part of the nomor_surat and convert it to integer
                $no_urut= $no_surat[0];
                $no_urut_int = (int)$no_urut;
                //get the last part of the nomor_surat and convert it to integer
                $tahun = (int)end($no_surat);
                if ($no_urut_int == 0 || $tahun == 0) {
                    return response()->json([
                        'message' => 'Nomor surat tidak valid',
                    ], 400);
                }
                $filename = $tahun."_".$no_urut."_surat_serah_terima.pdf";
                //delete the old file
                Storage::delete('public/surat_magang/serah_terima/'.$suratSerahTerima->file_surat);
                $file = $request->file('file_surat');
                $file->storeAs('public/surat_magang/serah_terima', $filename);

                $suratMagang = new SuratMagang;
                $suratMagang->jenis_surat = 2;
                $suratMagang->id_magang = $magang->id_magang;
                $suratMagang->no_urut = $no_urut;
                $suratMagang->nomor_surat = $request->nomor_surat;
                $suratMagang->file_surat = $filename;

                $suratMagang->save();
                return response()->json([
                    'message' => 'Surat serah terima berhasil ditambahkan',
                    'data' => $suratMagang
                ]);
            }
        } else {
            if ($request->has('nomor_surat')) {
                //change nomor surat in the database
                $suratSerahTerima = $magang->suratMagang()->where('jenis_surat',2)->first();
                $suratSerahTerima->nomor_surat = $request->nomor_surat;
                $suratSerahTerima->save();
                return response()->json([
                    'message' => 'Surat serah terima berhasil diupdate',
                    'data' => $suratSerahTerima
                ]);
            }

            return response()->json([
                'message' => 'request tidak memiliki file atau nomor surat',
            ], 400);
        }
        return response()->json([
            'message' => 'request tidak memiliki file atau nomor surat',
        ], 400);
    }

    public function destroySerahTerima()
    {
        $user = request()->user()->mahasiswa()->first();
        //check if user have magang with magang_id
        $magang = $user->magang()->first();
        if (!$magang) {
            return response()->json([
                'message' => 'Magang tidak ditemukan',
            ], 404);
        }
        //check if didn't have surat jawaban
        if (!$magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban belum diupload',
            ], 400);
        }
        if (!$magang->suratMagang()->where('jenis_surat',2)->exists()) {
            return response()->json([
                'message' => 'Surat serah terima belum ada',
            ], 400);
        }
        $suratSerahTerima = $magang->suratMagang()->where('jenis_surat',2)->first();
        Storage::delete('public/surat_magang/serah_terima/'.$suratSerahTerima->file_surat);
        $suratSerahTerima->delete();
        return response()->json([
            'message' => 'Surat serah terima berhasil dihapus',
        ]);
    }

    public function generateSerahTerima()
    {
        set_time_limit(3000);
        $user = request()->user()->mahasiswa()->first();
        //check if user have magang with magang_id
        $magang = $user->magang()->with('topik','instansi','mahasiswa','dosen','rencanaMagang')->first();

        if (!$magang) {
            return response()->json([
                'message' => 'Magang tidak ditemukan',
            ], 404);
        }
        if (!$magang->instansi()->exists() || $magang->instansi()->first()->status_instansi != 1) {
            return response()->json([
                'message' => 'Instansi belum disetujui',
            ], 400);
        }
        //check if didn't have surat jawaban
        if (!$magang->suratJawaban()->exists()) {
            return response()->json([
                'message' => 'Surat jawaban belum diupload',
            ], 400);
        }
        //check bila status diterima instansi
        if ($magang->status_diterima_instansi != 1) {
            return response()->json([
                'message' => 'Magang belum diterima instansi',
            ], 400);
        }
        //get path of the file from storage
        $path = storage_path('app/public/template/format_surat_serah_terima.rtf');

        // Create a new RTF document from the file
        $document = new Document($path);
    
        // Access the content of the RTF document
        $content = $document->write();

        $data = $magang->toArray();

        //$content = str_replace("fill_nomor_dokumen", "...........", $content);
        $content = str_replace("data_tahun_a", $data["tahun"], $content);
        $content = str_replace("data_tahun_b", ($data["tahun"]-1)."/".$data["tahun"], $content);
        $content = str_replace("data_nama_dosen", $data["dosen"]["nama"], $content);
        $content = str_replace("data_nama_mhs", $data["mahasiswa"]["nama"], $content);
        $content = str_replace("data_nim", strtoupper($data["mahasiswa"]["nim"]), $content);
        $content = str_replace("data_nama_topik", $data["topik"]["nama_topik"], $content);
        $content = str_replace("data_nama_instansi", $data["instansi"]["nama"], $content);
        $content = str_replace("data_nama_kaprodi", "Eko Harry Pratisto, S.T., M.Info.Tech., Ph.D.", $content);
        $content = str_replace("data_nik_kaprodi", "1981112420130201", $content);
        $content = str_replace("data_alamat_instansi", $data["instansi"]["alamat"], $content);
        $content = str_replace("data_alamat", $data["instansi"]["alamat"], $content);

        $rencana_content = "";
        $template_rencana = "data_tabel";

        //use for loop with rencanaMagang's length
        for ($i=0; $i < count($data["rencana_magang"]); $i++) { 
            //check first iteration
            $n = $data["rencana_magang"][$i]["minggu"];
            $value = $data["rencana_magang"][$i]["kegiatan"];
            if ($i == 0) {
                $rencana_content = "$n\\cell Minggu ke-$n\\cell $value\\par \\cell }\\pard \\ltrpar\\ql \\li0\\ri0\\sa200\\sl276\\slmult1\\widctlpar\\intbl\\wrapdefault\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\rtlch\\fcs1 \\af0\\afs20 \\ltrch\\fcs0 \\fs20\\insrsid4720666 \\trowd \\irow$n\\irowband$n\\ltrrow\\ts11\\trgaph108\\trleft0\\trbrdrt\\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 ";
            } else if ($i == count($data["rencana_magang"])-1) {
                $rencana_content .= "\\trftsWidth1\\trautofit1\\trpaddl108\\trpaddr108\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3\\tblrsid4720666\\tbllkhdrrows\\tbllklastrow\\tbllkhdrcols\\tbllklastcol\\tblind108\\tblindtype3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth537\\clshdrawnil\\clhidemark \\cellx537\\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth2492\\clshdrawnil\\clhidemark \\cellx3029\\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth6610\\clshdrawnil \\cellx9639\\row\\ltrrow}\\pard \\ltrpar\\qj \\li0\\ri0\\sl276\\slmult1\\widctlpar\\intbl \\tx3060\\wrapdefault\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\rtlch\\fcs1 \\af0 \\ltrch\\fcs0 \\insrsid4720666 $n\\cell Minggu ke-$n\\cell $value\\par \\cell }\\pard \\ltrpar\\ql \\li0\\ri0\\sa200\\sl276\\slmult1\\widctlpar\\intbl\\wrapdefault\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\rtlch\\fcs1 \\af0\\afs20 \\ltrch\\fcs0 \\fs20\\insrsid4720666 \\trowd \\irow$n\\irowband$n\\lastrow \\ltrrow\\ts11\\trgaph108\\trleft0\\trbrdrt \\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 ";
            } else {
                $rencana_content .= "\\trftsWidth1\\trautofit1\\trpaddl108\\trpaddr108\\trpaddfl3\\trpaddft3\\trpaddfb3\\trpaddfr3\\tblrsid4720666\\tbllkhdrrows\\tbllklastrow\\tbllkhdrcols\\tbllklastcol\\tblind108\\tblindtype3 \\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth537\\clshdrawnil\\clhidemark \\cellx537\\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth2492\\clshdrawnil\\clhidemark \\cellx3029\\clvertalt\\clbrdrt\\brdrs\\brdrw10 \\clbrdrl\\brdrs\\brdrw10 \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrs\\brdrw10 \\cltxlrtb\\clftsWidth3\\clwWidth6610\\clshdrawnil \\cellx9639\\row \\ltrrow}\\pard \\ltrpar\\qj \\li0\\ri0\\sl276\\slmult1\\widctlpar\\intbl \\tx3060\\wrapdefault\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\rtlch\\fcs1 \\af0 \\ltrch\\fcs0 \\insrsid4720666 $n\\cell Minggu ke-$n\\cell $value\\par \\cell }\\pard \\ltrpar\\ql \\li0\\ri0\\sa200\\sl276\\slmult1\\widctlpar\\intbl\\wrapdefault\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 {\\rtlch\\fcs1 \\af0\\afs20 \\ltrch\\fcs0 \\fs20\\insrsid4720666 \\trowd \\irow$n\\irowband$n\\ltrrow\\ts11\\trgaph108\\trleft0\\trbrdrt \\brdrs\\brdrw10 \\trbrdrl\\brdrs\\brdrw10 \\trbrdrb\\brdrs\\brdrw10 \\trbrdrr\\brdrs\\brdrw10 \\trbrdrh\\brdrs\\brdrw10 \\trbrdrv\\brdrs\\brdrw10 ";
            }
        }
        $content = str_replace($template_rencana, $rencana_content, $content);

        //save the file to documents/pengajuan-instansi
        $filename = $data["tahun"].'_'.strtoupper($data["mahasiswa"]["nim"]).'_surat_serah_terima.rtf';
        //storage path
        $path = storage_path('app/public/surat_magang/serah_terima_2024/'.$filename);

        //check if file already exists
        if (file_exists($path)) {
            //delete the file
            unlink($path);
        }
        //make file with content
        $document = new Document($content);
        $document->save($path);

        Mail::to($data["mahasiswa"]["email"])->send(new baseMail(
            [
                "view" => "emails.akun",
                "from" => [
                    "address" =>  env('MAIL_FROM_ADDRESS'),
                    "name" => "Notifikasi D3TI"
                ],
                "tags" => [ "serah", "terima","dokumen","surat", "d3ti","kuliah","notifikasi" ],
                "subject" => "Surat Serah Terima Magang",
                "content" => [
                    "nama_user" => $data["mahasiswa"]["nama"],
                    "judul" => "Surat Serah Terima Magang",
                    "pesan" => "Berikut adalah tautan untuk mengunduh surat serah terima magang",
                    "tautan" => env('APP_URL').'/mahasiswa/magang/surat-serah-terima-2024/'.$filename,
                    "useakun" => false,
                    "akun" => []
                ],
                "attachments" => []
            ]
        ));

        //return redirect to url $path
        return response()->json([
            'message' => 'Surat serah terima berhasil di generate',
            'data' => $filename
        ]);
    }
}
