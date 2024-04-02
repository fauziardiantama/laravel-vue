<?php

namespace App\Http\Controllers;

use App\Models\ProposalTA;
use App\Models\Mahasiswa;
use App\Events\Prop;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProposalTA;
use App\Http\Requests\UpdateProposalTA;
use Illuminate\Support\Facades\Storage;

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
            $proposal->token = bin2hex(random_bytes(32));
            $proposal->token_expired = now()->addHours(2);
            $proposal->save();
        } else {
            $semesterMapping = [
                "ganjil" => 1,
                "genap" => 2
            ];
            $statusMapping = [
                "disetujui" => 1,
                "diterima" => 1,
                "ditolak" => -1,
                "menunggu" => 0
            ];
            $order = request()->order ?: 'id';
            $sort = request()->sort ?: 'asc';
            $limit = request()->limit ?: 10;

            $query = ProposalTA::query();

            if ($order == 'nama')
            {
                $query->addSelect([
                    $order => Mahasiswa::select('nama')
                    ->whereColumn('nim', 'proposal_ta.nim')
                    ->limit(1)
                ]);
                $query->orderBy($order, $sort);
            } else {
                $query->orderBy($order, $sort);
            }

            if (array_key_exists(strtolower(request()->kueri), $semesterMapping)) {
                $query->where('semester_id', $semesterMapping[strtolower(request()->kueri)]);
            } else if (array_key_exists(strtolower(request()->kueri), $statusMapping)) {
                $query->where('status_proposal', $statusMapping[strtolower(request()->kueri)]);
            } else {
                $query->where(function ($query) {
                    $query->whereHas('mahasiswa', function ($query) {
                        $query->where('nama', 'like', '%'.request()->kueri.'%');
                    })
                    ->orWhere('nim', 'like', '%'.request()->kueri.'%')
                    ->orWhere('judul_proposal', 'like', '%'.request()->kueri.'%')
                    ->orWhere('tahun', 'like', '%'.request()->kueri.'%')
                    ->orWhere('file_proposal', 'like', '%'.request()->kueri.'%');
                });
            }

           $proposal = $query->with(['mahasiswa', 'magang.dosen'])->paginate($limit);
 
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

    public function indexAll()
    {
        $proposal = ProposalTA::orderBy('tahun', 'asc')->with('mahasiswa')->get();
        if ($proposal->isEmpty() == true) {
            return response()->json([
                'message' => 'Proposal tidak ditemukan'
            ],404);
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
        $proposal->token = bin2hex(random_bytes(32));
        $proposal->token_expired = now()->addHours(2);
        if ($proposal->save()) {
            $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa','semester','jadwalPropTa')->first();
            event( new Prop("store", ["Admin","User.".$nim], false, $proposal));
            // send #EVENT ProposalUpdated to Mahasiswa.nim dan Admin

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
            $proposal = ProposalTA::where('nim', request()->user()->mahasiswa()->first()->nim)->with('mahasiswa','semester','jadwalPropTa')->first();
            if (!$proposal) {
                return response()->json([
                    'message' => 'Proposal tidak ditemukan'
                ],404);
            }
            $proposal->token = bin2hex(random_bytes(32));
            $proposal->token_expired = now()->addHours(2);
            $proposal->save();
            return response()->json([
                'message' => 'Berhasil menampilkan proposal',
                'data' => $proposal
            ]);
        } else {
            $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa')->first();
            $proposal->token = bin2hex(random_bytes(32));
            $proposal->token_expired = now()->addHours(2);
            $proposal->save();
            event( new Prop("update", ["Admin","User.".$nim], false, $proposal));
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
        $proposal->token = bin2hex(random_bytes(32));
        $proposal->token_expired = now()->addHours(2);
        $proposal->save();
        $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa')->first();
        event( new Prop("update", ["Admin","User.".$nim], false, $proposal));
        //send #EVENT ProposalUpdated to Mahasiswa.nim dan Admin
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
        $proposal->token = bin2hex(random_bytes(32));
        $proposal->token_expired = now()->addHours(2);
        $proposal->save();
        $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa')->first();
        event( new Prop("update", ["Admin","User.".$nim], false, $proposal));
        // send #EVENT ProposalUpdated to Mahasiswa.nim dan Admin
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
        $proposal->token = bin2hex(random_bytes(32));
        $proposal->token_expired = now()->addHours(2);
        $proposal->save();
        $proposal = ProposalTA::where('nim', $nim)->with('mahasiswa')->first();
        event( new Prop("update", ["Admin","User.".$nim], false, $proposal));
        // send #EVENT ProposalUpdated to Mahasiswa.nim dan Admin
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
        //delete file
        $file = $proposal->file_proposal;
        if ($file) {
            Storage::delete('public/proposal_ta/'.$file);
        }

        $legacy = $proposal;

        $proposal->delete();

        event( new Prop("destroy", ["Admin","User.".$nim], true, [
            'nim' => $nim,
            'id' => $legacy->id
        ]));

        return response()->json([
            'message' => 'Berhasil menghapus proposal',
            'data' => $legacy
        ]);
    }
}
