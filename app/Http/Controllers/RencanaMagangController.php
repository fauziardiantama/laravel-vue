<?php

namespace App\Http\Controllers;

use App\Models\RencanaMagang;
use App\Models\Magang;
use Illuminate\Http\Request;

class RencanaMagangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->isMahasiswa()) {
            $mahasiswa = $request->user()->mahasiswa();
            $magang = Magang::where('nim', $mahasiswa->first()->nim);
            if ($magang->exists()) {
                $rencanaMagang = RencanaMagang::where('id_magang', $magang->first()->id_magang)->get();
                if ($rencanaMagang->count() > 0) {
                    return response()->json([
                        'message' => 'Berhasil menampilkan rencana magang',
                        'data' => $rencanaMagang
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Rencana magang tidak ditemukan',
                        'data' => null
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Magang tidak ditemukan',
                    'data' => null
                ], 404);
            }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RencanaMagang $rencanaMagang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RencanaMagang $rencanaMagang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RencanaMagang $rencanaMagang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RencanaMagang $rencanaMagang)
    {
        //
    }
}
