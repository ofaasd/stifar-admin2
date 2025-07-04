<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index(){
        return view('mahasiswa.skripsi.pengajuan.index');
    }
    // public function index_judul(){
    //     return view('mahasiswa.skripsi.pengajuan.judul');
    // }
    // public function index_dosbim(){
    //     return view('mahasiswa.skripsi.pengajuan.dosbim');
    // }
    // public function index_sidang(){
    //     return view('mahasiswa.skripsi.pengajuan.sidang');
    // }
}
