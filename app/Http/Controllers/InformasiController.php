<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $informasi = Informasi::where('status_publikasi',1)->orderBy('tanggal', 'desc')->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $informasi
        ]);
    }

    public function indexPagination()
    {
        $informasi = Informasi::orderBy('tanggal', 'desc')->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $informasi
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->kueri;
        $informasi = Informasi::where('judul','like',"%$search%")
        ->orWhere('deskripsi','like',"%$search%")
        ->orderBy('tanggal', 'desc')
        ->paginate(10);
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $informasi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $informasi = new Informasi();
        $informasi->judul = $request->judul;
        $informasi->deskripsi = $request->deskripsi;
        //tanggal sekarang
        $informasi->tanggal = date('Y-m-d');
        $informasi->status_publikasi = $request->status_publikasi == true ? 1 : 0;
        if($request->hasFile('dokumen')){
            $file = $request->file('dokumen');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/dokumen_berita', $filename);
            $informasi->dokumen = $filename;
        }
        $informasi->save();

        return response()->json([
            'message' => 'Berhasil menambahkan data',
            'data' => $informasi
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function showDokumenInformasi($file)
    {
        $path = storage_path('app/public/dokumen_berita/'. $file);
        return response()->file($path);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $informasi = Informasi::find($id);
        //tanggal sekarang
        $informasi->tanggal = date('Y-m-d');
        $informasi->judul = $request->judul ?? $informasi->judul;
        $informasi->deskripsi = $request->deskripsi ?? $informasi->deskripsi;
        if ($request->status_publikasi != null) {
            $informasi->status_publikasi = $request->status_publikasi == true ? 1 : 0;
        }
        if($request->hasFile('dokumen')){
            //delete file lama
            $file = $informasi->dokumen;
            $path = storage_path('app/public/dokumen_berita/'. $file);
            if(file_exists($path)){
                unlink($path);
            }
            //upload file baru
            $file = $request->file('dokumen');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/dokumen_berita', $filename);
            $informasi->dokumen = $filename;
        }
        $informasi->save();

        return response()->json([
            'message' => 'Berhasil mengubah data',
            'data' => $informasi
        ]);
    }

    public function publish($id)
    {
        $informasi = Informasi::find($id);
        $informasi->status_publikasi = 1;
        $informasi->save();

        return response()->json([
            'message' => 'Berhasil mempublikasikan data',
            'data' => $informasi
        ]);
    }

    public function unpublish($id)
    {
        $informasi = Informasi::find($id);
        $informasi->status_publikasi = 0;
        $informasi->save();

        return response()->json([
            'message' => 'Berhasil menarik publikasi data',
            'data' => $informasi
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $informasi = Informasi::find($id);
        $legacy = $informasi;
        $file = $informasi->dokumen;
        $path = storage_path('app/public/dokumen_berita/'. $file);
        if(file_exists($path)){
            unlink($path);
        }
        $informasi->delete();

        return response()->json([
            'message' => 'Berhasil menghapus data',
            'data' => $legacy
        ]);
    }
}
