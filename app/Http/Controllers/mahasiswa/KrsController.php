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


class KrsController extends Controller
{
    //
    public function input(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $idmhs = $mhs->id;
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $prodi = Prodi::find($mhs->id_program_studi);

        $title = 'Input KRS';
        $kd_prodi_mhs = Prodi::where('id',$mhs->id_program_studi)->first()->kode_prodi;
        $kurikulum = Kurikulum::where('progdi',$kd_prodi_mhs)->where('angkatan','<=',$mhs->angkatan)->where('angkatan_akhir','>=',$mhs->angkatan)->where('thn_ajar',$ta)->get();
        $mk = [];
        if($kurikulum){
            foreach($kurikulum as $row){
                $mk[] = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
            }
        }
        //$mk = MataKuliah::get();
        $krs = Krs::select('krs.*', 'a.hari','a.kode_jadwal','a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul', 'c.nama_sesi', 'd.nama_ruang')
                    ->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('id_mhs',$idmhs)
                    ->get();
        $no = 1;

        $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        return view('mahasiswa.input_krs', compact('prodi','mhs','title', 'permission','mk', 'krs', 'no', 'ta', 'idmhs', 'tahun_ajaran'));
    }
    public function riwayat($id=0){
        $title = "Riwayat KRS Mahasiswa";
        $user_id = Auth::user()->id;
        $mahasiswa = Mahasiswa::where('user_id',$user_id)->first();
        $angkatan_ta = (int)($mahasiswa->angkatan . "1");

        $tahun_ajaran = TahunAjaran::where('status','Tidak Aktif')->where('kode_ta','>=',$angkatan_ta)->get();
        $krs = [];
        foreach($tahun_ajaran as $row){
            $krs[$row->id] = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul', 'c.nama_sesi', 'd.nama_ruang', 'b.rps')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where('krs.id_tahun', $row->id)
                    ->where('id_mhs',$mahasiswa->id)
                    ->where('is_publish',1)
                    ->get();
        }
        $no = 1;
        return view('mahasiswa.riwayat',compact('mahasiswa','krs','no','tahun_ajaran','title'));
    }
}
