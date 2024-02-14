<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterMahasiswaRequest;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\baseMail;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswa = Mahasiswa::orderBy('nim', 'desc')->paginate(10);
        return response()->json([
            'message' => 'berhasil menampilkan data mahasiswa',
            'data' => $mahasiswa
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
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
                            "address" => "admin@newkmmd3ti.vokasi.uns.ac.id",
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
                            "tautan" => null
                        ],
                        "attachments" => []
                    ]
                ));
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
                $mahasiswa->delete();
                return response()->json([
                    'message' => 'data mahasiswa berhasil dihapus',
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
                'message' => 'data mahasiswa gagal dihapus',
                'data' => null
            ], 500);
        }
    }
}
