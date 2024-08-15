<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\PegawaiBiodatum;
use App\Models\PmbPesertaOnline;
use App\Models\PmbPesertum;
use App\Models\TahunAjaran;
use App\Models\MataKuliah;
use App\Models\Kurikulum;
use App\Models\Prodi;

class DashboardController extends Controller
{
    //
    public function index(){
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $jumlah_mhs = Mahasiswa::count();
        $jumlah_pegawai = PegawaiBiodatum::count();
        $pendaftaran_online = PmbPesertaOnline::where('tahun_ajaran',$ta)->count();
        $pendaftaran_offline = PmbPesertum::where('tahun_ajaran',$ta)->count();
        $total_pendaftar = $pendaftaran_offline + $pendaftaran_online;
        $jumlah_matkul = MataKuliah::count();
        $jumlah_kurikulum = Kurikulum::count();
        $jumlah_teori = MataKuliah::whereNotNull('sks_teori')->count();
        $jumlah_praktek = MataKuliah::whereNotNull('sks_praktek')->count();
        $prodi = Prodi::all();
        $list_prodi = '';
        $i = 0;
        foreach($prodi as $row){
            if($i == 0){
                $list_prodi .= $row->nama_prodi;
            }else{
                $list_prodi .= ',' . $row->nama_prodi;
            }
            $i++;
        }
        return view('index', compact('jumlah_kurikulum','jumlah_teori','jumlah_praktek','jumlah_mhs','jumlah_pegawai','total_pendaftar','jumlah_matkul'));
    }
    public function mhs(){
        return view('index_mhs');
    }
    public function dosen(){
        return view('index_pegawai');
    }
}
