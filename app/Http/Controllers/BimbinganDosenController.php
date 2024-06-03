<?php

namespace App\Http\Controllers;

use App\Models\BimbinganDosen;
use App\Models\Magang;
use App\Models\Mahasiswa;
use App\Models\ProposalTA;
use App\Models\Progres;
use Illuminate\Http\Request;

class BimbinganDosenController extends Controller
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

        $order = request()->order ?: 'id_bimbingan_dosen';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = BimbinganDosen::query();
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

        $bimbinganDosen = $query->paginate($limit);

        return response()->json([
            'message' => 'bimbingan dosen berhasil ditampilkan',
            'data' => $bimbinganDosen
        ]);
    }

    public function indexTa(Request $request)
    {

        $order = $request->order ?: 'id_magang';
        $sort = $request->sort ?: 'asc';
        $limit = $request->limit ?: 10;

        $query = Magang::query();

        if ($order=="nama") {
            $query->addSelect([
                $order => Mahasiswa::select('nama')
                ->whereColumn('mahasiswa.nim', 'magang.nim')
                ->limit(1)
            ]);
            $query->orderBy($order, $sort);
        } else if ($order=="judul_proposal") {
            $query->addSelect([
                $order => ProposalTa::select('judul_proposal')
                ->whereColumn('proposal_ta.id', 'pembimbing_ta.proposal_ta_id')
                ->whereColumn('pembimbing_ta.id_magang', 'magang.id_magang')
                ->limit(1)
            ]);
            $query->orderBy($order, $sort);
        } else {
            $query->orderBy($order, $sort);
        }
        $query->where(function ($query) {
            $query->whereHas('mahasiswa', function ($query) {
                $query->where('mahasiswa.nim', 'like', '%'.request()->kueri.'%')
                ->orWhere('mahasiswa.nama', 'like', '%'.request()->kueri.'%')
                ->orWhere('mahasiswa.email', 'like', '%'.request()->kueri.'%')
                ->orWhere('mahasiswa.no_telp', 'like', '%'.request()->kueri.'%');
            })
            ->orWhereHas('proposalTa', function ($query) {
                $query->where('proposal_ta.judul_proposal', 'like', '%'.request()->kueri.'%')
                ->orWhere('proposal_ta.file_proposal', 'like', '%'.request()->kueri.'%')
                ->orWhere('proposal_ta.tahun', 'like', '%'.request()->kueri.'%');
            });
        });

        $query->where('status_dosen', 1)->whereHas('proposalTa');
        //if dosen
        if ($request->user()->isDosen()) {
            $user = $request->user()->dosen()->first();
            $query->where('id_dosen', $user->id_dosen);
        }
        $magang = $query->with([
            'mahasiswa',
            'tahun',
            'topik',
            'instansi',
            'dosen',
            'progres',
            'rencanaMagang',
            'proposalTa'
        ])->paginate($limit);

        return response()->json([
            'message' => 'Berhasil menampilkan data magang',
            'data' => $magang
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mahasiswa = request()->user()->mahasiswa()->first();

        $magang = $mahasiswa->magang()->first();

        $bimbinganDosen = $magang->bimbinganDosen()->create([
            'tanggal' => $request->tanggal,
            'data_bimbingan' => $request->data_bimbingan,
            'status' => 0
        ]);

        return response()->json([
            'message' => 'bimbingan dosen berhasil ditambahkan',
            'data' => $bimbinganDosen
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

        $order = request()->order ?: 'id_bimbingan_dosen';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = BimbinganDosen::query();
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

        $bimbinganDosen = $query->paginate($limit);

        return response()->json([
            'message' => 'bimbingan dosen berhasil ditampilkan',
            'data' => $bimbinganDosen
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bimbinganDosen = BimbinganDosen::where('id_bimbingan_dosen', $id)->first();
        //if status is 1 then reject
        if ($bimbinganDosen->status == 1) {
            return response()->json([
                'message' => 'bimbingan dosen tidak dapat diubah karena sudah disetujui'
            ]);
        }
        $bimbinganDosen->update([
            'tanggal' => $request->tanggal,
            'data_bimbingan' => $request->data_bimbingan,
            'status' => 0
        ]);

        return response()->json([
            'message' => 'bimbingan dosen berhasil diubah',
            'data' => $bimbinganDosen
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bimbinganDosen = BimbinganDosen::where('id_bimbingan_dosen', $id)->first();

        //if status is 1 then reject
        if ($bimbinganDosen->status == 1) {
            return response()->json([
                'message' => 'bimbingan dosen tidak dapat dihapus karena sudah disetujui'
            ]);
        }
        $bimbinganDosen->delete();

        return response()->json([
            'message' => 'bimbingan dosen berhasil dihapus'
        ]);
    }

    public function approve($id, $id_bimbingan)
    {
        $magang = Magang::where('id_magang', $id)->first();
        $bimbinganDosen = $magang->bimbinganDosen()->where('id_bimbingan_dosen', $id_bimbingan)->first();

        //if status is 1 then reject
        if ($bimbinganDosen->status == 1) {
            return response()->json([
                'message' => 'bimbingan dosen tidak dapat disetujui karena sudah disetujui'
            ]);
        }
        $bimbinganDosen->update([
            'status' => 1
        ]);

        return response()->json([
            'message' => 'bimbingan dosen berhasil disetujui',
            'data' => $bimbinganDosen
        ]);
    }

    public function reject($id, $id_bimbingan)
    {
        $magang = Magang::where('id_magang', $id)->first();
        $bimbinganDosen = $magang->bimbinganDosen()->where('id_bimbingan_dosen', $id_bimbingan)->first();

        //if status -1 then reject
        if ($bimbinganDosen->status == -1) {
            return response()->json([
                'message' => 'bimbingan dosen tidak dapat ditolak karena sudah ditolak'
            ]);
        }
        $bimbinganDosen->update([
            'status' => -1
        ]);

        return response()->json([
            'message' => 'bimbingan dosen berhasil ditolak',
            'data' => $bimbinganDosen
        ]);
    }
}
