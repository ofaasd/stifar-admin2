<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanSkripsiController extends Controller
{
    public function index(){
        return view('mahasiswa.skripsi.pengajuan.skripsi.index',);
    }
}
