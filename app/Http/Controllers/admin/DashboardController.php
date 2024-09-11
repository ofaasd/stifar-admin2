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
use App\Models\MatakuliahKurikulum;
use App\Models\Prodi;
use App\Models\Krs;
use Auth;

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
        $list_teori = '';
        $list_praktek = '';
        foreach($prodi as $row){
            $kurikulum = Kurikulum::where('progdi',$row->kode_prodi)->get();
            $matakuliah_praktek = 0;
            $matakuliah_teori = 0;

            foreach($kurikulum as $kur ){
                $matakuliah_praktek += MatakuliahKurikulum::join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('id_kurikulum',$kur->id)->whereNotNull('sks_praktek')->count();
                $matakuliah_teori += MatakuliahKurikulum::join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('id_kurikulum',$kur->id)->whereNotNull('sks_teori')->count();
            }

            if($i == 0){
                $list_prodi .= "'" . $row->nama_prodi . "'";
                $list_teori .= "" . $matakuliah_teori . "";
                $list_praktek .= "" . $matakuliah_praktek . "";
            }else{
                $list_prodi .= ",'" . $row->nama_prodi . "'";
                $list_teori .= "," . $matakuliah_teori . "";
                $list_praktek .= "," . $matakuliah_praktek . "";
            }
            $i++;
        }
        return view('index', compact('list_praktek','list_teori','list_prodi','jumlah_kurikulum','jumlah_teori','jumlah_praktek','jumlah_mhs','jumlah_pegawai','total_pendaftar','jumlah_matkul'));
    }
    public function mhs(){
        $user_id = Auth::user()->id;
        $mahasiswa = Mahasiswa::where('user_id',$user_id)->first();
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $krs = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul', 'c.nama_sesi', 'd.nama_ruang')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('id_mhs',$mahasiswa->id)
                    ->get();
        $no = 1;
        return view('index_mhs',compact('mahasiswa','krs','no'));
    }
    public function dosen(){
        return view('index_pegawai');
    }
}
