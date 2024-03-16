<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterMahasiswaRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\AuthUser;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\baseMail;
use App\Events\Mhs;


class AuthController extends Controller
{
    //register
    public function registerMahasiswa(RegisterMahasiswaRequest $request)
    {
        try {
            //create admin
            $mahasiswa = new Mahasiswa;
            $mahasiswa->nim = $request->nim;
            $mahasiswa->nama = $request->nama;
            $mahasiswa->email = $request->email;
            $mahasiswa->no_telp = $request->no_telp;
            $mahasiswa->password = bcrypt($request->password);
            $mahasiswa->status = false;
            $mahasiswa->konfirmasi = false;
            $mahasiswa->konfirmasi_token = sha1(time().$mahasiswa->email);
            $mahasiswa->save();

            //response
            if ($mahasiswa) {
                $authmahasiswa = $mahasiswa->auth()->create([
                    'role' => 'mahasiswa',
                ]);
                if ($authmahasiswa) {
                    Mail::to($mahasiswa->email)->send(new baseMail(
                        [
                            "view" => "emails.akun",
                            "from" => [
                                "address" =>  env('MAIL_FROM_ADDRESS'),
                                "name" => "Notifikasi D3TI"
                            ],
                            "tags" => [ "verifikasi", "akun", "d3ti","kuliah","notifikasi" ],
                            "subject" => "Verifikasi Akun",
                            "content" => [
                                "nama_user" => $mahasiswa->nama,
                                "judul" => "Verifikasi Akun",
                                "pesan" => "Buka link berikut untuk verifikasi akun anda, abaikan pesan ini jika anda tidak merasa melakukan pendaftaran akun.",
                                "tautan" => config('app.url').'/verifikasi-email?email='.$mahasiswa->email.'&token='.$mahasiswa->konfirmasi_token,
                                "useakun" => true,
                                "akun" => [
                                    "email" => $mahasiswa->email,
                                    "password" => $request->password
                                ]
                            ],
                            "attachments" => []
                        ]
                    ));
                    event( new Mhs("store", ["Admin"], false, $mahasiswa));
                    return response()->json([
                        'message' => 'Mahasiswa berhasil didaftarkan',
                        'mahasiswa' => $mahasiswa
                    ], 201);
                } else {
                    $mahasiswa->delete();
                    return response()->json([
                        'message' => 'Autentikasi Mahasiswa gagal didaftarkan'
                    ], 409);
                }
            } else {
                return response()->json([
                    'message' => 'Mahasiswa gagal didaftarkan'
                ], 409);
            }
        } catch (\Exception $e) {
            //response
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    //login
    public function loginMahasiswa(LoginRequest $request)
    {
        try {
            $mahasiswa = Mahasiswa::where('email', $request->email)->first();
            if ($mahasiswa) {
                if ($mahasiswa->password == md5($request->password)) {
                    $mahasiswa->password = bcrypt($request->password);
                    $mahasiswa->save();
                }
                if (!$mahasiswa->konfirmasi) {
                    return response()->json([
                        'message' => 'Akun belum aktif'
                    ], 401);
                }
            }
            auth()->guard('smahasiswa')->attempt(
                [
                    'email' => $request['email'],
                    'password' => $request['password'],
                    'konfirmasi' => true
                ]
            );
            if (!auth()->guard('smahasiswa')->check()) {
                return response()->json([
                    'message' => 'Email atau password salah'
                ], 401);
            }

            //generate token
            $authuser = auth()->guard('smahasiswa')->user();
            $token = $authuser->auth()->first()->createToken('mahasiswa')->accessToken;

            //response
            return response()->json([
                'message' => 'Berhasil login',
                'token' => $token,
                'user' => $authuser
            ], 200);

        } catch (\RuntimeException $e) {
            //response
            return response()->json([
                'message' => 'Email atau password salah',
                'error' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            //response
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function loginDosen(LoginRequest $request)
    {
        try {
            $dosen = Dosen::where('email', $request->email)->first();
            if ($dosen) {
                if ($dosen->password == md5($request->password)) {
                    $dosen->password = bcrypt($request->password);
                    $dosen->save();
                }
            }
            auth()->guard('sdosen')->attempt(
                [
                    'email' => $request['email'],
                    'password' => $request['password']
                ]
            );
            if (!auth()->guard('sdosen')->check()) {
                return response()->json([
                    'message' => 'Email atau password salah'
                ], 401);
            }

            //generate token
            $authuser = auth()->guard('sdosen')->user();
            $token = $authuser->auth()->first()->createToken('dosen')->accessToken;

            //response
            return response()->json([
                'message' => 'Berhasil login',
                'token' => $token,
                'user' => $authuser
            ], 200);

        } catch (\RuntimeException $e) {
            //response
            return response()->json([
                'message' => 'Email atau password salah',
                'error' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            //response
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function loginAdmin(LoginRequest $request)
    {
        try {
            $admin = Admin::where('email', $request->email)->first();
            if ($admin) {
                if ($admin->password == md5($request->password)) {
                    $admin->password = bcrypt($request->password);
                    $admin->save();
                }
            }
            auth()->guard('sadmin')->attempt(
                [
                    'email' => $request['email'],
                    'password' => $request['password']
                ]
            );
            if (!auth()->guard('sadmin')->check()) {
                return response()->json([
                    'message' => 'Email atau password salah'
                ], 401);
            }

            //generate token
            $authuser = auth()->guard('sadmin')->user();
            $token = $authuser->auth()->first()->createToken('admin')->accessToken;

            //response
            return response()->json([
                'message' => 'Berhasil login',
                'token' => $token,
                'user' => $authuser
            ], 200);

        } catch (\RuntimeException $e) {
            //response
            return response()->json([
                'message' => 'Email atau password salah',
                'error' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            //response
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    //logout
    public function logout(Request $request)
    {
        try {
            //revoke token
            $request->user()->token()->revoke();
            auth()->guard('sadmin')->logout();
            auth()->guard('sdosen')->logout();
            auth()->guard('smahasiswa')->logout();
    
            //response
            return response()->json([
                'message' => 'berhasil logout'
            ], 200);
        } catch (\Exception $e) {
            //response
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function verify() {
        try {
            Mail::to("fauziardiantama@student.uns.ac.id")->send(new baseMail(
                [
                    "view" => "emails.akun",
                    "from" => [
                        "address" => env('MAIL_FROM_ADDRESS'),
                        "name" => "Notifikasi D3TI"
                    ],
                    "tags" => [ "verifikasi", "akun", "d3ti","kuliah","notifikasi" ],
                    "subject" => "Verifikasi Akun",
                    "content" => [
                        "nama_user" => "Fauzi Ardiantama",
                        "judul" => "Verifikasi Akun",
                        "pesan" => "Buka link berikut untuk verifikasi akun anda, abaikan pesan ini jika anda tidak merasa melakukan pendaftaran akun.",
                        "tautan" => config('app.url').'/verifikasi-email?email=fauziardiantama@student.uns.ac.id&token=123',
                        "useakun" => true,
                        "akun" => [
                            "email" => "testinf",
                            "password" => "testinf"
                        ]
                    ],
                    "attachments" => []
                ]
            ));
            return response()->json([
                'message' => 'berhasil'
            ], 200);
        } catch (\Exception $e) {
            //response
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function verifyEmail(Request $request)
    {
        try {
            $mahasiswa = Mahasiswa::where('email', $request->email)->first();
            if ($mahasiswa) {
                if ($mahasiswa->konfirmasi_token == $request->token) {
                    $mahasiswa->konfirmasi_token = null;
                    $mahasiswa->konfirmasi = true;
                    $mahasiswa->save();
                    event( new Mhs("store", ["Admin"], false, $mahasiswa));
                    return redirect(config('app.url').'/mahasiswa/login?status=verified');
                } else {
                    return redirect(config('app.url').'/mahasiswa/login?status=invalid');
                }
            } else {
                return redirect(config('app.url').'/mahasiswa/login?status=invalid');
            }
        } catch (\Exception $e) {
            //response
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 409);
        }
    }
}
