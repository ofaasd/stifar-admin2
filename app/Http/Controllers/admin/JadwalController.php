<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\hari;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\MasterRuang;
use App\Models\Waktu as Sesi;
use App\Models\TahunAjaran;
use App\Models\pengajar;
use App\Models\PegawaiBiodatum;
use App\Models\koordinator_mk;
use App\Models\anggota_mk;
use App\Models\Pertemuan;

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
        $ta = TahunAjaran::get();
        $id = 1;
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang')
                  ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                  ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                  ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                  ->where(['jadwals.id_mk' => $mk['id'], 'jadwals.status' => 'Aktif'])->get();
        $anggota = anggota_mk::select('anggota_mks.*', 'pegawai_biodata.id as id_dsn', 'pegawai_biodata.npp', 'pegawai_biodata.nama_lengkap', 'pegawai_biodata.gelar_belakang')
                        ->leftJoin('pegawai_biodata', 'anggota_mks.id_pegawai_bio', '=', 'pegawai_biodata.id')
                        ->where(['anggota_mks.idmk' => $mk['id']])->get();
        $warning = [];
        $i = 0;
        foreach($jadwal as $row){
            $pengajar = pengajar::where('id_jadwal',$row->id)->get();
            $waktu = Sesi::where('id',$row->id_sesi)->first();
            foreach ($pengajar as $dsn) {
                $pegawai = PegawaiBiodatum::find($dsn->id_dsn);
                $cekDosen = Jadwal::leftJoin('mata_kuliahs', 'mata_kuliahs.id','=','jadwals.id_mk')
                                ->leftJoin('pengajars', 'pengajars.id_jadwal','=','jadwals.id')
                                ->join('waktus','waktus.id','=','jadwals.id_sesi')
                                ->where(['pengajars.id_dsn' => $dsn->id_dsn,'jadwals.hari' => $row->hari, 'jadwals.id_tahun' => $row->id_tahun])
                                ->where('waktu_mulai','>=',$waktu->waktu_mulai)->where('waktu_mulai','<=',$waktu->waktu_selesai)
                                ->where('jadwals.id','<>',$row->id)
                                ->first();
                if ($cekDosen) {
                    $pesan = 'jadwal dengan dosen ' . $pegawai->nama_lengkap . ' bertabrakan dengan jadwal ' . $cekDosen->kode_jadwal . ' matakulianh : ' . $cekDosen->nama_matkul;
                    $warning[$i] = $pesan;
                    $i++;
                    //return json_encode(['status' => 'bentrok', 'kode' => 204]);
                }
            }
        }

        return view('admin.akademik.jadwal.input', compact('anggota', 'ta', 'id_mk','title','nama_mk', 'days', 'jadwal', 'id', 'ruang', 'sesi', 'warning'));
    }
    public function daftarJadwalHarian(Request $request){
        $title = "Jadwal Harian";
        $mk = MataKuliah::where('status', 'Aktif')->get();
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                    ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                    ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                    ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                    ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                    ->where(['jadwals.hari' => 'Senin'])
                    ->get();
        $no = 1;
        return view('admin.akademik.jadwal.jadwal_harian', compact('title', 'mk', 'no', 'jadwal'));
    }
    public function reqJadwalHarian(Request $request){
        $hari = $request->hari;
        $matakuliah = $request->matakuliah;

        $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                    ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                    ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                    ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                    ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                    ->get();
        if (($hari != 0) && ($matakuliah == 0)) {
            $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
            ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
            ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
            ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
            ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
            ->where(['jadwals.hari' => $hari])
            ->get();
        }
        if (($hari == 0) && ($matakuliah != 0)) {
            $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
            ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
            ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
            ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
            ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
            ->where(['jadwals.id_mk' => $matakuliah])
            ->get();
        }
        if (($hari != 0) && ($matakuliah != 0)) {
            $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
            ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
            ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
            ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
            ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
            ->where(['jadwals.hari' => $hari, 'jadwals.id_mk' => $matakuliah])
            ->get();
        }
        return json_encode(['data' => $q]);
    }
    public function daftarPertemuan(Request $request){
        $id_jadwal = $request->id_jadwal;

        return json_encode(['kode' => 200, 'pertemuan' => Pertemuan::select('pertemuans.*', 'pegawai_biodata.npp', 'pegawai_biodata.nama_lengkap', 'pegawai_biodata.gelar_belakang')
                                                                    ->leftJoin('pegawai_biodata', 'pertemuans.id_dsn', '=', 'pegawai_biodata.id')
                                                                    ->where(['pertemuans.id_jadwal' => $id_jadwal])->get()]);

    }
    public function hapusPertemuan($id){
        Pertemuan::where('id', $id)->delete();
        return back();
    }
    public function tambahPertemuan(Request $request){
        $id_jadwal = $request->id_jadwal;
        $tgl_pertemuan = $request->tgl_pertemuan;
        $nama_pengampu = $request->nama_pengampu;

        $qr = Pertemuan::create(['id_jadwal' => $id_jadwal, 'tgl_pertemuan' => $tgl_pertemuan, 'id_dsn' => $nama_pengampu]);
        if ($qr) {
            return json_encode(['kode' => 200, 'pertemuan' => Pertemuan::select('pertemuans.*', 'pegawai_biodata.nama_lengkap')->leftJoin('pegawai_biodata', 'pertemuans.id_dsn', '=', 'pegawai_biodata.id')->where(['pertemuans.id_jadwal' => $id_jadwal])->get()]);
        }
        return json_encode(['kode' => 204]);
    }
    public function jadwalPengampu(Request $request){
        $id_jadwal = $request->idjadwal;
        $qr = Pengajar::select('pengajars.*', 'pegawai_biodata.nama_lengkap', 'pegawai_biodata.gelar_belakang', 'pegawai_biodata.npp')
                        ->leftJoin('pegawai_biodata', 'pengajars.id_dsn','=','pegawai_biodata.id')
                        ->where('pengajars.id_jadwal', $id_jadwal)->get();
        return json_encode(['kode' => 200, 'daftar' => $qr]);
    }
    public function tambahPengampu(Request $request){
        $id_jadwal = $request->id_jadwal;
        $id_dsn = $request->id_dsn;
        Pengajar::create(['id_dsn' => $id_dsn, 'id_jadwal' => $id_jadwal]);

        return json_encode(['kode' => 200]);
    }
    public function hapusPengampu($id){
        Pengajar::where('id', $id)->delete();
        return back();
    }
    public function koordinatorMK($idmk){
        $title = 'Koordinator Matakuliah';
        $pegawai = PegawaiBiodatum::get();
        $koordinator = koordinator_mk::select('koordinator_mks.*', 'pegawai_biodata.npp', 'pegawai_biodata.nama_lengkap', 'pegawai_biodata.gelar_belakang')
                        ->leftJoin('pegawai_biodata', 'koordinator_mks.id_pegawai_bio', '=', 'pegawai_biodata.id')
                        ->where(['koordinator_mks.idmk' => $idmk])->get();
        $no = 1;
        return view('admin.akademik.jadwal.koordinator', compact('title', 'pegawai', 'idmk', 'koordinator', 'no'));
    }
    public function anggotaMK($idmk){
        $title = 'Koordinator Matakuliah';
        $pegawai = PegawaiBiodatum::get();
        $anggota = anggota_mk::select('anggota_mks.*', 'pegawai_biodata.npp', 'pegawai_biodata.nama_lengkap', 'pegawai_biodata.gelar_belakang')
                        ->leftJoin('pegawai_biodata', 'anggota_mks.id_pegawai_bio', '=', 'pegawai_biodata.id')
                        ->where(['anggota_mks.idmk' => $idmk])->get();
        $no = 1;
        return view('admin.akademik.jadwal.anggota', compact('title', 'pegawai', 'idmk', 'anggota', 'no'));
    }
    public function simpanAnggota(Request $request){
        $idmk = $request->idmk;
        $id_pegawai_bio = $request->id_pegawai_bio;
        $status = $request->status;
        $cek_qr = anggota_mk::where(['idmk' => $idmk, 'id_pegawai_bio' => $id_pegawai_bio])->count();
        if($cek_qr == 0){
           $qr = anggota_mk::create(['idmk' => $idmk, 'id_pegawai_bio' => $id_pegawai_bio, 'status' => $status]);

            if($qr){
                return json_encode(['status' => 'ok', 'kode' => 200]);
            }
            return json_encode(['status' => 'error', 'kode' => 204]);
        }else{
            return json_encode(['status' => 'error', 'kode' => 205]);
        }

    }
    public function hapusAnggota($id){
        $qr = anggota_mk::where('id', $id)->first();
        $idmk = $qr['idmk'];
        anggota_mk::where('id', $id)->delete();

        return redirect('/admin/masterdata/anggota-mk/'.$idmk);
    }
    public function simpanKoor(Request $request){
        $idmk = $request->idmk;
        $id_pegawai_bio = $request->id_pegawai_bio;

        $qr = koordinator_mk::create(['idmk' => $idmk, 'id_pegawai_bio' => $id_pegawai_bio]);

        if($qr){
            return json_encode(['status' => 'ok', 'kode' => 200]);
        }
        return json_encode(['status' => 'ok', 'kode' => 204]);
    }
    public function hapusKoor($id){
        $qr = koordinator_mk::where('id', $id)->first();
        $idmk = $qr['idmk'];
        koordinator_mk::where('id', $id)->delete();

        return redirect('/admin/masterdata/koordinator-mk/'.$idmk);
    }
    public function hapusJadwal($id){
        Jadwal::where('id', $id)->delete();
        pengajar::where('id_jadwal', $id)->delete();

        return back();
    }
    public function updateJadwal(Request $request){
        $kode_jadwal = $request->kjadwal;
        $id_mk = $request->id_mk;
        $hari = $request->hari;
        $ruang = $request->ruang;
        $sesi = $request->sesi;
        $kel = $request->kel;
        $kuota = $request->kuota;
        $status = $request->status;
        $id = $request->id;
        $tp = $request->tp;
        $taAktif = $request->tahun_ajaran;
        $waktu = Sesi::where('id',$sesi)->first();

        $cekJadwal = Jadwal::join('waktus','waktus.id','=','jadwals.id_sesi')->where(['hari' => $hari, 'id_tahun' => $taAktif, 'id_ruang' => $ruang])->where('waktu_mulai','>=',$waktu->waktu_mulai)->where('waktu_mulai','<=',$waktu->waktu_selesai)->first();
        // var_dump($taAktif);
        if($cekJadwal){
            return json_encode(['status' => 'bentrok', 'kode' => 203, 'kode_jadwal' => $cekJadwal['kode_jadwal']]);
        }
        Jadwal::where('id', $id)->update(
            [
                'kode_jadwal' => $kode_jadwal,
                'id_mk' => $id_mk,
                'hari' => $hari,
                'id_tahun' => $taAktif,
                'id_ruang' => $ruang,
                'id_sesi' => $sesi,
                'kel' => $kel,
                'kuota' => $kuota,
                'status' => $status,
                'tp' => $tp
            ]);
            return json_encode(['status' => 'ok', 'kode' => 200]);
    }
    public function createJadwal(Request $request){

        $id_mk = $request->id_mk;
        $matakuliah = MataKuliah::find($id_mk);
        $kode_jadwal = $matakuliah->kode_matkul . $request->kel;
        $hari = $request->hari;
        $ruang = $request->ruang;
        $sesi = $request->sesi;
        $kel = $request->kel;
        $kuota = $request->kuota;
        $status = $request->status;
        $dsn = $request->dsn;
        $tp = $request->tp;
        $taAktif = $request->tahun_ajaran;
        $waktu = Sesi::where('id',$sesi)->first();

        $cekJadwal = Jadwal::join('waktus','waktus.id','=','jadwals.id_sesi')->where(['hari' => $hari, 'id_tahun' => $taAktif, 'id_ruang' => $ruang])->where('waktu_mulai','>=',$waktu->waktu_mulai)->where('waktu_mulai','<=',$waktu->waktu_selesai)->first();
        // var_dump($taAktif);
        if($cekJadwal){
            return json_encode(['status' => 'bentrok', 'kode' => 203, 'kode_jadwal' => $cekJadwal['kode_jadwal']]);
        }
        // for ($i=0; $i < count($dsn); $i++) {
        //     $cekDosen = Jadwal::leftJoin('pengajars', 'pengajars.id_jadwal','=','jadwals.id')
        //                     ->where(['pengajars.id_dsn' => $dsn[$i],'jadwals.hari' => $hari, 'jadwals.id_tahun' => $taAktif, 'jadwals.id_sesi' => $sesi])
        //                     ->first();
        //     if ($cekDosen) {
        //         return json_encode(['status' => 'bentrok', 'kode' => 204]);
        //     }
        // }
        $id_jadwal = Jadwal::create(
                                [
                                    'kode_jadwal' => $kode_jadwal,
                                    'id_mk' => $id_mk,
                                    'hari' => $hari,
                                    'id_tahun' => $taAktif,
                                    'id_ruang' => $ruang,
                                    'id_sesi' => $sesi,
                                    'kel' => $kel,
                                    'kuota' => $kuota,
                                    'status' => $status,
                                    'tp' => $tp
                                ])->id;
        for ($i=0; $i < count($dsn); $i++) {
            pengajar::create(['id_jadwal' => $id_jadwal, 'id_dsn' => $dsn[$i]]);
        }
        return json_encode(['status' => 'ok', 'kode' => 200]);
    }
    public function tableAnggota(Request $request){
        $idmk = $request->idmk;
        $anggota = anggota_mk::select('anggota_mks.*', 'pegawai_biodata.npp', 'pegawai_biodata.nama_lengkap', 'pegawai_biodata.gelar_belakang')
                        ->leftJoin('pegawai_biodata', 'anggota_mks.id_pegawai_bio', '=', 'pegawai_biodata.id')
                        ->where(['anggota_mks.idmk' => $idmk])->get();
        $no = 1;
        return view('admin.akademik.jadwal.tableAnggota', compact('idmk', 'anggota', 'no'));
    }
}
