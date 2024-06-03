<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenRegistrasi;
use App\Models\ProposalTA;
use App\Models\SuratMagang;
use App\Models\SuratJawaban;

class DokumenController extends Controller
{
    public function getProposal(Request $request, $nim) {
        $proposal = ProposalTA::where('nim', $nim)->first();
        if ($proposal) {
            //if request from mahasiswa
            if ($request->user()->isMahasiswa() && $request->user()->mahasiswa()->first()->nim != $nim) {
                return response()->json([
                    'message' => 'Data proposal tidak ditemukan'
                ], 404);
            }
            //fill the token and token_expired 2 jam from now
            $proposal->token = bin2hex(random_bytes(32));
            $proposal->token_expired = now()->addHours(2);
            $proposal->save();
            return response()->json([
                'message' => 'Berhasil mengambil file proposal TA',
                'data' => [
                    'judul_proposal' => $proposal->judul_proposal,
                    'file_proposal' => $proposal->file_proposal,
                    'token' => $proposal->token,
                    'token_expired' => $proposal->token_expired
                ]
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data proposal tidak ditemukan'
            ], 404);
        }
    }
    public function getDokumenRegistrasi(Request $request, $nim) {
        $dokumen = DokumenRegistrasi::where('nim', $nim)->first();
        if ($dokumen) {
            //if request from mahasiswa
            if ($request->user()->isMahasiswa() && $request->user()->mahasiswa()->first()->nim != $nim) {
                return response()->json([
                    'message' => 'Data dokumen registrasi tidak ditemukan'
                ], 404);
            }
            //fill the token and token_expired 2 jam from now
            $dokumen->token = bin2hex(random_bytes(32));
            $dokumen->token_expired = now()->addHours(2);
            $dokumen->save();
            return response()->json([
                'message' => 'Berhasil mengambil dokumen registrasi',
                'data' => [
                    'krs' => $dokumen->krs,
                    'transkrip' => $dokumen->transkrip,
                    'bukti_seminar' => $dokumen->bukti_seminar,
                    'kartu_mahasiswa' => $dokumen->kartu_mahasiswa,
                    'token' => $dokumen->token,
                    'token_expired' => $dokumen->token_expired
                ]
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data dokumen registrasi tidak ditemukan'
            ], 404);
        }
    }
    public function showKartuMahasiswa($token, $file) {
        $file = DokumenRegistrasi::where('kartu_mahasiswa', $file)->where('token', $token)->first();
        if ($file && $file->token_expired > now()) {
            $path = storage_path('app/public/dokumen_registrasi/kartu_mahasiswa/'. $file->kartu_mahasiswa);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function showKRS($token, $file) {
        $file = DokumenRegistrasi::where('krs', $file)->where('token', $token)->first();
        if ($file && $file->token_expired > now()) {
            $path = storage_path('app/public/dokumen_registrasi/krs/' . $file->krs);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function showTranskripNilai($token, $file) {
        $file = DokumenRegistrasi::where('transkrip_nilai', $file)->where('token', $token)->first();
        if ($file && $file->token_expired > now()) {
            $path = storage_path('app/public/dokumen_registrasi/transkrip_nilai/' . $file->transkrip_nilai);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function showBuktiSeminar($token, $file) {
        $file = DokumenRegistrasi::where('bukti_seminar', $file)->where('token', $token)->first();
        if ($file && $file->token_expired > now()) {
            $path = storage_path('app/public/dokumen_registrasi/bukti_seminar/' . $file->bukti_seminar);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function showProposalTA($token, $file) {
        $file = ProposalTA::where('file_proposal', $file)->where('token', $token)->first();
        if ($file && $file->token_expired > now()) {
            $path = storage_path('app/public/proposal_ta/' . $file->file_proposal);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function showSuratPengantar($token, $file) {
        $file = SuratMagang::where('file_surat', $file)->where('token', $token)->where('jenis_surat',1)->first();
        if ($file && $file->token_expired > now()) {
            $path = storage_path('app/public/surat_magang/pengantar/' . $file->file_surat);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function showSuratSerahTerima($token, $file) {

        $file = SuratMagang::where('file_surat', $file)->where('token', $token)->where('jenis_surat',2)->first();

        if ($file && $file->token_expired > now()) {
            $path = storage_path('app/public/surat_magang/serah_terima/' . $file->file_surat);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function showSuratSerahTerimaNew($file)
    {

        $path = storage_path('app/public/surat_magang/serah_terima_2024/' . $file);
        return response()->file($path);
    }

    public function showTemplateSerahTerima()
    {
        $path = storage_path('app/public/template/blank_surat_serah_terima.rtf');
        return response()->file($path);
    }

    public function showSuratJawaban($token, $file) {
        $file = SuratJawaban::where('file_surat', $file)->where('token', $token)->first();
        if ($file && $file->token_expired > now()) {
            $path = storage_path('app/public/surat_jawaban/' . $file->file_surat);
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File tidak ditemukan'
            ], 404);
        }
    }

    public function showDaftarHadirSeminar($file) {
        $path = storage_path('app/public/seminar/daftar_hadir/' . $file);
        return response()->file($path);
    }

    public function showDraftKMM($file) {
        $path = storage_path('app/public/seminar/draft_kmm/' . $file);
        return response()->file($path);
    }

    public function showFotoSeminar($file) {
        $path = storage_path('app/public/seminar/foto/' . $file);
        return response()->file($path);
    }

    public function showKRSI($file) {
        $path = storage_path('app/public/seminar/krs/' . $file);
        return response()->file($path);
    }

    public function showLembarRevisi($file) {
        $path = storage_path('app/public/seminar/lembar_revisi/' . $file);
        return response()->file($path);
    }

    public function showSelesaiKMM($file) {
        $path = storage_path('app/public/seminar/selesai_kmm/' . $file);
        return response()->file($path);
    }
}
