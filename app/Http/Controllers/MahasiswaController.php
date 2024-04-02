<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterMahasiswaRequest;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\baseMail;
use App\Events\Mhs;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
        // {
        //     $mahasiswa = Mahasiswa::orderBy('nim', 'desc')->with('dokumenRegistrasi','magang','proposalTa')->paginate(10);
        //     return response()->json([
        //         'message' => 'berhasil menampilkan data mahasiswa',
        //         'data' => $mahasiswa
        //     ]);
    // }

    public function index(Request $request)
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
        $order = $request->order ?: 'nim';
        $sort = $request->sort ?: 'asc';
        $limit = $request->limit ?: 10;
        if (array_key_exists($request->kueri, $statusMapping)) {
            $mahasiswa = Mahasiswa::where('status', $statusMapping[$request->kueri])
            ->with('dokumenRegistrasi','magang','proposalTa')
            ->orderBy($order, $sort)
            ->paginate($limit);
        } else {
            $mahasiswa = Mahasiswa::where('nama', 'like', '%'.$request->kueri.'%')
            ->orWhere('email', 'like', '%'.$request->kueri.'%')
            ->orWhere('nim', 'like', '%'.$request->kueri.'%')
            ->orWhere('no_telp', 'like', '%'.$request->kueri.'%')
            ->orderBy($order, $sort)
            ->with('dokumenRegistrasi','magang','proposalTa')
            ->paginate($limit);
        }

        return response()->json([
            'message' => 'Berhasil menampilkan instansi',
            'data' => $mahasiswa
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->with('dokumenRegistrasi','magang','proposalTa')->first();
        if ($mahasiswa) {
            return response()->json([
                'message' => 'berhasil menampilkan data mahasiswa',
                'data' => $mahasiswa
            ]);
        } else {
            return response()->json([
                'message' => 'data mahasiswa tidak ditemukan',
                'data' => null
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegisterMahasiswaRequest $request, $nim)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if ($mahasiswa) {
                $mahasiswa->update($request->all());
                event( new Mhs("update", ["Admin","User.".$nim], false, $mahasiswa));
                return response()->json([
                    'message' => 'data mahasiswa berhasil diubah',
                    'data' => $mahasiswa
                ]);
            } else {
                return response()->json([
                    'message' => 'data mahasiswa tidak ditemukan',
                    'data' => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'data mahasiswa gagal diubah',
                'data' => null
            ], 500);
        }
    }

    public function aktivasi($nim)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if ($mahasiswa) {
                $mahasiswa->update(['status' => 1]);
                Mail::to($mahasiswa->email)->send(new baseMail(
                    [
                        "view" => "emails.akun",
                        "from" => [
                            "address" =>  env('MAIL_FROM_ADDRESS'),
                            "name" => "Notifikasi D3TI"
                        ],
                        "tags" => [ "verifikasi", "akun", "d3ti","kuliah","notifikasi" ],
                        "subject" => "Akun telah diaktivasi",
                        "content" => [
                            "nama_user" => $mahasiswa->nama,
                            "judul" => "Akun Anda Telah Diaktivasi",
                            "pesan" => "Admin telah menyetujui akun Anda. Silahkan login untuk mengakses akun Anda.",
                            "tautan" => null,
                            "useakun" => false,
                            "akun" => []
                        ],
                        "attachments" => []
                    ]
                ));
                event( new Mhs("update", ["Admin","User.".$nim], false, $mahasiswa));
                return response()->json([
                    'message' => 'data mahasiswa berhasil diaktifkan',
                    'data' => $mahasiswa
                ]);
            } else {
                return response()->json([
                    'message' => 'data mahasiswa tidak ditemukan',
                    'data' => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'data mahasiswa gagal diaktifkan',
                'data' => null
            ], 500);
        }
    }

    public function reject($nim)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if ($mahasiswa) {
                $mahasiswa->update(['status' => -1]);
                Mail::to($mahasiswa->email)->send(new baseMail(
                    [
                        "view" => "emails.akun",
                        "from" => [
                            "address" => "d3ti.no-reply@gmail.com",
                            "name" => "Notifikasi D3TI"
                        ],
                        "tags" => [ "verifikasi", "akun", "d3ti","kuliah","notifikasi" ],
                        "subject" => "Akun belum dapat diaktivasi",
                        "content" => [
                            "nama_user" => $mahasiswa->nama,
                            "judul" => "Akun Anda Belum Dapat Diaktivasi",
                            "pesan" => "Admin belum bisa menyetujui akun Anda. Silahkan periksa dokumen anda bila ada yang salah atau kurang lalu ajukan kembali, atau hubungi admin untuk informasi lebih lanjut.",
                            "tautan" => null,
                            "useakun" => false,
                            "akun" => []
                        ],
                        "attachments" => []
                    ]
                ));
                // send #EVENT MahasiswaUpdated to Mahasiswa.nim dan Admin
                event( new Mhs("update", ["Admin","User.".$nim], false, $mahasiswa));
                return response()->json([
                    'message' => 'data mahasiswa berhasil ditolak',
                    'data' => $mahasiswa
                ]);
            } else {
                return response()->json([
                    'message' => 'data mahasiswa tidak ditemukan',
                    'data' => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'data mahasiswa gagal ditolak',
                'error' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nim)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if ($mahasiswa) {
                $legacy = $mahasiswa;
                $mahasiswa->delete();
                // send #EVENT MahasiswaUpdated to Mahasiswa.nim dan Admin
                event( new Mhs("destroy", ["Admin","User.".$nim], true, $legacy));
                return response()->json([
                    'message' => 'data mahasiswa berhasil dihapus',
                    'data' => $legacy
                ]);
            } else {
                return response()->json([
                    'message' => 'data mahasiswa tidak ditemukan',
                    'data' => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'data mahasiswa gagal dihapus',
                'data' => null
            ], 500);
        }
    }
}
