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
use App\Models\master_nilai;
use App\Models\MasterKeuanganMh;
use Illuminate\Support\Facades\DB;
use App\helpers;

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
    public function show($id){
        $mhs = Mahasiswa::where('nim',$id)->first();
        $id = $mhs->id ?? 0;
        $idmhs = $mhs->id ?? 0;
        if($idmhs == 0){
            dd('User not found');
        }
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $tahun_ajaran_all = TahunAjaran::orderBy('id','desc')->get();
        $jumlah_ips = [];
        $nilai = [];
        $mk = [];
        $krs = [];
        $ips = [];
        $jumlah_valid = [];
        $tahun_ajaran_keterangan = [];
        foreach($tahun_ajaran_all as $tahun_ajaran){
            $jumlah_valid[$tahun_ajaran->id] = 0;
            $tahun_ajaran_keterangan[$tahun_ajaran->id] = "'" . $tahun_ajaran->keterangan . "'";
            $helpers = new helpers;
            $ta = $tahun_ajaran->id;
            $jumlah_ips[$ta] = 0;

            $title = 'KHS Mahasiswa';
            $kd_prodi_mhs = Prodi::where('id',$mhs->id_program_studi)->first()->kode_prodi;
            $kurikulum = Kurikulum::where('progdi',$kd_prodi_mhs)->where('angkatan','<=',$mhs->angkatan)->where('angkatan_akhir','>=',$mhs->angkatan)->get();

            if($kurikulum){
                foreach($kurikulum as $row){
                    $mk[] = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
                }
            }
            //$mk = MataKuliah::get();
            $krs_now = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul')
                        ->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                        ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                        ->where('krs.id_tahun', $ta)
                        ->where('id_mhs',$idmhs)
                        ->get();


            $jumlah_matkul=0;
            $jumlah_sks = 0;
            $sks = [];
            foreach($krs_now as $row){
                $jumlah_sks += ($row->sks_teori+$row->sks_praktek);
                $sks[$row->id_jadwal] = ($row->sks_teori+$row->sks_praktek);
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = '-';
                $jumlah_matkul++;
            }
            $get_nilai = master_nilai::where(['nim'=>$mhs->nim,'id_tahun'=>$ta])->get();
            $total_nilai = 0;
            foreach($get_nilai as $row){
                if($row->publish_tugas == 1){
                    //$nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = $row->ntugas;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = "-";
                }

                if($row->publish_uts == 1){
                    //$nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = $row->nuts;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = "-";
                }
                if($row->publish_uas == 1){
                    //$nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = $row->nuas;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = '-';
                }
                if($row->publish_tugas == 1 && $row->publish_uts == 1 && $row->publish_uas == 1){
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = $row->nakhir;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = $row->nhuruf;
                    $total_nilai += ($helpers->getKualitas($row->nhuruf))*$sks[$row->id_jadwal];
                }
                if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1){
                    $jumlah_valid[$ta]++;
                }

            }

            $krs[$ta] = $krs_now;
            if(!empty($jumlah_sks) || $jumlah_sks != 0){
                $ips[$ta] = round(($total_nilai / $jumlah_sks),2);
            }
        }
            $new_total_nilai = implode(",",$ips);
            $tahun_ajaran_keterangan = implode(",",$tahun_ajaran_keterangan);
            $no = 1;
            $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        return view('admin.akademik.khs.index', compact('mhs','title','new_total_nilai','tahun_ajaran_keterangan', 'permission','mk', 'krs', 'no','tahun_ajaran_all', 'idmhs','nilai','jumlah_matkul','jumlah_valid','id'));
    }
}
