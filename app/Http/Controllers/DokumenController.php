<?php

namespace App\Http\Controllers;

use App\Models\DokumenRegistrasi;
use App\Models\ProposalTA;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function kartuMahasiswa(Request $request, $file) {
        $file = DokumenRegistrasi::where('kartu_mahasiswa', $file)->first();
        if ($file) {
            if ($request->user()->isMahasiswa() && ($file->nim != $request->user()->nim)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            $path = storage_path('app/public/dokumen_registrasi/kartu_mahasiswa/' . $file->kartu_mahasiswa);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function KRS(Request $request, $file) {
        $file = DokumenRegistrasi::where('krs', $file)->first();
        if ($file) {
            if ($request->user()->isMahasiswa() && ($file->nim != $request->user()->nim)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            $path = storage_path('app/public/dokumen_registrasi/krs/' . $file->krs);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function transkripNilai(Request $request, $file) {
        $file = DokumenRegistrasi::where('transkrip_nilai', $file)->first();
        if ($file) {
            if ($request->user()->isMahasiswa() && ($file->nim != $request->user()->nim)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            $path = storage_path('app/public/dokumen_registrasi/transkrip_nilai/' . $file->transkrip_nilai);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function buktiSeminar(Request $request, $file) {
        $file = DokumenRegistrasi::where('bukti_seminar', $file)->first();
        if ($file) {
            if ($request->user()->isMahasiswa() && ($file->nim != $request->user()->nim)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            $path = storage_path('app/public/dokumen_registrasi/bukti_seminar/' . $file->bukti_seminar);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function proposalTA(Request $request, $file) {
        $file = ProposalTA::where('file_proposal', $file)->first();
        if ($file) {
            if ($request->user()->isMahasiswa() && ($file->nim != $request->user()->nim)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            $path = storage_path('app/public/proposal_ta/' . $file->proposal_ta);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }
}
