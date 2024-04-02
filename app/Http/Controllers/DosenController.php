<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\TopikKmm;
use Illuminate\Http\Request;
use App\Http\Requests\AddDosenRequest;
use App\Http\Requests\UpdateDosenRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\baseMail;
use App\Events\Dsn;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexAll()
    {
        $dosen = Dosen::where('status',1)->orderBy('nama', 'desc')->with('topik')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan dosen',
            'data' => $dosen
        ]);
    }

    public function indexAllTopik($id_topik)
    {
        $topik = TopikKmm::where('id_topik', $id_topik)->first();
        $dosen = $topik->dosen()->where('status',1)->orderBy('nama', 'desc')->with('topik')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan dosen',
            'data' => $dosen
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = request()->order ?: 'id_dosen';
        $sort = request()->sort ?: 'asc';
        $limit = request()->limit ?: 10;
        $query = Dosen::query();
        $query->orderBy($order, $sort);
        $query->where(function ($query) {
            $query->whereHas('topik', function ($query) {
                $query->where('nama_topik', 'like', '%'.request()->kueri.'%');
            })
            ->orWhere('nama', 'like', '%'.request()->kueri.'%')
            ->orWhere('email', 'like', '%'.request()->kueri.'%')
            ->orWhere('nik', 'like', '%'.request()->kueri.'%');
        });
        $dosen = $query->with('topik','magang')->paginate($limit);
        return response()->json([
            'message' => 'Berhasil menampilkan dosen',
            'data' => $dosen
        ]);
    }

    public function indexMagang()
    {
        $dosen = Dosen::orderBy('nama', 'desc')->with('topik','magang')->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan dosen',
            'data' => $dosen
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddDosenRequest $request)
    {
        $dosen = new Dosen;
        $dosen->nik = $request->nik;
        $dosen->nama = $request->nama;
        $dosen->email = $request->email;
        $dosen->password = bcrypt($request->password);
        $dosen->save();
        $dosen->auth()->create([
            'role' => 'dosen',
        ]);
        Mail::to($dosen->email)->send(new baseMail(
            [
                "view" => "emails.akun",
                "from" => [
                    "address" => env('MAIL_FROM_ADDRESS'),
                    "name" => "Notifikasi D3TI"
                ],
                "tags" => [ "dosen", "akun", "d3ti","kuliah","notifikasi" ],
                "subject" => "Pembuatan akun",
                "content" => [
                    "nama_user" => $dosen->nama,
                    "judul" => "Akun anda telah dibuat",
                    "pesan" => "Anda dapat mengakses akun di https://portald3ti.vokasi.uns.ac.id/ dengan memasukkan kredensial dibawah ini",
                    "tautan" => null,
                    "useakun" => true,
                    "akun" => [
                        "email" => $dosen->email,
                        "password" => $request->password
                    ]
                ],
                "attachments" => []
            ]
        ));
        event( new Dsn("store", ["Admin","Dosen","Mahasiswa"], false, $dosen));
        return response()->json([
            'message' => 'Dosen berhasil ditambahkan',
            'data' => $dosen
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($nik)
    {
        $dosen = Dosen::where('nik', $nik)->with('topik')->first();
        return response()->json([
            'message' => 'Berhasil menampilkan dosen',
            'data' => $dosen
        ]);
    }

    public function showWithMagang($nik)
    {
        $dosen = Dosen::where('nik', $nik)->with('topik','magang')->first();
        return response()->json([
            'message' => 'Berhasil menampilkan dosen',
            'data' => $dosen
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDosenRequest $request, $nik)
    {
        $dosen = Dosen::where('nik', $nik)->first();
        $dosen->nik = $request->nik ?? $dosen->nik;
        $dosen->nama = $request->nama ?? $dosen->nama;
        $dosen->email = $request->email ?? $dosen->email;
        $dosen->password = $request->password ? bcrypt($request->password) : $dosen->password;
        $dosen->save();
        event( new Dsn("update", ["Admin","Dosen","Mahasiswa"], false, $dosen));
        return response()->json([
            'message' => 'Dosen berhasil diubah',
            'data' => $dosen
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $dosen = Dosen::where('nik', $nik)->first();
        $legacy = $dosen->toArray();
        $dosen->auth()->delete();
        $dosen->delete();
        event( new Dsn("destroy", ["Admin","Dosen","Mahasiswa"], true, $legacy));
        return response()->json([
            'message' => 'Dosen berhasil dihapus',
            'data' => $legacy
        ]);
    }
}
