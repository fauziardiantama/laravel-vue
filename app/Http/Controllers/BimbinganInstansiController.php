<?php

namespace App\Http\Controllers;

use App\Models\bimbinganInstansi;
use App\Models\Magang;
use Illuminate\Http\Request;

class BimbinganInstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswa = request()->user()->mahasiswa()->first();

        $magang = $mahasiswa->magang()->first();

        $status = [
            "disetujui" => 1,
            "ditolak" => -1,
            "menunggu" => 0,
            "setuju" => 1,
            "tolak" => -1,
        ];

        $order = request()->order ?: 'id_bimbingan_instansi';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = bimbinganInstansi::query();
        $query->where('id_magang', $magang->id_magang);
        $query->orderBy($order, $sort);

        if (array_key_exists(strtolower(request()->kueri), $status)) {
            $query->where('status', $status[request()->kueri]);
        } else {
            $query->where(function ($query) {
                $query->where('tanggal', 'like', '%' . request()->kueri . '%')
                    ->orWhere('data_bimbingan', 'like', '%' . request()->kueri . '%');
            });
        }

        $bimbinganInstansi = $query->paginate($limit);

        return response()->json([
            'message' => 'bimbingan instansi berhasil ditampilkan',
            'data' => $bimbinganInstansi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mahasiswa = request()->user()->mahasiswa()->first();

        $magang = $mahasiswa->magang()->first();

        $bimbinganInstansi = $magang->bimbinganInstansi()->create([
            'tanggal' => $request->tanggal,
            'data_bimbingan' => $request->data_bimbingan,
            'status' => 0
        ]);

        return response()->json([
            'message' => 'bimbingan instansi berhasil ditambahkan',
            'data' => $bimbinganInstansi
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $magang = Magang::where('id_magang', $id)->first();
        $status = [
            "disetujui" => 1,
            "ditolak" => -1,
            "menunggu" => 0,
            "setuju" => 1,
            "tolak" => -1,
        ];

        $order = request()->order ?: 'id_bimbingan_instansi';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = bimbinganInstansi::query();
        $query->where('id_magang', $magang->id_magang);
        $query->orderBy($order, $sort);

        if (array_key_exists(strtolower(request()->kueri), $status)) {
            $query->where('status', $status[request()->kueri]);
        } else {
            $query->where(function ($query) {
                $query->where('tanggal', 'like', '%' . request()->kueri . '%')
                    ->orWhere('data_bimbingan', 'like', '%' . request()->kueri . '%');
            });
        }

        $bimbinganInstansi = $query->paginate($limit);

        return response()->json([
            'message' => 'bimbingan instansi berhasil ditampilkan',
            'data' => $bimbinganInstansi
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bimbinganInstansi = bimbinganInstansi::where('id_bimbingan_instansi', $id)->first();
        //if status is 1 then reject
        if ($bimbinganInstansi->status == 1) {
            return response()->json([
                'message' => 'bimbingan instansi tidak dapat diubah karena sudah disetujui'
            ]);
        }
        $bimbinganInstansi->update([
            'tanggal' => $request->tanggal,
            'data_bimbingan' => $request->data_bimbingan,
            'status' => 0
        ]);

        return response()->json([
            'message' => 'bimbingan instansi berhasil diubah',
            'data' => $bimbinganInstansi
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bimbinganInstansi = bimbinganInstansi::where('id_bimbingan_instansi', $id)->first();

        //if status is 1 then reject
        if ($bimbinganInstansi->status == 1) {
            return response()->json([
                'message' => 'bimbingan instansi tidak dapat dihapus karena sudah disetujui'
            ]);
        }
        $bimbinganInstansi->delete();

        return response()->json([
            'message' => 'bimbingan instansi berhasil dihapus'
        ]);
    }

    public function approve($id, $id_bimbingan)
    {
        $magang = Magang::where('id_magang', $id)->first();
        $bimbinganInstansi = $magang->bimbinganInstansi()->where('id_bimbingan_instansi', $id_bimbingan)->first();

        //if status is 1 then reject
        if ($bimbinganInstansi->status == 1) {
            return response()->json([
                'message' => 'bimbingan dosen tidak dapat disetujui karena sudah disetujui'
            ]);
        }
        $bimbinganInstansi->update([
            'status' => 1
        ]);

        return response()->json([
            'message' => 'bimbingan dosen berhasil disetujui',
            'data' => $bimbinganInstansi
        ]);
    }

    public function reject($id, $id_bimbingan)
    {
        $magang = Magang::where('id_magang', $id)->first();
        $bimbinganInstansi = $magang->bimbinganInstansi()->where('id_bimbingan_instansi', $id_bimbingan)->first();

        //if status -1 then reject
        if ($bimbinganInstansi->status == -1) {
            return response()->json([
                'message' => 'bimbingan dosen tidak dapat ditolak karena sudah ditolak'
            ]);
        }
        $bimbinganInstansi->update([
            'status' => -1
        ]);

        return response()->json([
            'message' => 'bimbingan dosen berhasil ditolak',
            'data' => $bimbinganInstansi
        ]);
    }
}
