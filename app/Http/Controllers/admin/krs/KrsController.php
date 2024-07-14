<?php

namespace App\Http\Controllers\admin\krs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\Jadwal;

class KrsController extends Controller
{
    public function index(Request $request)
    {
        $title = "Master KRS";
        $tahun_ajaran = TahunAjaran::get();
        return view('admin.akademik.krs.index', compact('title', 'tahun_ajaran'));
    }
    public function listMhs(Request $request){
        $ta = TahunAjaran::where('id', $request->ta)->first();
        $mhs = Mahasiswa::get();
        $no = 1;
        return view('admin.akademik.krs.vMhs', compact('ta', 'mhs', 'no'));
    }
    public function gantiStatus(Request $request){
        $id = $request->id;
        $krs = $request->krs;

        $qr = TahunAjaran::where('id', $id)->update(['krs' => $krs]);
        if ($qr) {
            return json_encode(['kode' => 200]);
        }
        return json_encode(['kode' => 204]);
    }
    public function inputadminKRS($idmhs, $ta){
        $title = 'Input KRS [Admin]';
        $mk = MataKuliah::get();
        $krs = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek', 'c.kode_sesi', 'd.nama_ruang')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('sesis as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where('krs.id_tahun', $ta)
                    ->get();
        $no = 1;
        return view('admin.akademik.krs.inputkrsadmin', compact('title', 'mk', 'krs', 'no', 'ta', 'idmhs'));
    }
    public function showJadwal(Request $request){
        $id_mk = $request->id_mk;
        $ta = $request->ta;
        $idmhs = $request->idmhs;
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'sesis.kode_sesi', 'ruang.nama_ruang', 'mata_kuliahs.nama_matkul')
                  ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                  ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                  ->leftJoin('sesis', 'sesis.id', '=', 'jadwals.id_sesi')
                  ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                  ->where(['jadwals.id_mk' => $id_mk, 'jadwals.status' => 'Aktif', 'jadwals.id_tahun' => $ta])->get();
        $n = 1;
        return view('admin.akademik.krs.showJadwal', compact('jadwal', 'n', 'idmhs'));
    }
    public function tambahadminKRS($id, $mhs){
        $data_jadwal = Jadwal::where('id', $id)->first();
        $qr = Krs::create(['id_jadwal' => $id, 'id_tahun' => $data_jadwal['id_tahun'], 'id_mhs' => $mhs, 'is_publish' => 0]);

        return back();
    }
    public function hapusadminKRS($id){
        Krs::where('id', $id)->delete();

        return back();
    }
}
