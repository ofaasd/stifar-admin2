<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Prodi;

class MahasiswaController extends Controller
{
  public function index(Request $request)
  {
      $title = "Daftar Mahasiswa";
      $mhs = Mahasiswa::get();
      $no = 1;
      return view('mahasiswa.daftar', compact('title', 'mhs', 'no'));
  }
  public function edit($nim){
    $title = "Mahasiswa";
    $mahasiswa = Mahasiswa::where('nim', $nim)->first();
    $program_studi = Prodi::all();
    $prodi = [];
    foreach($program_studi as $row){
        $prodi[$row->id] = $row->nama_prodi;
    }
    $agama = array('1'=>'Islam','Kristen','Katolik','Hindu','Budha','Konghuchu','Lainnya');


    return view('mahasiswa.edit', compact('title', 'mahasiswa','prodi','agama'));
  }
  public function detail($nim){
    $title = "Detail Mahasiswa";
    $detail = Mahasiswa::where('nim', $nim)->get();

    return view('mahasiswa.detail', compact('title', 'detail'));
  }
}
