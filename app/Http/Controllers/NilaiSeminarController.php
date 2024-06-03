<?php

namespace App\Http\Controllers;

use App\Models\NilaiSeminar;
use Illuminate\Http\Request;

class NilaiSeminarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->user()->isMahasiswa()) {
            $user = request()->user();
            $magang = $user->mahasiswa()->first()->magang()->first()->nilaiSeminar()->with('parameterNilaiSeminar','dosen')->get();
            return response()->json([
                'message' => 'success',
                'data' => $magang
            ]);
        } else {
            $order = request()->order ?: 'id_nilai_bimbingan';
            $sort = request()->sort ?: 'desc';
            $limit = request()->limit ?: 10;

            $query = NilaiSeminar::query();
            $query->orderBy($order, $sort);

            $query->where(function ($query){
                $query->where('id_magang', 'like', '%'.request()->kueri.'%')
                ->orWhere('id_parameter', 'like', '%'.request()->kueri.'%');
            });

            $nilaiBimbingan = $query->paginate($limit);

            return response()->json([
                'message' => 'Berhasil menampilkan data',
                'data' => $nilaiBimbingan
            ]);
        }
    }

    public function magang($id_magang)
    {
        $order = request()->order ?: 'id_nilai_bimbingan';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = NilaiSeminar::query();
        $query->orderBy($order, $sort);

        $query->where('id_magang', $id_magang);

        $nilaiBimbingan = $query->with('parameterNilaiSeminar','dosen')->paginate($limit);

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $nilaiBimbingan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_magang' => 'required',
            'id_parameter' => 'required',
            'nilai' => 'required',
        ]);

        $user = request()->user();
        $dosen = $user->dosen()->first();

        $nilaiBimbingan = NilaiSeminar::create([
            'id_magang' => $request->id_magang,
            'id_parameter' => $request->id_parameter,
            'nilai' => $request->nilai,
            'id_dosen' => $dosen->id_dosen
        ]);

        if($nilaiBimbingan) {
            return response()->json([
                'message' => 'Nilai bimbingan berhasil ditambahkan',
                'data' => $nilaiBimbingan
            ]);
        } else {
            return response()->json([
                'message' => 'Nilai bimbingan gagal ditambahkan',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(NilaiSeminar $nilaiSeminar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NilaiSeminar $nilaiSeminar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nilai' => 'required'
        ]);

        $nilai = NilaiSeminar::find($id);

        if (!$nilai) {
            return response()->json([
                'message' => 'Nilai seminar tidak ditemukan'
            ], 404);
        }

        $nilai->nilai = $request->nilai;

        if ($nilai->save()) {
            return response()->json([
                'message' => 'Nilai seminar berhasil diubah',
                'data' => $nilai
            ]);
        } else {
            return response()->json([
                'message' => 'Nilai seminar gagal diubah'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $nilai = NilaiSeminar::find($id);

        if (!$nilai) {
            return response()->json([
                'message' => 'Nilai seminar tidak ditemukan'
            ], 404);
        }

        if ($nilai->delete()) {
            return response()->json([
                'message' => 'Nilai seminar berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'message' => 'Nilai seminar gagal dihapus'
            ], 400);
        }
    }
}
