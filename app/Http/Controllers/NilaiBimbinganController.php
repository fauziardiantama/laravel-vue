<?php

namespace App\Http\Controllers;

use App\Models\NilaiBimbingan;
use App\Models\Magang;
use Illuminate\Http\Request;

class NilaiBimbinganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->user()->isMahasiswa()) {
            $user = request()->user();
            $magang = $user->mahasiswa()->first()->magang()->first()->nilaiBimbingan()->with('parameterNilaiBimbingan')->get();
            return response()->json([
                'message' => 'success',
                'data' => $magang
            ]);
        } else {
            $order = request()->order ?: 'id_nilai_bimbingan';
            $sort = request()->sort ?: 'desc';
            $limit = request()->limit ?: 10;

            $query = NilaiBimbingan::query();
            $query->orderBy($order, $sort);

            $query->where(function ($query){
                $query->where('id_magang', 'like', '%'.request()->kueri.'%')
                ->orWhere('id_parameter', 'like', '%'.request()->kueri.'%');
            });

            $nilaiBimbingan = $query->paginate($limit);

            return response()->json([
                'message' => 'Berhasil menampilkan data',
                'data' => $nilaiBimbingan
            ]);
        }
    }

    public function magang($id_magang)
    {
        $order = request()->order ?: 'id_nilai_bimbingan';
        $sort = request()->sort ?: 'desc';
        $limit = request()->limit ?: 10;

        $query = NilaiBimbingan::query();
        $query->orderBy($order, $sort);

        $query->where('id_magang', $id_magang);

        $nilaiBimbingan = $query->with('parameterNilaiBimbingan')->paginate($limit);

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $nilaiBimbingan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_magang' => 'required',
            'id_parameter' => 'required',
            'nilai' => 'required'
        ]);

        $nilaiBimbingan = NilaiBimbingan::create([
            'id_magang' => $request->id_magang,
            'id_parameter' => $request->id_parameter,
            'nilai' => $request->nilai
        ]);

        if($nilaiBimbingan) {
            return response()->json([
                'message' => 'Nilai bimbingan berhasil ditambahkan',
                'data' => $nilaiBimbingan
            ]);
        } else {
            return response()->json([
                'message' => 'Nilai bimbingan gagal ditambahkan',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(NilaiBimbingan $nilaiBimbingan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NilaiBimbingan $nilaiBimbingan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $nilai = NilaiBimbingan::find($id);

        if (!$nilai) {
            return response()->json(['message' => 'NilaiBimbingan not found'], 404);
        }
    
        $nilai->fill($request->all());
        $nilai->save();
    
        return response()->json([
            'message' => 'edit berhasil',
            'data' => $nilai
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $nilai = NilaiBimbingan::find($id);

        if ($nilai->delete()) {
            return response()->json([
                'message' => 'hapus berhasil'
            ]);
        }
    }
}
