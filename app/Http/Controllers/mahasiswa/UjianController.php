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
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    //
    public function index(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $idmhs = $mhs->id;
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;

        $title = 'Input KRS';
        $kd_prodi_mhs = Prodi::where('id',$mhs->id_program_studi)->first()->kode_prodi;
        $kurikulum = Kurikulum::where('progdi',$kd_prodi_mhs)->where('angkatan','<=',$mhs->angkatan)->where('angkatan_akhir','>=',$mhs->angkatan)->get();
        $mk = [];
        if($kurikulum){
            foreach($kurikulum as $row){
                $mk[] = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
            }
        }
        //$mk = MataKuliah::get();
        $krs = Krs::select('krs.*', 'a.hari', 'a.kel', 'a.kode_jadwal', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul', 'c.nama_sesi', 'd.nama_ruang','tbl_jadwal_ujian.tanggal_uts', 'tbl_jadwal_ujian.jam_mulai_uts','tbl_jadwal_ujian.jam_selesai_uts','tbl_jadwal_ujian.id_ruang_uts','tbl_jadwal_ujian.tanggal_uas','tbl_jadwal_ujian.jam_mulai_uas','tbl_jadwal_ujian.jam_selesai_uas','tbl_jadwal_ujian.id_ruang_uas')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->leftJoin('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id_jadwal', '=', 'a.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('id_mhs',$idmhs)
                    ->get();
        $no = 1;
        $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        return view('mahasiswa.ujian.index', compact('mhs','title', 'permission','mk', 'krs', 'no', 'ta', 'idmhs'));
    }
}
