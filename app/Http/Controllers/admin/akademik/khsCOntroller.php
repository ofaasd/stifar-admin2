<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\Kurikulum;
use App\Models\MatakuliahKurikulum;
use App\Models\Jadwal;
use App\Models\hari;
use App\Models\MasterRuang;
use App\Models\Sesi;
use App\Models\Krs;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;

class KhsController extends Controller
{
    //
    public function index(Request $request){
      $title = "Daftar Mahasiswa";
      $mhs = Mahasiswa::get();
      $no = 1;
      $prodi = Prodi::all();
      $jumlah = [];
      $nama = [];

      foreach($prodi as $row){
        $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
        $nama_prodi = explode(' ',$row->nama_prodi);
        $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
      }
      return view('admin.khs.index', compact('title', 'mhs', 'no', 'prodi','jumlah','nama'));
    }
    public function get_table_khs(Request $request){
      $id = $request->id;
      if($id == 0){
        $mhs = Mahasiswa::get();
        $no = 1;
        $prodi = Prodi::all();
        $jumlah = [];
        $nama = [];

        foreach($prodi as $row){
          $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
          $nama_prodi = explode(' ',$row->nama_prodi);
          $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        return view('admin.khs.table_khs', compact('mhs', 'no', 'prodi','jumlah','nama'));
      }else{
        $mhs = Mahasiswa::where('id_program_studi',$id)->get();
        $no = 1;
        $prodi = Prodi::all();
        $jumlah = [];
        $nama = [];

        foreach($prodi as $row){
          $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
          $nama_prodi = explode(' ',$row->nama_prodi);
          $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        return view('admin.khs.table_khs', compact('mhs', 'no', 'prodi','jumlah','nama'));
      }
    }
}
