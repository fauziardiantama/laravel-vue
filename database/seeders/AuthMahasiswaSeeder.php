<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\AuthMahasiswa;

class AuthMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //get every nim from mahasiswa and make AuthMahasiswa with it
        $mahasiswas = Mahasiswa::all();
        foreach ($mahasiswas as $mahasiswa) {
            AuthMahasiswa::create([
                'nim' => $mahasiswa->nim
            ]);
        }
    }
}
