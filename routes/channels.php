<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('testing-channel', function ($user) {
    return true;
});

Broadcast::channel('Admin', function ($user) {
    return $user->role == 'admin';
});

Broadcast::channel('Mahasiswa', function ($user) {
    return $user->role == 'mahasiswa';
});

Broadcast::channel('Dosen', function ($user) {
    return $user->role == 'dosen';
});

Broadcast::channel('User.{nim}', function ($user, $nim) {
    return $user->nim === $nim;
});

Broadcast::channel('Duser.{nik}', function ($user, $nik) {
    return $user->nik === $nik;
});

//MahasiswaAdded -> Admin
//ProposalUpdated -> Mahasiswa.nim and Admin
//TahunUpdated -> Mahasiswa and Admin
//MahasiswaUpdated -> Mahasiswa.nim and Admin
//DokRegAdded -> Mahasiswa.nim and Admin
//DokRegDeleted -> Mahasiswa.nim and Admin
