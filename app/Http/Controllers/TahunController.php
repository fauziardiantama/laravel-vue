<?php

namespace App\Http\Controllers;

use App\Http\Requests\TahunRequest;
use App\Models\Tahun;
use Illuminate\Http\Request;
use App\Events\Thn;

class TahunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahun = Tahun::orderBy('tahun', 'desc')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan tahun',
            'data' => $tahun
        ]);
    }

    public function showProposalTA($tahun)
    {
        $tahun = Tahun::where('tahun',$tahun)->first();
        $proposal = $tahun->proposalTA()->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan proposal TA tahun '.$tahun->tahun,
            'data' => $proposal
        ]);
    }

    public function showMagang($tahun)
    {
        $tahun = Tahun::where('tahun',$tahun)->first();
        $magang = $tahun->magang()->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan magang tahun '.$tahun->tahun,
            'data' => $magang
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
        // send #EVENT TahunUpdated to Mahasiswa dan Admin
        $tahunnew = Tahun::orderBy('tahun', 'desc')->get();
        event( new Thn("store", ["Admin","Mahasiswa"], false, $tahunnew));
        return response()->json([
            'message' => 'Berhasil menambahkan tahun',
            'data' => $tahunnew
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tahun $tahun)
    {
        $tahun->delete();
        // send #EVENT TahunUpdated to Mahasiswa dan Admin
        $tahunnew = Tahun::orderBy('tahun', 'desc')->get();
        event( new Thn("destroy", ["Admin","Mahasiswa"], false, $tahunnew));
        return response()->json([
            'message' => 'Berhasil menghapus tahun',
            'data' => $tahunnew
        ]);
    }
}
