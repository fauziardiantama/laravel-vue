<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    //register
    public function registerMahasiswa(Request $request)
    {
        //validate request
        $request->validate([
            'nim' => 'required|string|unique:mahasiswa',
            'nama' => 'required|string',
            'email' => 'required|email|unique:mahasiswa',
            'no_telp' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        try {
            //create admin
            $mahasiswa = Mahasiswa::create([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'password' => bcrypt($request->password),
                'status' => false
            ]);

            //generate token
            //$token = $mahasiswa->createToken('adminToken')->plainTextToken;

            //response
            if ($mahasiswa) {
                return response()->json([
                    'message' => 'Mahasiswa berhasil didaftarkan',
                    'mahasiswa' => $mahasiswa
                ], 201);
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
    public function loginMahasiswa(Request $request)
    {
        //validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        try {
            auth()->guard('smahasiswa')->attempt(
                [
                    'email' => $request['email'],
                    'password' => $request['password'],
                    'status' => true
                ]
            );
            if (!auth()->guard('smahasiswa')->check()) {
                return response()->json([
                    'message' => 'Email atau password salah'
                ], 401);
            }

            //generate token
            $authuser = auth()->guard('smahasiswa')->user();
            $token = $authuser->auth()->first()->createToken('mahasiswaToken')->accessToken;

            //response
            return response()->json([
                'message' => 'Berhasil login',
                'token' => $token,
                'user' => $authuser
            ], 200);

        } catch (\Exception $e) {
            //response
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function loginDosen(Request $request)
    {
        //validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        try {
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
            $token = $authuser->createToken('dosenToken')->accessToken;

            //response
            return response()->json([
                'message' => 'Berhasil login',
                'token' => $token,
                'user' => $authuser
            ], 200);

        } catch (\Exception $e) {
            //response
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function loginAdmin(Request $request)
    {
        //validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        try {
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
            $token = $authuser->createToken('adminToken')->accessToken;

            //response
            return response()->json([
                'message' => 'Berhasil login',
                'token' => $token,
                'user' => $authuser
            ], 200);

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
}
