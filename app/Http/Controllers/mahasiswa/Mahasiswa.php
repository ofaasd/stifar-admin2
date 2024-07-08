<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;

class Mahasiswa extends Controller
{
  public function index(Request $request)
  {
      $title = "Daftar Mahasiswa";
      $mhs = MahasiswaModel::get();
      $no = 1;
      return view('mahasiswa.daftar', compact('title', 'mhs', 'no'));
  }
}
