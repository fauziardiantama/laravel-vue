<?php

namespace App\Http\Controllers;

use App\Models\ParameterNilaiInstansi;
use Illuminate\Http\Request;

class ParameterNilaiInstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = request()->order ?: 'id_parameter';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = ParameterNilaiInstansi::query();
        $query->orderBy($order, $sort);

        $query->where(function ($query){
            $query->where('parameter', 'like', '%'.request()->kueri.'%')
            ->orWhere('tahun', 'like', '%'.request()->kueri.'%');
        });

        $parameterNilaiInstansi = $query->paginate($limit);

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $parameterNilaiInstansi
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

        $parameterNilaiInstansi = ParameterNilaiInstansi::create([
            'parameter' => $request->parameter,
            'tahun' => $request->tahun
        ]);

        if($parameterNilaiInstansi) {
            return response()->json([
                'message' => 'Parameter nilai instansi berhasil ditambahkan',
                'data' => $parameterNilaiInstansi
            ]);
        } else {
            return response()->json([
                'message' => 'Parameter nilai instansi gagal ditambahkan',
                'data' => null
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $parameterNilaiInstansi = ParameterNilaiInstansi::find($id);
        if($parameterNilaiInstansi) {
            return response()->json([
                'message' => 'Berhasil menampilkan data',
                'data' => $parameterNilaiInstansi
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

        $parameterNilaiInstansi = ParameterNilaiInstansi::find($id);

        if($parameterNilaiInstansi) {
            $parameterNilaiInstansi->parameter = $request->parameter;
            $parameterNilaiInstansi->tahun = $request->tahun;

            $parameterNilaiInstansi->save();
            return response()->json([
                'message' => 'Parameter nilai instansi berhasil diubah',
                'data' => $parameterNilaiInstansi
            ]);
        } else {
            return response()->json([
                'message' => 'Parameter nilai instansi gagal diubah',
                'data' => null
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $parameterNilaiInstansi = ParameterNilaiInstansi::find($id);

        if($parameterNilaiInstansi) {
            $parameterNilaiInstansi->delete();
            return response()->json([
                'message' => 'Parameter nilai instansi berhasil dihapus',
            ]);
        } else {
            return response()->json([
                'message' => 'Parameter nilai instansi gagal dihapus',
            ], 500);
        }
    }
}
