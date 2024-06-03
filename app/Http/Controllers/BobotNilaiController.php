<?php

namespace App\Http\Controllers;

use App\Models\BobotNilai;
use Illuminate\Http\Request;

class BobotNilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = request()->order ?: 'id_bobot';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = BobotNilai::query();
        $query->orderBy($order, $sort);

        $query->where(function ($query){
            $query->where('jenis_nilai', 'like', '%'.request()->kueri.'%')
            ->orWhere('persentase', 'like', '%'.request()->kueri.'%')
            ->orWhere('tahun', 'like', '%'.request()->kueri.'%');
        });

        $bobotNilai = $query->paginate($limit);

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $bobotNilai
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'jenis_nilai' => 'required',
            'persentase' => 'required',
            'tahun' => 'required'
        ]);

        $bobotNilai = BobotNilai::create([
            'jenis_nilai' => $request->jenis_nilai,
            'persentase' => $request->persentase,
            'tahun' => $request->tahun
        ]);

        if($bobotNilai) {
            return response()->json([
                'message' => 'Bobot nilai berhasil ditambahkan',
                'data' => $bobotNilai
            ]);
        } else {
            return response()->json([
                'message' => 'Bobot nilai gagal ditambahkan',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bobotNilai = BobotNilai::find($id);
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $bobotNilai
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'jenis_nilai' => 'required',
            'persentase' => 'required',
            'tahun' => 'required'
        ]);

        $bobotNilai = BobotNilai::find($id);
        $bobotNilai->jenis_nilai = $request->jenis_nilai;
        $bobotNilai->persentase = $request->persentase;
        $bobotNilai->tahun = $request->tahun;
        $bobotNilai->save();

        return response()->json([
            'message' => 'Bobot nilai berhasil diubah',
            'data' => $bobotNilai
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bobotNilai = BobotNilai::find($id);
        $bobotNilai->delete();

        return response()->json([
            'message' => 'Bobot nilai berhasil dihapus'
        ]);
    }
}
