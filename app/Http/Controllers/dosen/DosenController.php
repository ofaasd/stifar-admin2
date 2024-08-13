<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\PegawaiBiodatum;
use Illuminate\Support\Facades\Auth;
use App\Models\Krs;
use App\Models\TahunAjaran;
use App\Models\Kurikulum;
use App\Models\Prodi;
use App\Models\MatakuliahKurikulum;
use App\Models\MasterKeuanganMh;

class DosenController extends Controller
{
    public function index(){
        $title = "Daftar Mahasiswa";
        $id_dsn = PegawaiBiodatum::where('user_id', Auth::id())->first();
        $mhs = Mahasiswa::where('id_dsn_wali', $id_dsn->id)->get();
        $no = 1;
        return view('dosen.perwalian', compact('title', 'mhs', 'no'));
    }
    public function detailKRS(Request $request){
        $mhs = Mahasiswa::where('id', $request->id)->first();
        $idmhs = $mhs->id;
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;

        $title = 'Pengajuan KRS';
        $kd_prodi_mhs = Prodi::where('id',$mhs->id_program_studi)->first()->kode_prodi;
        $kurikulum = Kurikulum::where('progdi',$kd_prodi_mhs)->first();
        $mk = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('id_kurikulum',$kurikulum->id)->get();
        $krs = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek', 'c.nama_sesi', 'd.nama_ruang')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where('krs.id_tahun', $ta)
                    ->get();
        $no = 1;
        $program_studi = Prodi::all();
        $prodi = [];
        foreach($program_studi as $row){
            $prodi[$row->id] = $row->nama_prodi;
        }
        // $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        return view('dosen._detail_krs_mhs', compact('mhs','title', 'mk', 'krs', 'no', 'ta', 'idmhs', 'prodi'));
    }
    public function valiKrsSatuan(Request $request){
        if ($request->tipe == 1) {
            Krs::where('id', $request->id_krs)->update([ 'is_publish' => 1]);
        }else{
            Krs::where('id', $request->id_krs)->update([ 'is_publish' => 0]);
        }
        return json_encode(['status' => 200 ]);
    }
    public function valiKrs(Request $request){
        Krs::where(['id_mhs' => $request->idmhs, 'id_tahun' => $request->ta])->update([ 'is_publish' => 1]);
        return json_encode(['status' => 200 ]);
    }
}
