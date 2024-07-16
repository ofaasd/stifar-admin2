<?php

namespace App\Http\Controllers\admin\krs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\Jadwal;
use Session;

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
        $krs = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek', 'c.nama_sesi', 'd.nama_ruang')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
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
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'c.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.nama_matkul')
                  ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                  ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                  ->leftJoin('waktus as c', 'jadwals.id_sesi', '=', 'c.id')
                  ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                  ->where(['jadwals.id_mk' => $id_mk, 'jadwals.status' => 'Aktif', 'jadwals.id_tahun' => $ta])->get();
        $n = 1;
        return view('admin.akademik.krs.showJadwal', compact('jadwal', 'n', 'idmhs'));
    }
    public function tambahadminKRS($id, $mhs){
        $data_jadwal = Jadwal::where('id', $id)->first();
        $cek_bentrok = Krs::select('krs.*', 'jadwals.hari', 'jadwals.id_sesi', 'mata_kuliahs.nama_matkul', 'c.nama_sesi')
                            ->leftJoin('jadwals', 'jadwals.id', '=', 'krs.id_jadwal')
                            ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                            ->leftJoin('waktus as c', 'jadwals.id_sesi', '=', 'c.id')
                            ->where(['jadwals.hari' => $data_jadwal['hari'], 'jadwals.id_sesi' => $data_jadwal['id_sesi'], 'jadwals.id_tahun' => $data_jadwal['id_tahun'], 'jadwals.status' => 'Aktif'])
                            ->get();
        echo count($cek_bentrok);
        if(count($cek_bentrok) >= 1){
            $tabel = '
                    <div class="alert alert-danger dark" role="alert">
                    <span class="mt-4"><b>Terdapat Jadwal Bentrok dengan :</b></span>
                    <table width="100%">
                        <tr>
                            <td>Matakuliah</td>
                            <td>Hari, Waktu</td>
                        </tr>
            ';
            foreach($cek_bentrok as $row){
                $tabel .= '
                            <tr>
                                <td>'. $row['nama_matkul'] .'</td>
                                <td>'. $row['hari'].', '. $row['kode_sesi'] .'</td>
                            </tr>
                        ';
            }
            $tabel .= '</table></div>';
            Session::put('krs', $tabel);
            return back();
        }else{
            Krs::create(['id_jadwal' => $id, 'id_tahun' => $data_jadwal['id_tahun'], 'id_mhs' => $mhs, 'is_publish' => 0]);
            $kuota = $data_jadwal['kuota'] - 1;
            Jadwal::where('id', $id)->update(['kuota' => $kuota]);
            Session::put('krs', '<div class="alert alert-success dark mt-4" role="alert">Jadwal Berhasil di Tambahkan</div>');

            return back();
        }
    }
    public function hapusadminKRS($id){
        $qr = Krs::where('id', $id)->first();
        $qr_jadwal = Jadwal::where('id', $qr['id_jadwal'])->first();
        $kuota = $qr_jadwal['kuota'] + 1;
        Jadwal::where('id', $qr['id_jadwal'])->update(['kuota' => $kuota]);

        Krs::where('id', $id)->delete();

        return back();
    }
}
