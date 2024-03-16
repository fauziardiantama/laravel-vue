<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDokumenRegistrasi;
use App\Models\DokumenRegistrasi;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Http\Requests\DeleteDokumenRegistrasi;
use Illuminate\Support\Facades\Storage;
use App\Events\DokReg;
use App\Events\Mhs;

class DokumenRegistrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokumenRegistrasi = DokumenRegistrasi::orderBy('nim', 'asc')->paginate(10);
        return response()->json([
            'message' => 'Successfully retrieved dokumen registrasi',
            'data' => $dokumenRegistrasi
        ]);
    }

    public function indexWithMahasiswa()
    {
        $dokumenRegistrasi = DokumenRegistrasi::with('mahasiswa')->orderBy('nim', 'asc')->paginate(10);
        return response()->json([
            'message' => 'Successfully retrieved dokumen registrasi',
            'data' => $dokumenRegistrasi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDokumenRegistrasi $request)
    {
        try {
            if ($request->user()->isAdmin()) {
                $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
            } else {
                $mahasiswa = $request->user()->mahasiswa()->first();
            }
            if ($mahasiswa->status > 0) {
                return response()->json([
                    'message' => 'Tidak bisa mengunggah dokumen registrasi pada mahasiswa yang sudah aktif'
                ], 404);
            }
            $nim = $mahasiswa->nim;
            $dokumenRegistrasi = DokumenRegistrasi::where('nim', $nim)->first();
            if (!$dokumenRegistrasi) {
                $dokumenRegistrasi = new DokumenRegistrasi();
                $dokumenRegistrasi->nim = $nim;
            }
            
            $file = $request->file('file');
            switch ($request->type) {
                case 'KRS':
                    $fileName = $nim . '_krs.pdf';
                    $file->storeAs('public/dokumen_registrasi/krs', $fileName);
                    $dokumenRegistrasi->krs = $fileName;
                    break;
                case 'KartuMahasiswa':
                    $fileName = $nim . '_kartu_mahasiswa.pdf';
                    $file->storeAs('public/dokumen_registrasi/kartu_mahasiswa', $fileName);
                    $dokumenRegistrasi->kartu_mahasiswa = $fileName;
                    break;
                case 'Transkrip':
                    $fileName = $nim . '_transkrip.pdf';
                    $file->storeAs('public/dokumen_registrasi/transkrip', $fileName);
                    $dokumenRegistrasi->transkrip = $fileName;
                    break;
                case 'BuktiSeminar':
                    $fileName = $nim . '_bukti_seminar.pdf';
                    $file->storeAs('public/dokumen_registrasi/bukti_seminar', $fileName);
                    $dokumenRegistrasi->bukti_seminar = $fileName;
                    break;
                default:
                    return response()->json([
                        'message' => 'Invalid type of dokumen registrasi'
                    ], 400);
            }
            $dokumenRegistrasi->token = bin2hex(random_bytes(32));
            $dokumenRegistrasi->token_expired = now()->addHours(2);
            $dokumenRegistrasi->save();
            $mahasiswa->status = 0;
            $mahasiswa->save();
            event( new DokReg("store", ["Admin","User.".$nim], $dokumenRegistrasi->isEmpty(), $dokumenRegistrasi));
            event( new Mhs("update", ["Admin","User.".$nim], false, $mahasiswa));
            return response()->json([
                'message' => 'Successfully uploaded dokumen registrasi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload dokumen registrasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $nim)
    {
        if ($request->user()->isMahasiswa() && $request->user()->mahasiswa()->first()->nim != $nim) {
            return response()->json([
                'message' => 'NIM not match'
            ], 401);
        }
        $dokumenRegistrasi = DokumenRegistrasi::where('nim', $nim)->with('mahasiswa')->first();
        if (!$dokumenRegistrasi) {
            return response()->json([
                'message' => 'Dokumen registrasi not found'
            ], 404);
        }
        $dokumenRegistrasi->token = bin2hex(random_bytes(32));
        $dokumenRegistrasi->token_expired = now()->addHours(2);
        $dokumenRegistrasi->save();
        event( new DokReg("update", ["Admin","User.".$nim], $dokumenRegistrasi->isEmpty(), $dokumenRegistrasi));
        return response()->json([
            'message' => 'Successfully retrieved dokumen registrasi',
            'isEmpty' => $dokumenRegistrasi->isEmpty(),
            'data' => $dokumenRegistrasi
        ]);
    }

    public function showWithMahasiswa(Request $request, $nim)
    {
        if ($request->user()->isMahasiswa() && $request->user()->mahasiswa()->first()->nim != $nim) {
            return response()->json([
                'message' => 'NIM not match'
            ], 401);
        }
        $dokumenRegistrasi = DokumenRegistrasi::where('nim', $nim)->with('mahasiswa')->first();
        if (!$dokumenRegistrasi) {
            return response()->json([
                'message' => 'Dokumen registrasi not found'
            ], 404);
        }
        $dokumenRegistrasi->token = bin2hex(random_bytes(32));
        $dokumenRegistrasi->token_expired = now()->addHours(2);
        $dokumenRegistrasi->save();
        event( new DokReg("update", ["Admin","User.".$nim], $dokumenRegistrasi->isEmpty(), $dokumenRegistrasi));
        return response()->json([
            'message' => 'Successfully retrieved dokumen registrasi',
            'isEmpty' => $dokumenRegistrasi->isEmpty(),
            'data' => $dokumenRegistrasi
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteDokumenRegistrasi $request, $nim)
    {
        try {
            if ($request->user()->isMahasiswa() && $request->user()->mahasiswa()->first()->nim != $nim) {
                return response()->json([
                    'message' => 'NIM not match'
                ], 401);
            }
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if ($mahasiswa->status > 0) {
                return response()->json([
                    'message' => 'Tidak bisa menghapus dokumen registrasi pada mahasiswa yang sudah aktif'
                ], 404);
            }
            $dokumenRegistrasi = DokumenRegistrasi::where('nim', $nim)->first();
            if (!$dokumenRegistrasi || $dokumenRegistrasi->isEmpty()) {
                return response()->json([
                    'message' => 'Dokumen registrasi not found'
                ], 404);
            }
            $deleted = false;
            switch($request->type) {
                case 'KRS':
                    $fileName = $dokumenRegistrasi->krs;
                    $deleted = Storage::disk('local')->delete('public/dokumen_registrasi/krs/' . $fileName);
                    $dokumenRegistrasi->krs = null;
                    break;
                case 'KartuMahasiswa':
                    $fileName = $dokumenRegistrasi->kartu_mahasiswa;
                    $deleted = Storage::disk('local')->delete('public/dokumen_registrasi/kartu_mahasiswa/' . $fileName);
                    $dokumenRegistrasi->kartu_mahasiswa = null;
                    break;
                case 'Transkrip':
                    $fileName = $dokumenRegistrasi->transkrip;
                    $deleted = Storage::disk('local')->delete('public/documen_registrasi/transkrip/' . $fileName);
                    $dokumenRegistrasi->transkrip = null;
                    break;
                case 'BuktiSeminar':
                    $fileName = $dokumenRegistrasi->bukti_seminar;
                    $deleted = Storage::disk('local')->delete('public/dokumen_registrasi/bukti_seminar/' . $fileName);
                    $dokumenRegistrasi->bukti_seminar = null;
                    break;
                default:
                    return response()->json([
                        'message' => 'Invalid type of dokumen registrasi'
                    ], 400);
            }
            if (!$deleted) {
                return response()->json([
                    'message' => 'Failed to delete dokumen registrasi'
                ], 500);
            }
            $dokumenRegistrasi->token = bin2hex(random_bytes(32));
            $dokumenRegistrasi->token_expired = now()->addHours(2);
            $dokumenRegistrasi->save();
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            $mahasiswa->status = 0;
            $mahasiswa->save();
            event( new DokReg("destroy", ["Admin","User.".$nim], $dokumenRegistrasi->isEmpty(), $dokumenRegistrasi));
            event( new Mhs("update", ["Admin","User.".$nim], false, $mahasiswa));
            return response()->json([
                'message' => 'Successfully deleted dokumen registrasi',
                'data' => $dokumenRegistrasi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete dokumen registrasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
