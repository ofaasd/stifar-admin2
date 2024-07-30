<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;
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

        $title = 'Input KRS';
        $mk = MataKuliah::get();
        $krs = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek', 'c.nama_sesi', 'd.nama_ruang')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where('krs.id_tahun', $ta)
                    ->get();
        $no = 1;
        $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        //return view('admin.akademik.krs.inputkrsadmin', compact('title', 'mk', 'krs', 'no', 'ta', 'idmhs'));
        return view('mahasiswa.input_krs', compact('title', 'permission','mk', 'krs', 'no', 'ta', 'idmhs'));
    }
}
