<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\hari;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\MasterRuang;
use App\Models\Sesi;
use App\Models\TahunAjaran;
use App\Models\pengajar;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $title = "Jadwal";
        $mk = MataKuliah::where('status', 'Aktif')->get();
        $no = 1;
        return view('admin.akademik.jadwal.index', compact('title', 'mk', 'no'));
    }
    public function daftarJadwal($id){
        $title = 'Buat Jadwal';
        $mk = MataKuliah::find($id);
        $nama_mk = $mk['nama_matkul'];
        $id_mk = $mk['id'];
        $days = hari::get();
        $ruang = MasterRuang::get();
        $sesi = Sesi::get();
        $id = 1;
        $jadwal = Jadwal::with('pengajar')
                  ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                  ->leftJoin('mata_kuliahs as mk', 'mk.id', '=', 'jadwals.id_mk')
                  ->leftJoin('sesis', 'sesis.id', '=', 'jadwals.id_sesi')
                  ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                  ->where(['jadwals.id_mk' => $id, 'jadwals.status' => 'Aktif'])->get();

        return view('admin.akademik.jadwal.input', compact('id_mk','title','nama_mk', 'days', 'jadwal', 'id', 'ruang', 'sesi'));
    }
    public function createJadwal(Request $request){
        $kode_jadwal = $request->kjadwal;
        $id_mk = $request->id_mk;
        $hari = $request->hari;
        $ruang = $request->ruang;
        $sesi = $request->sesi;
        $kel = $request->kel;
        $kuota = $request->kuota;
        $status = $request->status;
        $dsn = $request->dsn;
        $taAktif = TahunAjaran::where('status', 'Aktif')->first();
        $cekJadwal = Jadwal::where(['hari' => $hari, 'id_tahun' => $taAktif['id'], 'id_ruang' => $ruang, 'id_sesi' => $sesi])->first();
        if($cekJadwal){
            return json_encode(['status' => 'bentrok', 'kode' => 203, 'kode_jadwal' => $cekJadwal['kode_jadwal']]);
        }
        for ($i=0; $i < count($dsn); $i++) {
            $cekDosen = Jadwal::leftJoin('pengajars', 'pengajars.id_jadwal','=','jadwals.id')
                            ->where(['pengajars.id_dsn' => $dsn[$i],'jadwals.hari' => $hari, 'jadwals.id_tahun' => $taAktif['id'], 'jadwals.id_sesi' => $sesi])
                            ->first();
            if ($cekDosen) {
                return json_encode(['status' => 'bentrok', 'kode' => 204]);
            }
        }
        $id_jadwal = Jadwal::create(
                                [
                                    'kode_jadwal' => $kode_jadwal,
                                    'id_mk' => $id_mk,
                                    'hari' => $hari,
                                    'id_tahun' => $taAktif['id'],
                                    'id_ruang' => $ruang,
                                    'id_sesi' => $sesi,
                                    'kel' => $kel,
                                    'kuota' => $kuota,
                                    'status' => $status
                                ])->id;
        for ($i=0; $i < count($dsn); $i++) {
            pengajar::create(['id_jadwal' => $id_jadwal, 'id_dsn' => $dsn[$i]]);
        }
        return json_encode(['status' => 'ok', 'kode' => 200]);
    }
}
