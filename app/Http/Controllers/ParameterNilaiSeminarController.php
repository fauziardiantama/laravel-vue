<?php

namespace App\Http\Controllers;

use App\Models\ParameterNilaiSeminar;
use Illuminate\Http\Request;

class ParameterNilaiSeminarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = request()->order ?: 'id_parameter';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = ParameterNilaiSeminar::query();
        $query->orderBy($order, $sort);

        $query->where(function ($query){
            $query->where('parameter', 'like', '%'.request()->kueri.'%')
            ->orWhere('tahun', 'like', '%'.request()->kueri.'%');
        });

        $parameterNilaiSeminar = $query->paginate($limit);

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $parameterNilaiSeminar
        ]);
    }
    public function year($tahun)
    {
        $parameterNilaiBimbingan = ParameterNilaiSeminar::where('tahun', $tahun)->get();

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $parameterNilaiBimbingan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'parameter' => 'required',
            'tahun' => 'required'
        ]);

        $parameterNilaiSeminar = ParameterNilaiSeminar::create([
            'parameter' => $request->parameter,
            'tahun' => $request->tahun
        ]);

        if($parameterNilaiSeminar) {
            return response()->json([
                'message' => 'Parameter nilai seminar berhasil ditambahkan',
                'data' => $parameterNilaiSeminar
            ]);
        } else {
            return response()->json([
                'message' => 'Parameter nilai seminar gagal ditambahkan',
                'data' => null
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $parameterNilaiSeminar = ParameterNilaiSeminar::find($id);

        if($parameterNilaiSeminar) {
            return response()->json([
                'message' => 'Berhasil menampilkan data',
                'data' => $parameterNilaiSeminar
            ]);
        } else {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'parameter' => 'required',
            'tahun' => 'required'
        ]);

        $parameterNilaiSeminar = ParameterNilaiSeminar::find($id);
        $parameterNilaiSeminar->parameter = $request->parameter;
        $parameterNilaiSeminar->tahun = $request->tahun;
        $parameterNilaiSeminar->save();

        return response()->json([
            'message' => 'Berhasil mengubah data',
            'data' => $parameterNilaiSeminar
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $parameterNilaiSeminar = ParameterNilaiSeminar::find($id);
        $parameterNilaiSeminar->delete();

        return response()->json([
            'message' => 'Berhasil menghapus data',
            'data' => $parameterNilaiSeminar
        ]);
    }
}
