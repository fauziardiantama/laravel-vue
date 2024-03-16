<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fakultas = Fakultas::orderBy('nama_fakultas', 'asc')->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan fakultas',
            'data' => $fakultas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fakultas = new Fakultas;
        $fakultas->kode_fakultas = $request->kode_fakultas;
        $fakultas->nama_fakultas = $request->nama_fakultas;
        $fakultas->singkatan = $request->singkatan;
        $fakultas->save();
        return response()->json([
            'message' => 'Berhasil menambahkan fakultas',
            'data' => $fakultas
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $fakultas = Fakultas::find($id);
        return response()->json([
            'message' => 'Berhasil menampilkan fakultas',
            'data' => $fakultas
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fakultas = Fakultas::find($id);
        $fakultas->kode_fakultas = $request->kode_fakultas;
        $fakultas->nama_fakultas = $request->nama_fakultas;
        $fakultas->singkatan = $request->singkatan;
        $fakultas->save();
        return response()->json([
            'message' => 'Berhasil mengubah fakultas',
            'data' => $fakultas
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fakultas = Fakultas::find($id);
        $fakultas->delete();
        return response()->json([
            'message' => 'Berhasil menghapus fakultas',
            'data' => $fakultas
        ]);
    }
}
