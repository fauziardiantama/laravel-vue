<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programStudi = ProgramStudi::orderBy('nama_program_studi', 'asc')->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan program studi',
            'data' => $programStudi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $programStudi = new ProgramStudi;
        $programStudi->kode_prodi = $request->kode_prodi;
        $programStudi->nama_prodi = $request->nama_prodi;
        $programStudi->id_fakultas = $request->id_fakultas;
        $programStudi->save();
        return response()->json([
            'message' => 'Berhasil menambahkan program studi',
            'data' => $programStudi
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $programStudi = ProgramStudi::find($id);
        return response()->json([
            'message' => 'Berhasil menampilkan program studi',
            'data' => $programStudi
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $programStudi = ProgramStudi::find($id);
        $programStudi->kode_prodi = $request->kode_prodi;
        $programStudi->nama_prodi = $request->nama_prodi;
        $programStudi->id_fakultas = $request->id_fakultas;
        $programStudi->save();
        return response()->json([
            'message' => 'Berhasil mengubah program studi',
            'data' => $programStudi
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $programStudi = ProgramStudi::find($id);
        $programStudi->delete();
        return response()->json([
            'message' => 'Berhasil menghapus program studi',
            'data' => $programStudi
        ]);
    }
}
