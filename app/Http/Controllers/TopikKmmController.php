<?php

namespace App\Http\Controllers;

use App\Models\TopikKmm;
use Illuminate\Http\Request;
use App\Http\Requests\TopikRequest;
use App\Events\Tpk;

class TopikKmmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topikKmm = TopikKmm::orderBy('nama_topik', 'desc')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan topik KMM',
            'data' => $topikKmm
        ]);
    }

    public function indexMine(Request $request)
    {
        $user = $request->user();
        $dosen = $user->dosen()->first();
        $topikKmm = $dosen->topik()->get();
        return response()->json([
            'message' => 'Berhasil menampilkan topik KMM',
            'data' => $topikKmm
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(TopikRequest $request)
    {
        $topikKmm = new TopikKmm;
        $topikKmm->nama_topik = $request->nama_topik;
        $topikKmm->save();
        event( new Tpk("store", ["Admin","Dosen","Mahasiswa"], false, $topikKmm));
        return response()->json([
            'message' => 'Topik KMM berhasil ditambahkan',
            'data' => $topikKmm
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $topikKmm = TopikKmm::where('id_topik', $id)->first();
        return response()->json([
            'message' => 'Berhasil menampilkan topik KMM',
            'data' => $topikKmm
        ]);
    }

    public function showDosen($id_topik)
    {
        $topikKmm = TopikKmm::where('id_topik', $id_topik)->first();
        $dosen = $topikKmm->dosen()->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan dosen yang mengambil topik KMM '.$topikKmm->nama_topik,
            'data' => $dosen
        ]);
    }

    public function showMagang($id_topik)
    {
        $topikKmm = TopikKmm::where('id_topik', $id_topik)->first();
        $mahasiswa = $topikKmm->magang()->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan magang yang mengambil topik KMM '.$topikKmm->nama_topik,
            'data' => $mahasiswa
        ]);
    }

    public function assign(Request $request)
    {
        $topikKmm = TopikKmm::where('id_topik', $request->id_topik)->first();
        $user = $request->user();
        $dosen = $user->dosen()->first();
        //if dosen already assigned to topik
        if($topikKmm->dosen()->where('dosen.id_dosen', $dosen->id_dosen)->first()){
            return response()->json([
                'message' => 'Topik KMM sudah diambil',
                'data' => $dosen->topik()->get()
            ], 400);
        }
        $topikKmm->dosen()->attach($dosen);
        $topikKmm->save();
        return response()->json([
            'message' => 'Topik KMM berhasil diambil',
            'data' => $dosen->topik()->get()
        ]);
    }

    public function unassign(Request $request)
    {
        $topikKmm = TopikKmm::where('id_topik', $request->id_topik)->first();
        $user = $request->user();
        $dosen = $user->dosen()->first();
        //if dosen already unassigned to topik
        if(!$topikKmm->dosen()->where('dosen.id_dosen', $dosen->id_dosen)->first()){
            return response()->json([
                'message' => 'Topik KMM belum diambil',
                'data' => $dosen->topik()->get()
            ], 400);
        }
        $topikKmm->dosen()->detach($dosen);
        $topikKmm->save();
        return response()->json([
            'message' => 'Topik KMM berhasil dilepas',
            'data' => $dosen->topik()->get()
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $topikKmm = TopikKmm::where('id_topik', $id)->first();
        $topikKmm->nama_topik = $request->nama_topik ?? $topikKmm->nama_topik;
        $topikKmm->save();
        event( new Tpk("update", ["Admin","Dosen","Mahasiswa"], false, $topikKmm));
        return response()->json([
            'message' => 'Topik KMM berhasil diubah',
            'data' => $topikKmm
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $topikKmm = TopikKmm::where('id_topik', $id)->first();
 
        $legacy = $topikKmm->toArray();

        $topikKmm->delete();

        event( new Tpk("destroy", ["Admin","Dosen","Mahasiswa"], true, $legacy));
        return response()->json([
            'message' => 'Topik KMM berhasil dihapus',
            'data' => $legacy
        ]);
    }
}
