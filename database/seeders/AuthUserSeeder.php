<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Admin;
use App\Models\AuthUser;

class AuthUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //get every nim from mahasiswa and make AuthUser with it
        $mahasiswas = Mahasiswa::all();
        foreach ($mahasiswas as $mahasiswa) {
            $mahasiswa->auth()->create([
                'role' => 'mahasiswa',
            ]);
        }

        //get every nim from dosen and make AuthUser with it
        $dosens = Dosen::all();
        foreach ($dosens as $dosen) {
            $dosen->auth()->create([
                'role' => 'dosen',
            ]);
        }
        foreach (Admin::all() as $admin) {
            $admin->auth()->create([
                'role' => 'admin',
            ]);
        }
    }
}
