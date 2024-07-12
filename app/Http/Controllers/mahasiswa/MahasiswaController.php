<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
  public function index(Request $request)
  {
      $title = "Daftar Mahasiswa";
      $mhs = Mahasiswa::get();
      $no = 1;
      return view('mahasiswa.daftar', compact('title', 'mhs', 'no'));
  }
  public function detail($nim){
    $title = "Detail Mahasiswa";
    $detail = Mahasiswa::where('nim', $nim)->get();
    
    return view('mahasiswa.detail', compact('title', 'detail'));
  }
}
