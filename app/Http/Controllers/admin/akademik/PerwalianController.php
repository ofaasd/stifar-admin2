<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiBiodatum;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Kurikulum;
use App\Models\MatakuliahKurikulum;
use App\Models\Krs;
use App\Models\TahunAjaran;

class PerwalianController extends Controller
{
    //
    public function index(){
        $title = "Perwalian";
        $pegawai  = PegawaiBiodatum::all();
        $fake_id = 0;

        return view('admin/akademik/perwalian/index', compact('title','pegawai','fake_id'));
    }
    public function show(String $id){
        $title = "Daftar Mahasiswa Perwalian";
        $id_dsn = $id;
        $mhs = Mahasiswa::where('id_dsn_wali', $id_dsn)->get();
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $list_sks = [];
        $get_prodi = Mahasiswa::where('id_dsn_wali', $id_dsn)->select('id_program_studi')->distinct()->get();
        foreach($get_prodi as $row){
            $prodi = Prodi::find($row->id_program_studi);
            $get_kurikulum = Kurikulum::where('progdi',$prodi->kode_prodi)->get();
            foreach($get_kurikulum as $kuri){
                $matakuliah = MatakuliahKurikulum::select('mata_kuliahs.*')
                    ->leftJoin('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')
                    ->where('id_kurikulum',$kuri->id)
                    ->get();
                foreach($matakuliah as $mata){
                    $list_sks[$mata->id] = $mata->sks_teori + $mata->sks_praktek;
                }
            }
        }
        $jumlah_sks = [];
        $jumlah_sks_validasi = [];
        foreach($mhs as $row){
            $krs = Krs::select('a.*')->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')->where('id_mhs',$row->id)->get();
            $jumlah_sks[$row->id] = 0;
            $jumlah_sks_validasi[$row->id] = 0;
            foreach($krs as $k){
                $jumlah_sks[$row->id] += $list_sks[$k->id_mk];
                if($k->is_publish == 1){
                    $jumlah_sks_validasi[$row->id] += $list_sks[$k->id_mk];
                }
            }
        }

        $no = 1;
        return view('dosen.perwalian', compact('title', 'mhs', 'no', 'jumlah_sks', 'jumlah_sks_validasi'));
    }
}
