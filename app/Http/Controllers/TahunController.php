<?php

namespace App\Http\Controllers;

use App\Http\Requests\TahunRequest;
use App\Models\Tahun;
use Illuminate\Http\Request;

class TahunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahun = Tahun::orderBy('tahun', 'desc')->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan tahun',
            'data' => $tahun
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TahunRequest $request)
    {
        $tahun = new Tahun;
        $tahun->tahun = $request->tahun;
        $tahun->save();
        return response()->json([
            'message' => 'Berhasil menambahkan tahun',
            'data' => $tahun
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tahun $tahun)
    {
        $legacy = $tahun;
        $tahun->delete();
        return response()->json([
            'message' => 'Berhasil menghapus tahun',
            'data' => $legacy
        ]);
    }
}
