<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Http\Controllers\Controller;
use App\Models\GelombangSidangSkripsi;
use Illuminate\Http\Request;

class PengajuanSidangController extends Controller
{
    public function index(){
        $gelombang = GelombangSidangSkripsi::with('tahunAjaran')->get();
        return view('mahasiswa.skripsi.pengajuan.sidang',
    compact('gelombang'));
    }
}
