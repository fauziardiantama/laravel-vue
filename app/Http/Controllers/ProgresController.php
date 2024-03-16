<?php

namespace App\Http\Controllers;

use App\Events\Prgs;
use App\Models\Progres;
use Illuminate\Http\Request;

class ProgresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $progres = Progres::all();
        return response()->json([
            'message' => 'Daftar data progres',
            'data' => $progres
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $progres = Progres::find($id);
        if ($progres) {
            return response()->json([
                'message' => 'Detail data progres',
                'data' => $progres
            ]);
        } else {
            return response()->json([
                'message' => 'Data progres tidak ditemukan'
            ], 404);
        }
    }

    public function showMagang($id)
    {
        $progres = Progres::where('id_progres',$id)->first();
        $magang = $progres->magang()->paginate(10);

        return response()->json([
            'message' => 'Berhasil menampilkan magang dari progres '.$progres->progres,
            'data' => $magang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $progres = Progres::find($id);
        if ($progres) {
            $progres->progres = $request->progres;
            $progres->save();
            event(new Prgs("update", ["Admin", "Dosen", "Mahasiswa"], false, $progres));
            return response()->json([
                'message' => 'Data progres berhasil diubah',
                'data' => $progres
            ]);
        } else {
            return response()->json([
                'message' => 'Data progres tidak ditemukan'
            ], 404);
        }
    }
}
