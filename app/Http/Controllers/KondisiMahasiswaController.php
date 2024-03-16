<?php

namespace App\Http\Controllers;

use App\Models\KondisiMahasiswa;
use Illuminate\Http\Request;

class KondisiMahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kondisiMahasiswa = KondisiMahasiswa::orderBy('kondisi', 'desc')->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan kondisi mahasiswa',
            'data' => $kondisiMahasiswa
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kondisiMahasiswa = new KondisiMahasiswa;
        $kondisiMahasiswa->nomor_induk_mahasiswa = $request->nomor_induk_mahasiswa;
        $kondisiMahasiswa->nama_lengkap = $request->nama_lengkap;
        $kondisiMahasiswa->fakultas = $request->fakultas;
        $kondisiMahasiswa->program_studi = $request->program_studi;
        $kondisiMahasiswa->nomor_telepon = $request->nomor_telepon;
        $kondisiMahasiswa->email_SSO = $request->email_SSO;
        $kondisiMahasiswa->alamat_asal_jalan_dan_nomor_rumah = $request->alamat_asal_jalan_dan_nomor_rumah;
        $kondisiMahasiswa->alamat_asal_RT_RW = $request->alamat_asal_RT_RW;
        $kondisiMahasiswa->alamat_asal_kelurahan = $request->alamat_asal_kelurahan;
        $kondisiMahasiswa->alamat_asal_kabupaten_kota = $request->alamat_asal_kabupaten_kota;
        $kondisiMahasiswa->alamat_asal_provinsi = $request->alamat_asal_provinsi;
        $kondisiMahasiswa->alamat_di_solo = $request->alamat_di_solo;
        $kondisiMahasiswa->alamat_solo_jalan_dan_nomor_rumah = $request->alamat_solo_jalan_dan_nomor_rumah;
        $kondisiMahasiswa->alamat_solo_RT_RW = $request->alamat_solo_RT_RW;
        $kondisiMahasiswa->alamat_solo_kelurahan = $request->alamat_solo_kelurahan;
        $kondisiMahasiswa->alamat_solo_kecamatan = $request->alamat_solo_kecamatan;
        $kondisiMahasiswa->alamat_solo_kabupaten_kota = $request->alamat_solo_kabupaten_kota;
        $kondisiMahasiswa->alamat_solo_provinsi = $request->alamat_solo_provinsi;
        $kondisiMahasiswa->alamat_saat_isi = $request->alamat_saat_isi;
        $kondisiMahasiswa->alamat_saat_isi_jalan_dan_nomor_rumah = $request->alamat_saat_isi_jalan_dan_nomor_rumah;
        $kondisiMahasiswa->alamat_saat_isi_RT_RW = $request->alamat_saat_isi_RT_RW;
        $kondisiMahasiswa->alamat_saat_isi_kelurahan = $request->alamat_saat_isi_kelurahan;
        $kondisiMahasiswa->alamat_saat_isi_kecamatan = $request->alamat_saat_isi_kecamatan;
        $kondisiMahasiswa->alamat_saat_isi_kabupaten_kota = $request->alamat_saat_isi_kabupaten_kota;
        $kondisiMahasiswa->alamat_saat_isi_provinsi = $request->alamat_saat_isi_provinsi;
        $kondisiMahasiswa->tanggal_mulai_tinggal_alamat_sekarang = $request->tanggal_mulai_tinggal_alamat_sekarang;
        $kondisiMahasiswa->moda_dipakai_meninggalkan_solo_ke_alamat_sekarang = $request->moda_dipakai_meninggalkan_solo_ke_alamat_sekarang;
        $kondisiMahasiswa->keadaan_sekarang = $request->keadaan_sekarang;
        $kondisiMahasiswa->sakit_keterangan = $request->sakit_keterangan;
        $kondisiMahasiswa->sakit_status_periksa = $request->sakit_status_periksa;
        $kondisiMahasiswa->sakit_periksa_diagnosa_saran_dokter = $request->sakit_periksa_diagnosa_saran_dokter;
        $kondisiMahasiswa->tanggal_submit = $request->tanggal_submit;
        $kondisiMahasiswa->save();

        return response()->json([
            'message' => 'Kondisi mahasiswa berhasil ditambahkan',
            'data' => $kondisiMahasiswa
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kondisiMahasiswa = KondisiMahasiswa::where('id', $id)->first();
        return response()->json([
            'message' => 'Berhasil menampilkan kondisi mahasiswa',
            'data' => $kondisiMahasiswa
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kondisiMahasiswa = KondisiMahasiswa::where('id', $id)->first();
        $kondisiMahasiswa->nomor_induk_mahasiswa = $request->nomor_induk_mahasiswa;
        $kondisiMahasiswa->nama_lengkap = $request->nama_lengkap;
        $kondisiMahasiswa->fakultas = $request->fakultas;
        $kondisiMahasiswa->program_studi = $request->program_studi;
        $kondisiMahasiswa->nomor_telepon = $request->nomor_telepon;
        $kondisiMahasiswa->email_SSO = $request->email_SSO;
        $kondisiMahasiswa->alamat_asal_jalan_dan_nomor_rumah = $request->alamat_asal_jalan_dan_nomor_rumah;
        $kondisiMahasiswa->alamat_asal_RT_RW = $request->alamat_asal_RT_RW;
        $kondisiMahasiswa->alamat_asal_kelurahan = $request->alamat_asal_kelurahan;
        $kondisiMahasiswa->alamat_asal_kabupaten_kota = $request->alamat_asal_kabupaten_kota;
        $kondisiMahasiswa->alamat_asal_provinsi = $request->alamat_asal_provinsi;
        $kondisiMahasiswa->alamat_di_solo = $request->alamat_di_solo;
        $kondisiMahasiswa->alamat_solo_jalan_dan_nomor_rumah = $request->alamat_solo_jalan_dan_nomor_rumah;
        $kondisiMahasiswa->alamat_solo_RT_RW = $request->alamat_solo_RT_RW;
        $kondisiMahasiswa->alamat_solo_kelurahan = $request->alamat_solo_kelurahan;
        $kondisiMahasiswa->alamat_solo_kecamatan = $request->alamat_solo_kecamatan;
        $kondisiMahasiswa->alamat_solo_kabupaten_kota = $request->alamat_solo_kabupaten_kota;
        $kondisiMahasiswa->alamat_solo_provinsi = $request->alamat_solo_provinsi;
        $kondisiMahasiswa->alamat_saat_isi = $request->alamat_saat_isi;
        $kondisiMahasiswa->alamat_saat_isi_jalan_dan_nomor_rumah = $request->alamat_saat_isi_jalan_dan_nomor_rumah;
        $kondisiMahasiswa->alamat_saat_isi_RT_RW = $request->alamat_saat_isi_RT_RW;
        $kondisiMahasiswa->alamat_saat_isi_kelurahan = $request->alamat_saat_isi_kelurahan;
        $kondisiMahasiswa->alamat_saat_isi_kecamatan = $request->alamat_saat_isi_kecamatan;
        $kondisiMahasiswa->alamat_saat_isi_kabupaten_kota = $request->alamat_saat_isi_kabupaten_kota;
        $kondisiMahasiswa->alamat_saat_isi_provinsi = $request->alamat_saat_isi_provinsi;
        $kondisiMahasiswa->tanggal_mulai_tinggal_alamat_sekarang = $request->tanggal_mulai_tinggal_alamat_sekarang;
        $kondisiMahasiswa->moda_dipakai_meninggalkan_solo_ke_alamat_sekarang = $request->moda_dipakai_meninggalkan_solo_ke_alamat_sekarang;
        $kondisiMahasiswa->keadaan_sekarang = $request->keadaan_sekarang;
        $kondisiMahasiswa->sakit_keterangan = $request->sakit_keterangan;
        $kondisiMahasiswa->sakit_status_periksa = $request->sakit_status_periksa;
        $kondisiMahasiswa->sakit_periksa_diagnosa_saran_dokter = $request->sakit_periksa_diagnosa_saran_dokter;
        $kondisiMahasiswa->tanggal_submit = $request->tanggal_submit;
        $kondisiMahasiswa->save();

        return response()->json([
            'message' => 'Kondisi mahasiswa berhasil diubah',
            'data' => $kondisiMahasiswa
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kondisiMahasiswa = KondisiMahasiswa::where('id', $id)->first();
        $kondisiMahasiswa->delete();
        return response()->json([
            'message' => 'Kondisi mahasiswa berhasil dihapus',
            'data' => $kondisiMahasiswa
        ]);
    }
}
