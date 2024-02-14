<?php

namespace App\Http\Controllers;

use App\Models\ProposalTA;
use App\Models\Mahasiswa;
use App\Events\ProposalUpdated;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProposalTA;
use App\Http\Requests\UpdateProposalTA;

class ProposalTAController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->user()->isMahasiswa()) {
            $proposal = ProposalTA::where('nim', request()->user()->mahasiswa()->first()->nim)->with('mahasiswa')->first();
            if (!$proposal) {
                return response()->json([
                    'message' => 'Proposal tidak ditemukan'
                ],404);
            }
        } else {
            $proposal = ProposalTA::orderBy('tahun', 'asc')->with('mahasiswa')->paginate(10);
			if ($proposal->isEmpty() == true) {
                return response()->json([
                    'message' => 'Proposal tidak ditemukan'
                ],404);
            }
            
        }
        return response()->json([
            'message' => 'Berhasil menampilkan proposal',
            'data' => $proposal
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProposalTA $request)
    {
        if (request()->user()->isMahasiswa()) {
            $nim = request()->user()->mahasiswa()->first()->nim;
        } else {
            $nim = $request->nim;
        }
        if (ProposalTA::where('nim', $nim)->first()) {
            $proposal = ProposalTA::where('nim', $nim)->first();
            if ($proposal->status_proposal == 1) {
                return response()->json([
                    'message' => 'Tidak dapat menambahkan proposal, proposal sudah disetujui'
                ], 400);
            }
        } else {
            $proposal = new ProposalTA();
        }
        
        $proposal->judul_proposal = $request->judul_proposal;
        if ($request->hasFile('file_proposal')) {
            $file = $request->file('file_proposal');
            $fileName = $nim . '_proposal.pdf';
            $file->storeAs('public/proposal_ta', $fileName);
            $proposal->file_proposal = $fileName;
        }
        $proposal->status_proposal = 0;
        $proposal->nim = $nim;
        $proposal->semester_id = $request->semester_id;
        $proposal->tahun = $request->tahun;
        if ($proposal->save()) {
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa')->first();
            event(new ProposalUpdated($mahasiswa, false, $proposal));
            return response()->json([
                'message' => 'Berhasil menambahkan proposal',
                'data' => $proposal
            ]);
        }
        return response()->json([
            'message' => 'Gagal menambahkan proposal'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show($nim)
    {
        if (request()->user()->isMahasiswa()) {
            $proposal = ProposalTA::where('nim', request()->user()->mahasiswa()->first()->nim)->with('mahasiswa')->first();
            return response()->json([
                'message' => 'Berhasil menampilkan proposal',
                'data' => $proposal
            ]);
        } else {
            $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa')->first();
            return response()->json([
                'message' => 'Berhasil menampilkan proposal',
                'data' => $proposal
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProposalTA $request, $nim)
    {
        $proposal = ProposalTA::where('nim', $nim)->first();
        if (!$proposal) {
            return response()->json([
                'message' => 'Proposal tidak ditemukan'
            ],404);
        }
        if (request()->user()->isMahasiswa()) {
            if ($proposal->nim != request()->user()->mahasiswa()->first()->nim) {
                return response()->json([
                    'message' => 'Proposal tidak ditemukan'
                ],404);
            }
        }
        if ($proposal->status_proposal == 1) {
            return response()->json([
                'message' => 'Tidak dapat mengubah proposal, proposal sudah disetujui'
            ], 400);
        }
        $proposal->judul_proposal = $request->judul_proposal ?? $proposal->judul_proposal;
        if ($request->hasFile('file_proposal')) {
            $file = $request->file('file_proposal');
            $fileName = $nim . '_proposal.pdf';
            $file->storeAs('public/proposal_ta', $fileName);
            $proposal->file_proposal = $fileName;
        }
        $proposal->status_proposal = 0;
        if (request()->user()->isMahasiswa()) {
            $proposal->nim = request()->user()->mahasiswa()->first()->nim;
        } else {
            $proposal->nim = $request->nim ?? $proposal->nim;
        }
        $proposal->semester_id = $request->semester_id ?? $proposal->semester_id;
        $proposal->tahun = $request->tahun ?? $proposal->tahun;
        $proposal->save();
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa')->first();
        event(new ProposalUpdated($mahasiswa, false, $proposal));
        return response()->json([
            'message' => 'Berhasil mengubah proposal',
            'data' => $proposal
        ]);
    }

    public function approved($nim)
    {
        $proposal = ProposalTA::where('nim', $nim)->first();
        if (!$proposal) {
            return response()->json([
                'message' => 'Proposal tidak ditemukan'
            ],404);
        }
        $proposal->status_proposal = 1;
        $proposal->save();
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa')->first();
        event(new ProposalUpdated($mahasiswa->token(), false, $proposal));
        return response()->json([
            'message' => 'Berhasil menyetujui proposal',
            'data' => $proposal
        ]);
    }

    public function rejected($nim)
    {
        $proposal = ProposalTA::where('nim', $nim)->first();
        if (!$proposal) {
            return response()->json([
                'message' => 'Proposal tidak ditemukan'
            ],404);
        }
        $proposal->status_proposal = -1;
        $proposal->save();
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa')->first();
        event(new ProposalUpdated($mahasiswa->token(), false, $proposal));
        return response()->json([
            'message' => 'Berhasil menolak proposal',
            'data' => $proposal
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nim)
    {
        $proposal = ProposalTA::where('nim', $nim)->first();
        if (!$proposal) {
            return response()->json([
                'message' => 'Proposal tidak ditemukan'
            ],404);
        }
        if (request()->user()->isMahasiswa()) {
            if ($proposal->nim != request()->user()->mahasiswa()->first()->nim) {
                return response()->json([
                    'message' => 'Proposal tidak ditemukan'
                ],404);
            }
        }
        if ($proposal->status_proposal == 1) {
            return response()->json([
                'message' => 'Tidak dapat menghapus proposal, proposal sudah disetujui'
            ], 400);
        }
        $legacy = $proposal;
        $proposal->delete();
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        return response()->json([
            'message' => 'Berhasil menghapus proposal',
            'data' => $legacy
        ]);
    }
}
