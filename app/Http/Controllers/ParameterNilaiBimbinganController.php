<?php

namespace App\Http\Controllers;

use App\Models\ParameterNilaiBimbingan;
use Illuminate\Http\Request;

class ParameterNilaiBimbinganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = request()->order ?: 'id_parameter';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = ParameterNilaiBimbingan::query();
        $query->orderBy($order, $sort);

        $query->where(function ($query){
            $query->where('parameter', 'like', '%'.request()->kueri.'%')
            ->orWhere('tahun', 'like', '%'.request()->kueri.'%');
        });

        $parameterNilaiBimbingan = $query->paginate($limit);

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $parameterNilaiBimbingan
        ]);
    }

    public function year($tahun)
    {
        $parameterNilaiBimbingan = ParameterNilaiBimbingan::where('tahun', $tahun)->get();

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

        $parameterNilaiBimbingan = ParameterNilaiBimbingan::create([
            'parameter' => $request->parameter,
            'tahun' => $request->tahun
        ]);

        if($parameterNilaiBimbingan) {
            return response()->json([
                'message' => 'Parameter nilai bimbingan berhasil ditambahkan',
                'data' => $parameterNilaiBimbingan
            ]);
        } else {
            return response()->json([
                'message' => 'Parameter nilai bimbingan gagal ditambahkan',
                'data' => null
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $parameterNilaiBimbingan = ParameterNilaiBimbingan::find($id);

        if($parameterNilaiBimbingan) {
            return response()->json([
                'message' => 'Berhasil menampilkan data',
                'data' => $parameterNilaiBimbingan
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

        $parameterNilaiBimbingan = ParameterNilaiBimbingan::find($id);
        $parameterNilaiBimbingan->parameter = $request->parameter;
        $parameterNilaiBimbingan->tahun = $request->tahun;
        $parameterNilaiBimbingan->save();

        if($parameterNilaiBimbingan) {
            return response()->json([
                'message' => 'Parameter nilai bimbingan berhasil diubah',
                'data' => $parameterNilaiBimbingan
            ]);
        } else {
            return response()->json([
                'message' => 'Parameter nilai bimbingan gagal diubah',
                'data' => null
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $parameterNilaiBimbingan = ParameterNilaiBimbingan::find($id);
        $parameterNilaiBimbingan->delete();

        if($parameterNilaiBimbingan) {
            return response()->json([
                'message' => 'Parameter nilai bimbingan berhasil dihapus',
                'data' => $parameterNilaiBimbingan
            ]);
        } else {
            return response()->json([
                'message' => 'Parameter nilai bimbingan gagal dihapus',
                'data' => null
            ]);
        }
    }
}
