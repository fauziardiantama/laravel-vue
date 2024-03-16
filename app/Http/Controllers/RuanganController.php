<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ruangan = Ruangan::orderBy('id', 'desc')->get();
        if ($ruangan->isEmpty()) {
            return response()->json([
                'message' => 'Data Ruangan Kosong'
            ], 404);
        }
        return response()->json([
            'message' => 'Daftar Ruangan',
            'data'    => $ruangan
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ruangan = new Ruangan;
        $ruangan->nama = $request->nama;

        if ($ruangan->save()) {
            return response()->json([
                'message' => 'Ruangan Berhasil Ditambahkan',
                'data'    => $ruangan
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ruangan $ruangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $ruangan = Ruangan::find($id);
        $ruangan->nama = $request->nama;
        $ruangan->save();

        if ($ruangan->save()) {
            return response()->json([
                'message' => 'Ruangan Berhasil Diupdate',
                'data'    => $ruangan
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ruangan = Ruangan::find($id);
        $ruangan->delete();

        if ($ruangan->delete()) {
            return response()->json([
                'message' => 'Ruangan Berhasil Dihapus',
                'data'    => $ruangan
            ], 200);
        }
    }
}
