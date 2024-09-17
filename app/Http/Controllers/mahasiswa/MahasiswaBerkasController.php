<?php

namespace App\Http\Controllers\mahasiswa;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MahasiswaBerkasController extends Controller
{
    public function index(){
        $mhs = Mahasiswa::where('user_id', Auth::id())->first();
        $nim = $mhs->nim;
        $title = "Mahasiswa";
        $mahasiswa = Mahasiswa::select('mahasiswa.*', 'pegawai_biodata.nama_lengkap as dosenWali', 'mahasiswa_berkas_pendukung.*')
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
        ->leftJoin('mahasiswa_berkas_pendukung', 'mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
        ->where('mahasiswa.nim', $nim)
        ->first();

        return view('mahasiswa.berkas.index', compact('title', 'mahasiswa'));
    }
}
