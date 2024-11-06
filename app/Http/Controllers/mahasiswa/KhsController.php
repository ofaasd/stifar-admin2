<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\TahunAjaran;
use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\MatakuliahKurikulum;
use App\Models\MasterKeuanganMh;
use App\Models\master_nilai;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class KhsController extends Controller
{
    //
    public function index(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $idmhs = $mhs->id;
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;

        $title = 'KHS Mahasiswa';
        $kd_prodi_mhs = Prodi::where('id',$mhs->id_program_studi)->first()->kode_prodi;
        $kurikulum = Kurikulum::where('progdi',$kd_prodi_mhs)->where('angkatan','<=',$mhs->angkatan)->where('angkatan_akhir','>=',$mhs->angkatan)->get();
        $mk = [];
        if($kurikulum){
            foreach($kurikulum as $row){
                $mk[] = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
            }
        }
        //$mk = MataKuliah::get();
        $krs_now = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('id_mhs',$idmhs)
                    ->get();
        $nilai = [];
        foreach($krs_now as $row){
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = 0;
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = 0;
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = 0;
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = 0;
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = 0;
        }
        $get_nilai = master_nilai::where(['nim'=>$mhs->nim,'id_tahun'=>$ta])->get();
        foreach($get_nilai as $row){
            if($row->publish_tugas == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = $row->ntugas;
            }

            if($row->publish_uts == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = $row->nuts;
            }
            if($row->publish_uas == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = $row->nuas;
            }
            if($row->publish_tugas == 1 && $row->publish_uts == 1 && $row->publish_uas == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = $row->nakhir;
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = $row->nhuruf;
            }
        }
        $krs = $krs_now;
        $no = 1;
        $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        return view('mahasiswa.khs', compact('mhs','title', 'permission','mk', 'krs', 'no', 'ta', 'idmhs','nilai'));
    }
}
