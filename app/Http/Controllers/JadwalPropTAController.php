<?php

namespace App\Http\Controllers;

use App\Models\JadwalPropTA;
use App\Models\ProposalTA;
use App\Models\Dosen;
use Illuminate\Http\Request;

class JadwalPropTAController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->isMahasiswa()) {
            $jadwalPropTA = $request->user()->mahasiswa()->first()
            ->proposalTa()->first()
            ->jadwalPropTA()->with('semester', 'sesiUjian', 'ruangan', 'proposalTa', 'dosen')->paginate(10);
        } else if ($request->user()->isDosen()) {
            $jadwalPropTA = $request->user()->dosen()->first()
            ->jadwalPropTA()->with('semester', 'sesiUjian', 'ruangan', 'proposalTa', 'dosen')->paginate(10);
        } else {
            $jadwalPropTA = JadwalPropTA::with('semester', 'sesiUjian', 'ruangan', 'proposalTa', 'dosen')->paginate(10);
        }
        return response()->json([
            'status' => 'success',
            'data' => $jadwalPropTA
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jadwalPropTA = new JadwalPropTA;
        $jadwalPropTA->tanggal = $request->tanggal;
        $jadwalPropTA->tahun = $request->tahun;
        $jadwalPropTA->semester_id = $request->semester_id;
        $jadwalPropTA->sesi_id = $request->sesi_id;
        $jadwalPropTA->ruangan_id = $request->ruangan_id;
        if ($jadwalPropTA->save()) {
            return response()->json([
                'message' => 'Jadwal berhasil ditambahkan',
                'data' => $jadwalPropTA
            ]);
        }
        return response()->json([
            'message' => 'Internal server error',
        ], 500);
    }

    public function addDosen(Request $request, $id)
    {
        $jadwalPropTA = JadwalPropTA::find($id);
        if (!$jadwalPropTA) {
            return response()->json([
                'message' => 'Jadwal tidak ditemukan'
            ]);
        }
        $dosen = Dosen::where('id_dosen', $request->id_dosen)->first();
        if (!$dosen) {
            return response()->json([
                'message' => 'Dosen tidak ditemukan'
            ]);
        }
        if ($jadwalPropTA->dosen()->where('id_dosen', $dosen->id_dosen)->exists()) {
            return response()->json([
                'message' => 'Dosen sudah ada di jadwal ini'
            ]);
        }
        $jadwalPropTA->dosen()->attach($dosen->id_dosen);
        return response()->json([
            'message' => 'Dosen berhasil ditambahkan',
            'data' => $jadwalPropTA
        ]);
    }

    public function addMahasiswa(Request $request, $id)
    {
        $jadwalPropTA = JadwalPropTA::find($id);
        if (!$jadwalPropTA) {
            return response()->json([
                'message' => 'Jadwal tidak ditemukan'
            ]);
        }
        $proposalTa = ProposalTA::where('id', $request->proposal_ta_id)->first();
        if (!$proposalTa) {
            return response()->json([
                'message' => 'Proposal TA tidak ditemukan'
            ]);
        }
        if ($jadwalPropTA->proposalTa()->where('proposal_ta.id', $proposalTa->id)->exists()) {
            return response()->json([
                'message' => 'Proposal TA sudah ada di jadwal ini'
            ]);
        }
        $jadwalPropTA->proposalTa()->attach($proposalTa->id);
        return response()->json([
            'message' => 'Proposal TA berhasil ditambahkan',
            'data' => $jadwalPropTA
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalPropTA $jadwalPropTA)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jadwalPropTA = JadwalPropTA::find($id);
        $jadwalPropTA->tanggal = $request->tanggal;
        $jadwalPropTA->tahun = $request->tahun;
        $jadwalPropTA->semester_id = $request->semester_id;
        $jadwalPropTA->sesi_id = $request->sesi_id;
        $jadwalPropTA->ruangan_id = $request->ruangan_id;
        if ($jadwalPropTA->save()) {
            return response()->json([
                'message' => 'Jadwal berhasil diubah',
                'data' => $jadwalPropTA
            ]);
        }
        return response()->json([
            'message' => 'Internal server error',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalPropTA $jadwalPropTA)
    {
        //
    }
}
