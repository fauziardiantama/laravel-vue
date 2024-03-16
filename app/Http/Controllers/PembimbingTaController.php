<?php

namespace App\Http\Controllers;

use App\Models\PembimbingTa;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class PembimbingTaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->isMahasiswa()) {
            $user = $request->user()->mahasiswa()->first();

            $proposal = $user->proposalTa()->first();
            if (!$proposal) {
                return response()->json([
                    'message' => 'Anda belum memiliki proposal TA',
                    'data' => null
                ], 404);
            }
            $pembimbingTa = $proposal->pembimbingTa()->with('dosen')->first();
            if (!$pembimbingTa) {
                return response()->json([
                    'message' => 'Anda belum memiliki pembimbing TA',
                    'data' => null
                ], 404);
            }
            return response()->json([
                'message' => 'Berhasil menampilkan pembimbing TA',
                'data' => $pembimbingTa
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeLink(Request $request)
    {
        if ($request->user()->isMahasiswa()) {
            $user = $request->user()->mahasiswa()->first();
            //check if user have magang with id_dosen is not null
            $daftar_magang_dengan_dosen = $user->magang()->whereNotNull('id_dosen')->where('status_dosen', '=', 1)->get();
            if ($daftar_magang_dengan_dosen->count() < 1) {
                return response()->json([
                    'message' => 'Anda belum memiliki magang dengan dosen pembimbing',
                    'data' => null
                ], 404);
            }
            //check if $request->id_magang is in $daftar_magang_dengan_dosen
            $magang = $daftar_magang_dengan_dosen->where('id_magang', $request->id_magang)->first();
            //add id_magang to pembimbing_ta if it's exist/true
            if ($magang) {
                $proposal = $user->proposalTa()->first();
                if (!$proposal) {
                    return response()->json([
                        'message' => 'Anda belum memiliki proposal TA',
                        'data' => null
                    ], 404);
                }

                if ($proposal->pembimbingTa()->first()) {
                    return response()->json([
                        'message' => 'Pembimbing TA sudah ada',
                        'data' => $proposal->with('pembimbingTa')->first()
                    ], 404);
                }
                $proposal->pembimbingTa()->attach($magang->id_magang);
                return response()->json([
                    'message' => 'Berhasil menambahkan pembimbing TA',
                    'data' => $proposal->with('pembimbingTa')->first()
                ]);
            } else {
                return response()->json([
                    'message' => 'Anda tidak memiliki magang dengan id tersebut',
                    'data' => null
                ], 404);
            }
        }
    }

    public function destroyLink(Request $request)
    {
        if ($request->user()->isMahasiswa()) {
            $user = $request->user()->mahasiswa()->first();
            $proposal = $user->proposalTa()->first();
            if (!$proposal) {
                return response()->json([
                    'message' => 'Anda belum memiliki proposal TA',
                    'data' => null
                ], 404);
            }
            $proposal->pembimbingTa()->detach($proposal->pembimbingTa()->first()->id_magang);
            return response()->json([
                'message' => 'Berhasil menghapus pembimbing TA',
                'data' => $proposal->with('pembimbingTa')->first()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        $pembimbingTas = $mahasiswa->proposalTa()->first()->pembimbingTa()->get();

        return response()->json([
            'message' => 'Berhasil menampilkan pembimbing TA',
            'data' => $pembimbingTas
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PembimbingTa $pembimbingTa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PembimbingTa $pembimbingTa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PembimbingTa $pembimbingTa)
    {
        //
    }
}
