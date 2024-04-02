<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Http\Requests\InstansiRequest;
use App\Models\Magang;
use App\Events\Inst;

class InstansiController extends Controller
{
    public function indexAll()
    {
        $instansi = Instansi::where('status_instansi',1)->orderBy('nama', 'desc')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan instansi',
            'data' => $instansi
        ]);
    }
    /**
     * Display a listing of the resource.
     */
        // public function index()
        // {
        //     $order = request()->order ?: 'id_instansi';
        //     $sort = request()->sort ?: 'asc';
        //     $limit = request()->limit ?: 10;
        //     $instansi = Instansi::orderBy($order, $sort)->paginate($limit);
        //     return response()->json([
        //         'message' => 'Berhasil menampilkan instansi',
        //         'data' => $instansi
        //     ]);
    // }
    
    public function index(Request $request)
    {
        $statusMapping = [
            "disetujui" => 1,
            "diterima" => 1,
            "ditolak" => -1,
            "menunggu" => 0,
            "Menunggu" => 0,
            "Ditolak" => -1,
            "Diterima" => 1,
            "Disetujui" => 1
        ];
    
        $order = $request->order ?: 'id_instansi';
        $sort = $request->sort ?: 'asc';
        $limit = $request->limit ?: 10;
        if (array_key_exists($request->kueri, $statusMapping)) {
            $instansi = Instansi::where('status_instansi', $statusMapping[$request->kueri])
            ->orderBy($order, $sort)
            ->paginate($limit);
        } else {
            $instansi = Instansi::where('nama', 'like', '%'.$request->kueri.'%')
            ->orWhere('no_telp', 'like', '%'.$request->kueri.'%')
            ->orderBy($order, $sort)
            ->paginate($limit);
        }
        return response()->json([
            'message' => 'Berhasil menampilkan instansi',
            'data' => $instansi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstansiRequest $request)
    {
        $instansi = new Instansi;
        $instansi->nama = $request->nama;
        $instansi->email = $request->email;
        $instansi->status_email = 0;
        $instansi->password = '';
        $instansi->token = '';
        $instansi->tgl_generate = null;
        $instansi->alamat = $request->alamat;
        $instansi->no_telp = $request->no_telp;
        $instansi->web = $request->web;
        $instansi->status_instansi = 0;
        $instansi->save();
        event( new Inst("store", ["Admin","Dosen","Mahasiswa"], false, $instansi));
        return response()->json([
            'message' => 'Instansi berhasil ditambahkan',
            'data' => $instansi
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function showMagang($id)
    {
        $instansi = Instansi::where('id', $id)->first();
        $magang = $instansi->magang()->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan magang instansi '.$instansi->nama,
            'data' => $magang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $instansi = Instansi::where('id_instansi', $id)->first();
        $instansi->nama = $request->nama ? $request->nama : $instansi->nama;
        $instansi->email = $request->email ? $request->email : $instansi->email;
        $instansi->status_email = 0;
        $instansi->password = '';
        $instansi->token = '';
        $instansi->tgl_generate = null;
        $instansi->alamat = $request->alamat ? $request->alamat : $instansi->alamat;
        $instansi->no_telp = $request->no_telp ? $request->no_telp : $instansi->no_telp;
        $instansi->web = $request->web ? $request->web : $instansi->web;
        $instansi->save();
        event( new Inst("update", ["Admin","Dosen","Mahasiswa"], false, $instansi));
        return response()->json([
            'message' => 'Instansi berhasil diubah',
            'data' => $instansi
        ]);
    }

    public function approve($id)
    {
        $instansi = Instansi::where('id_instansi', $id)->first();
        $instansi->status_instansi = 1;
        $instansi->save();
        $magang = Magang::where('id_instansi', $id)->get();
        foreach ($magang as $m) {
            $m->id_progres = 2;
            $m->save();
        }
        event( new Inst("update", ["Admin","Dosen","Mahasiswa"], false, $instansi));
        return response()->json([
            'message' => 'Instansi berhasil diapprove',
            'data' => $instansi
        ]);
    }

    public function reject($id)
    {
        $instansi = Instansi::where('id_instansi', $id)->first();
        $magang = Magang::where('id_instansi', $id)->where('id_progres', '>', 2)->get();
        if ($magang->count() > 0) {
            return response()->json([
                'message' => 'Terdaapat magang yang sudah mengajukan bimbingan ke dosen, tidak bisa mereject instansi',
                'data' => $instansi
            ]);
        }
        $instansi->status_instansi = 2;
        $instansi->save();
        event( new Inst("update", ["Admin","Dosen","Mahasiswa"], false, $instansi));
        return response()->json([
            'message' => 'Instansi berhasil direject',
            'data' => $instansi
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $instansi = Instansi::where('id', $id)->first();
        $legacy = $instansi;
        $instansi->delete();
        event( new Inst("destroy", ["Admin","Dosen","Mahasiswa"], true, $legacy));
        return response()->json([
            'message' => 'Instansi berhasil dihapus',
            'data' => $legacy
        ]);
    }
}
