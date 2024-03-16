<?php

namespace App\Http\Controllers;

use App\Models\SesiUjian;
use Illuminate\Http\Request;

class SesiUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sesiUjian = SesiUjian::orderBy('id', 'desc')->get();
        if ($sesiUjian->isEmpty()) {
            return response()->json([
                'message' => 'Data Sesi Ujian Kosong'
            ], 404);
        }
        return response()->json([
            'message' => 'Daftar Sesi Ujian',
            'data'    => $sesiUjian
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sesiUjian = new SesiUjian;
        $sesiUjian->no_sesi = $request->no_sesi;
        $sesiUjian->waktu_mulai = $request->waktu_mulai;
        $sesiUjian->waktu_selesai = $request->waktu_selesai;
        if ($sesiUjian->save()) {
            return response()->json([
                'message' => 'Sesi Ujian Berhasil Ditambahkan',
                'data'    => $sesiUjian
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SesiUjian $sesiUjian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sesiUjian = SesiUjian::find($id);
        $sesiUjian->no_sesi = $request->no_sesi;
        $sesiUjian->waktu_mulai = $request->waktu_mulai;
        $sesiUjian->waktu_selesai = $request->waktu_selesai;
        $sesiUjian->save();
        if ($sesiUjian->save()) {
            return response()->json([
                'message' => 'Sesi Ujian Berhasil Diubah',
                'data'    => $sesiUjian
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sesiUjian = SesiUjian::find($id);
        $legacy = $sesiUjian->toArray();
        if ($sesiUjian->delete()) {
            return response()->json([
                'message' => 'Sesi Ujian Berhasil Dihapus',
                'data'    => $legacy
            ], 200);
        }
    }
}
