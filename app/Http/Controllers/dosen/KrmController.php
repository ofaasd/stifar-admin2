<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\PegawaiBiodatum;
use App\Models\MasterRuang;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Krs;
use App\Models\AbsensiModel;
use App\Models\Pertemuan;
use App\Models\Prodi;
use App\Models\KontrakKuliahModel;
use App\Models\master_nilai;
use App\Models\TahunAjaran;
use App\Models\MataKuliah;
use App\Models\hari;
use App\Models\Pengajar;
use App\Models\LogNilai;
use App\Models\Waktu as Sesi;
use App\Models\master_nilai as MasterNilai;
use Illuminate\Support\Facades\DB;
use Redirect;

class KrmController extends Controller
{
    public function index(){
        $title = "Kartu Rencana Mengajar";
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;
        $id_dsn = PegawaiBiodatum::where('user_id', Auth::id())->first();
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'mata_kuliahs.rps')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                        ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->where([ 'pengajars.id_dsn' => $id_dsn->id, 'jadwals.status' => 'Aktif', 'jadwals.id_tahun' => $id_tahun])->get();
        $no = 1;
        $jumlah_input_krs = [];
        foreach($jadwal as $row){
            $jumlah_input_krs[$row->id] = Krs::where('id_jadwal',$row->id)->count();
        }
        return view('dosen.krm', compact('title', 'jadwal', 'no', 'jumlah_input_krs'));
    }
    public function krm_riwayat(){
        $title = "Riwayat Mengajar";
        $tahun_ajaran = TahunAjaran::where('status','Tidak Aktif')->get();
        $id_dsn = PegawaiBiodatum::where('user_id', Auth::id())->first();
        $jadwal = [];
        $jumlah_input_krs = [];
        foreach($tahun_ajaran as $ta){
            $jadwal[$ta->id] = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'mata_kuliahs.rps')
                            ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                            ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                            ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                            ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                            ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                            ->where([ 'pengajars.id_dsn' => $id_dsn->id, 'jadwals.status' => 'Aktif', 'jadwals.id_tahun' => $ta->id])->get();
            $no = 1;

            foreach($jadwal[$ta->id] as $row){
                $jumlah_input_krs[$ta->id][$row->id] = Krs::where('id_jadwal',$row->id)->count();
            }
        }
        return view('dosen.krm_riwayat', compact('title', 'jadwal', 'no', 'jumlah_input_krs', 'tahun_ajaran'));
    }
    public function input_nilai(){
        $title = "Daftar Matakuliah";
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;
        $id_dsn = PegawaiBiodatum::where('user_id', Auth::id())->first();
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                        ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->where([ 'pengajars.id_dsn' => $id_dsn->id, 'jadwals.status' => 'Aktif', 'jadwals.id_tahun' => $id_tahun])->get();
        $no = 1;
        $nilaiPublish = [];
        $nilaiValidasi = [];
        foreach($jadwal as $row){
            $nilaiValidasi[$row->id] = MasterNilai::where('id_jadwal',$row->id)->where(['validasi_tugas'=>1,'validasi_uts'=>1,'validasi_uas'=>1])->count();
            $nilaiPublish[$row->id] = MasterNilai::where('id_jadwal',$row->id)->where(['publish_tugas'=>1,'publish_uts'=>1,'publish_uas'=>1])->count();
        }
        $jumlah_input_krs = [];
        foreach($jadwal as $row){
            $jumlah_input_krs[$row->id] = Krs::where('id_jadwal',$row->id)->where('id_tahun',$id_tahun)->count();
        }
        return view('dosen.input_nilai', compact('title','nilaiPublish','nilaiValidasi', 'jadwal', 'no', 'jumlah_input_krs',));
    }
    public function daftarMhs($id){
        $title = "Daftar Mahasiswa";
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                        ->where([ 'jadwals.id' => $id, 'jadwals.status' => 'Aktif'])->first();

        $daftar_mhs = Krs::select('krs.*', 'mhs.nim', 'mhs.nama', 'mhs.foto_mhs')
                         ->leftJoin('mahasiswa as mhs', 'mhs.id', '=', 'krs.id_mhs')
                         ->where('krs.id_jadwal', $id)->get();

        $no = 1;
        return view('dosen.daftar_mhs', compact('title', 'jadwal', 'daftar_mhs', 'no'));
    }

    public function daftarMhsNew($id,$id_pertemuan){
        $id_jadwal = $id;
        $pertemuan = $id_pertemuan;
        $title = "Input Absensi";
        $absensi = [];
        $pertemuan = Pertemuan::select('pertemuans.*', 'pegawai_biodata.nama_lengkap', 'absensi_models.type')
                              ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pertemuans.id_dsn')
                              ->leftJoin('absensi_models', 'absensi_models.id_pertemuan', '=', 'pertemuans.id')
                              ->where('pertemuans.id_jadwal', $id_jadwal)->where("pertemuans.id",$pertemuan)->first();
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                        ->where([ 'jadwals.id' => $id, 'jadwals.status' => 'Aktif'])->first();

        $daftar_mhs = Krs::select('krs.*', 'mhs.nim', 'mhs.nama', 'mhs.foto_mhs')
                        ->leftJoin('mahasiswa as mhs', 'mhs.id', '=', 'krs.id_mhs')
                        ->orderBy('nim','asc')
                        ->where('krs.id_jadwal', $id)->get();
        foreach($daftar_mhs as $daftar){
            $absensi[$daftar->id_mhs] = 1;
        }
        $total_hadir = 0;
        $total_tidak_hadir = 0;
        $total_sakit = 0;
        $total_izin = 0;
        $absensi_model = AbsensiModel::where(['id_jadwal' => $id_jadwal, 'id_pertemuan' => $id_pertemuan]);
        if($absensi_model->count() > 0){
            foreach($absensi_model->get() as $row){
                if($row->type == 0){
                    $total_tidak_hadir++;
                }elseif($row->type == 1){
                    $total_hadir++;
                }elseif($row->type == 2){
                    $total_sakit++;
                }else{
                    $total_izin++;
                }
                $absensi[$row->id_mhs] = $row->type;
            }
        }
        $user = Auth::user()->roles->pluck('name');
        $no = 1;
        if($user == 'admin'){
            return view('dosen.daftar_mhs_new', compact('total_hadir','total_tidak_hadir','total_sakit','total_izin','absensi','title','pertemuan', 'jadwal', 'daftar_mhs', 'no'));
        }else{
            return view('dosen.daftar_mhs_new2', compact('total_hadir','total_tidak_hadir','total_sakit','total_izin','absensi','title','pertemuan', 'jadwal', 'daftar_mhs', 'no'));
        }
    }
    public function bukaTutupAbsen($id, $id_pertemuan){
        $pertemuan = Pertemuan::where('pertemuans.id_jadwal', $id)->where("pertemuans.id",$id_pertemuan);
        $jadwal = Jadwal::leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')->where("jadwals.id",$id)->first();

        $kunci = 0;
        $q_pertemuan = $pertemuan->first();
        if($q_pertemuan->buka_kehadiran == 0){
            $daftar_mhs = Krs::select('krs.*', 'mhs.nim', 'mhs.nama', 'mhs.foto_mhs')
                        ->leftJoin('mahasiswa as mhs', 'mhs.id', '=', 'krs.id_mhs')
                        ->orderBy('nim','asc')
                        ->where('krs.id_jadwal', $id)->get();
            foreach($daftar_mhs as $row){
                $cek = AbsensiModel::where(['id_jadwal' => $id, 'id_pertemuan' => $id_pertemuan, 'id_mhs' => $row->id_mhs]);
                if($cek->count() == 0){
                    $new_absensi = new AbsensiModel;
                    $new_absensi->id_jadwal = $id;
                    $new_absensi->id_pertemuan = $id_pertemuan;
                    $new_absensi->id_mhs = $row->id_mhs;
                    $new_absensi->type = 0;
                    $new_absensi->save();
                }
            }
            $kunci = 1;
        }
        $waktu_selesai = date("H:i:s", strtotime($jadwal->waktu_selesai));
        $tanggal_expired = $q_pertemuan->tgl_pertemuan . " " . $waktu_selesai;
        if($kunci == 1){
            $pertemuan->update(['buka_kehadiran'=>$kunci,'tgl_expired'=>$tanggal_expired]);
        }else{
            $pertemuan->update(['buka_kehadiran'=>$kunci]);
        }
        return Redirect::back();
    }
    public function saveAbsensiNew(Request $request){
        $id_jadwal = $request->id_jadwal;
        $id_pertemuan = $request->id_pertemuan;
        $id_mhs = $request->id_mhs;
        $type = $request->type;

        foreach($id_mhs as $key=>$value){
            $cek = AbsensiModel::where(['id_jadwal' => $id_jadwal, 'id_pertemuan' => $id_pertemuan, 'id_mhs' => $value]);
            if($cek->count() > 0){
                $cek->update(['type'=>$type[$key]]);
            }else{
                $new_absensi = new AbsensiModel;
                $new_absensi->id_jadwal = $id_jadwal;
                $new_absensi->id_pertemuan = $id_pertemuan;
                $new_absensi->id_mhs = $value;
                $new_absensi->type = $type[$key];
                $new_absensi->save();
            }
        }
        return Redirect::back();
    }
    public function saveAbsensiSatuan(Request $request){
        $id_jadwal = $request->id_jadwal;
        $id_pertemuan = $request->id_pertemuan;
        $id_mhs = $request->id_mhs;
        $type = $request->type;

        $cek = AbsensiModel::where(['id_jadwal' => $id_jadwal, 'id_pertemuan' => $id_pertemuan, 'id_mhs' => $id_mhs])->first();

        if ($cek) {
            AbsensiModel::where(['id_jadwal' => $id_jadwal, 'id_pertemuan' => $id_pertemuan, 'id_mhs' => $id_mhs])->update(['type' => $type]);
        }else{
            AbsensiModel::create(['id_jadwal' => $id_jadwal, 'id_pertemuan' => $id_pertemuan, 'id_mhs' => $id_mhs, 'type' => $type]);
        }
        return json_encode(['kode' => 200, 'result' => $cek]);
    }
    public function inputAbsenBatch($id){
        $title = "Input Absensi Batch";
        $pertemuan = Pertemuan::select('pertemuans.*', 'pegawai_biodata.nama_lengkap', 'absensi_models.type')
                              ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pertemuans.id_dsn')
                              ->leftJoin('absensi_models', 'absensi_models.id_pertemuan', '=', 'pertemuans.id')
                              ->where('pertemuans.id_jadwal', $id)->get();
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                              ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                              ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                              ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                              ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                              ->where([ 'jadwals.id' => $id, 'jadwals.status' => 'Aktif'])->first();
        $id_jadwal = $id;
        return view('dosen.input_absen_batch', compact('title', 'pertemuan', 'id_jadwal', 'jadwal'));
    }
    public function pertemuanAbsensi(Request $request){
        $id_pertemuan = $request->id_pertemuan;
        $pertemuan = Pertemuan::find($id_pertemuan);
        $daftar_mhs = DB::select('select
                                        krs.*,
                                        mahasiswa.nim,
                                        mahasiswa.nama,
                                        (select
                                            type
                                        from absensi_models
                                        where id_pertemuan = '. $id_pertemuan .' and id_mhs = krs.id_mhs) as type
                                        from krs
                                    left join mahasiswa on krs.id_mhs = mahasiswa.id
                                    where krs.id_jadwal = '. $pertemuan->id_jadwal);

        $capaian = $pertemuan->capaian;
        $no = 1;
        return view('dosen._view_pertemuan_absensi_', compact('no', 'daftar_mhs', 'capaian', 'id_pertemuan'));
    }
    public function simpanCapaian(Request $request){
        $id_pertemuan = $request->id_pertemuan;
        $capaian = $request->capaian;
        Pertemuan::where('id', $id_pertemuan)->update(['capaian' => $capaian]);

        return json_encode(['msg' => 'ok']);
    }
    public function daftarMhsNilai($id){
        $title = "Input Nilai";
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                        ->where([ 'jadwals.id' => $id, 'jadwals.status' => 'Aktif'])->first();
        $daftar_mhs = Krs::select('krs.id_mhs as idmhs', 'krs.*', 'mhs.nim as nims', 'mhs.nama', 'mhs.foto_mhs', 'master_nilai.*')
                         ->leftJoin('mahasiswa as mhs', 'mhs.id', '=', 'krs.id_mhs')
                         ->leftJoin('master_nilai', function($join) {
                            $join->on('master_nilai.id_jadwal', '=', 'krs.id_jadwal');
                            $join->on('master_nilai.id_mhs', '=', 'mhs.id');
                         })
                         ->where('krs.id_jadwal', $id)->orderBy('mhs.nim','asc')->get();
        $action[1] = $daftar_mhs[0]->publish_tugas ?? 0;
        $action[2] = $daftar_mhs[0]->publish_uts ?? 0;
        $action[3] = $daftar_mhs[0]->publish_uas ?? 0;
        $actionvalid[1] = $daftar_mhs[0]->validasi_tugas ?? 0;
        $actionvalid[2] = $daftar_mhs[0]->validasi_uts ?? 0;
        $actionvalid[3] = $daftar_mhs[0]->validasi_uas ?? 0;
        $kontrak = KontrakKuliahModel::where('id_jadwal', $id)->first();
        $no = 1;
        return view('dosen.daftar_mhs_nilai', compact('title', 'jadwal', 'daftar_mhs', 'no', 'id', 'kontrak','action','actionvalid'));
    }

    public function saveNilai(Request $request){
        $id_jadwal = $request->id_jadwal;
        $id_mhs = $request->id_mhs;
        $mahasiswa = Mahasiswa::find($id_mhs);
        $tipe = $request->tipe;
        $nilai = $request->nilai;

        $cek = master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs])->first();
        $kontrak = KontrakKuliahModel::where('id_jadwal', $id_jadwal)->first();
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $na = 0;
        $nh = 'E';

        if ($cek) {
            if ($tipe == 1) {
                master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs, 'nim' => $mahasiswa->nim])->update(['ntugas' => $nilai]);
            }
            if ($tipe == 2) {
                master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs, 'nim' => $mahasiswa->nim])->update(['nuts' => $nilai]);
            }
            if ($tipe == 3) {
                master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs, 'nim' => $mahasiswa->nim])->update(['nuas' => $nilai]);
            }
            $cek = master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs, 'nim' => $mahasiswa->nim])->first();
            $kontrak_tugas = $kontrak['tugas']??0;
            $kontrak_uts = $kontrak['uts']??0;
            $kontrak_uas = $kontrak['uas']??0;
            $na = (floatval($cek['ntugas']??0) * floatval(($kontrak_tugas / 100))) +
                  (floatval($cek['nuts']??0) * floatval(($kontrak_uts / 100))) +
                  (floatval($cek['nuas']??0) * floatval(($kontrak_uas / 100)));
            $nh = 'E';
            $nh = \App\helpers::getNilaiHuruf($na);

            master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs, 'nim' => $mahasiswa->nim])->update(['nakhir' => $na, 'nhuruf' => $nh]);
            LogNilai::create(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs, 'id_tahun'=>$tahun_ajaran['id'],'status'=>2]);
            return json_encode(['kode' => 200, 'na' => $na, 'nh' => $nh]);
        }else{
            if ($tipe == 1) {
                master_nilai::create(['id_jadwal' => $id_jadwal, 'id_tahun' => $tahun_ajaran['id'], 'id_mhs' => $id_mhs, 'ntugas' => $nilai, 'nim' => $mahasiswa->nim]);
            }
            if ($tipe == 2) {
                master_nilai::create(['id_jadwal' => $id_jadwal, 'id_tahun' => $tahun_ajaran['id'], 'id_mhs' => $id_mhs, 'nuts' => $nilai, 'nim' => $mahasiswa->nim]);
            }
            if ($tipe == 3) {
                master_nilai::create(['id_jadwal' => $id_jadwal, 'id_tahun' => $tahun_ajaran['id'], 'id_mhs' => $id_mhs, 'nuas' => $nilai, 'nim' => $mahasiswa->nim]);
            }
            $cek = master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs, 'nim' => $mahasiswa->nim])->first();
            $kontrak_tugas = $kontrak['tugas']??0;
            $kontrak_uts = $kontrak['uts']??0;
            $kontrak_uas = $kontrak['uas']??0;
            $na = (floatval($cek['ntugas']??0) * floatval(($kontrak_tugas / 100))) +
                  (floatval($cek['nuts']??0) * floatval(($kontrak_uts / 100))) +
                  (floatval($cek['nuas']??0) * floatval(($kontrak_uas / 100)));
            $nh = 'E';
            $nh = \App\helpers::getNilaiHuruf($na);
            master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs, 'nim' => $mahasiswa->nim])->update(['nakhir' => $na, 'nhuruf' => $nh]);
            LogNilai::create(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs, 'id_tahun'=>$tahun_ajaran['id'],'status'=>1]);
            return json_encode(['kode' => 200, 'na' => $na, 'nh' => $nh]);
        }
    }
    public function saveNilaiBatch(Request $request){
        $id_jadwal = $request->id_jadwal;
        $id_mhs = $request->id_mhs;
        $mahasiswa = Mahasiswa::find($id_mhs);
        $nim = $request->nim;
        $nilai_tugas = $request->nilai_tugas;
        $nilai_uts = $request->nilai_uts;
        $nilai_uas = $request->nilai_uas;

        foreach($nim as $key=>$value){
            // echo "NIM : " . $value . "<br />";
            // echo "id_mhs : " . $id_mhs[$key] . "<br />";
            // echo "id jadwal : " . $id_jadwal . "<br />";
            // echo "NIlai Tugas : " . $nilai_tugas[$value] . "<br />";
            // echo "NIlai UTS : " . $nilai_uts[$value] . "<br />";
            // echo "NIlai UAS : " . $nilai_uas[$value] . "<br />";
            // echo "<br />";


            $cek_count = master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs[$key]])->count();
            $kontrak = KontrakKuliahModel::where('id_jadwal', $id_jadwal)->first();
            $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
            $na = 0;
            $nh = 'E';
            $kontrak_tugas = $kontrak['tugas']??0;
            $kontrak_uts = $kontrak['uts']??0;
            $kontrak_uas = $kontrak['uas']??0;
            // echo "Kontrak Tugas : " . $kontrak_tugas . "<br />";
            // echo "Kontrak UTS : " . $kontrak_uts . "<br />";
            // echo "Kontrak UAS : " . $kontrak_uas . "<br />";
            if($cek_count > 1){
                master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs[$key], 'nim' => $value])->delete();
            }
            $cek = master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs[$key]])->first();
            if ($cek) {
                $na = (floatval($nilai_tugas[$value]??0) * floatval(($kontrak_tugas / 100))) +
                        (floatval($nilai_uts[$value]??0) * floatval(($kontrak_uts / 100))) +
                        (floatval($nilai_uas[$value]??0) * floatval(($kontrak_uas / 100)));
                $nh = \App\helpers::getNilaiHuruf($na);
                $data = [
                    'ntugas' => $nilai_tugas[$value],
                    'nuts' => $nilai_uts[$value],
                    'nuas' => $nilai_uas[$value],
                    'nakhir' => $na,
                    'nhuruf' => $nh
                ];
                master_nilai::where(['id_jadwal' => $id_jadwal, 'id_mhs' => $id_mhs[$key], 'nim' => $value])->update($data);
            }else{
                $na = (floatval($nilai_tugas[$value]??0) * floatval(($kontrak_tugas / 100))) +
                        (floatval($nilai_uts[$value]??0) * floatval(($kontrak_uts / 100))) +
                        (floatval($nilai_uas[$value]??0) * floatval(($kontrak_uas / 100)));

                $nh = \App\helpers::getNilaiHuruf($na);
                $data = [
                    'id_jadwal' => $id_jadwal,
                    'id_mhs' => $id_mhs[$key],
                    'id_tahun' => $tahun_ajaran['id'],
                    'nim' => $value,
                    'ntugas' => $nilai_tugas[$value],
                    'nuts' => $nilai_uts[$value],
                    'nuas' => $nilai_uas[$value],
                    'nakhir' => $na,
                    'nhuruf' => $nh
                ];
                master_nilai::create($data);
            }
            // echo "Nilai Akhir: " . $na;
            // echo "<br /><br />";
        }
        return redirect('/dosen/nilai/' . $id_jadwal . '/input');
    }
    public function saveKontrak(Request $request){
        $id_jadwal = $request->id_jadwal;
        $tugas = $request->tugas;
        $uts = $request->uts;
        $uas = $request->uas;

        $cek = KontrakKuliahModel::where('id_jadwal', $id_jadwal)->first();
        if ($cek) {
            KontrakKuliahModel::where('id_jadwal', $id_jadwal)->update([ 'tugas' => $tugas, 'uts' => $uts, 'uas' => $uas ]);
        }else{
            KontrakKuliahModel::create([ 'id_jadwal' => $id_jadwal, 'tugas' => $tugas, 'uts' => $uts, 'uas' => $uas ]);
        }
        return json_encode(['kode' => 200]);
    }
    public function publishNilai(Request $request){
        $isi = ($request->action == 0) ? 1 : 0;
        $data = [
            'publish_' . $request->status => $isi,
        ];

        $update = master_nilai::where(['id_jadwal'=>$request->id_jadwal])->update($data);
        if($update){
            return json_encode(['kode' => 200]);
        }else{
            return json_encode(['kode' => 500]);
        }
    }
    public function validasiNilai(Request $request){
        $isi = ($request->action == 0) ? 1 : 0;
        $data = [
            'validasi_' . $request->status => $isi,
        ];

        $update = master_nilai::where(['id_jadwal'=>$request->id_jadwal])->update($data);
        if($update){
            return json_encode(['kode' => 200]);
        }else{
            return json_encode(['kode' => 500]);
        }
    }
    public function simpanRps(Request $request){
        $id_matkul = $request->id_mk;
        $filename = '';
        if ($request->file('rps') != null) {
            $file = $request->file('rps');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $tujuan_upload = 'assets/file/rps';
            $file->move($tujuan_upload,$filename);

            $matakuliah = MataKuliah::find($id_matkul);
            $matakuliah->rps = $filename;
            $matakuliah->rps_log = date('Y-m-d H:i:s');
            $matakuliah->save();
        }
        return redirect('/dosen/krm');
    }
    public function settingPertemuan(int $id = 0){
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;
        $title = "Jadwal Harian";
        $mk = MataKuliah::where('status', 'Aktif')->get();
        $id_prodi = 0;
        $id_dsn = PegawaiBiodatum::where('user_id', Auth::id())->first();
        $list_pengajar = [];
        $pegawai = PegawaiBiodatum::all();
        $list_pegawai = [];
        foreach($pegawai as $row){
            $list_pegawai[$row->id] = $row->nama_lengkap;
        }
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                ->Join('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                ->Join('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                ->Join('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                ->Join('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                ->Join('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                ->where('jadwals.id_tahun',$id_tahun)
                ->where('pengajars.id_dsn',$id_dsn->id)
                ->get();
        foreach($jadwal as $row){
            $cek_pertemuan = Pertemuan::where('id_jadwal',$row->id)->whereNotNull('tgl_pertemuan')->count();
            if($cek_pertemuan >= 14){
                $list_pertemuan[$row->id] = 'btn-success';
            }elseif($cek_pertemuan < 14 && $cek_pertemuan > 0){
                $list_pertemuan[$row->id] = 'btn-warning';
            }else{
                $list_pertemuan[$row->id] = 'btn-danger';
            }
            $pengajar = Pengajar::where('id_jadwal',$row->id)->get();
            $jumlah_pertemuan[$row->id] = $cek_pertemuan;
            $list_pengajar[$row->id] = '';
            $i = 1;
            foreach($pengajar as $peng){
                //echo $row->id;

                $list_pengajar[$row->id] .= '[' . $i . '] ' . $list_pegawai[$peng->id_dsn] . ',<br/>';
                $i++;
            }
        }
        $no = 1;

        $prodi = Prodi::all();
        $nama = [];

        $days = hari::get();
        $ruang = MasterRuang::get();
        $sesi = Sesi::orderBy('nama_sesi','asc')->get();
        $ta = TahunAjaran::get();

        $jumlah_input_krs = [];
        foreach($jadwal as $row){
            $jumlah_input_krs[$row->id] = Krs::where('id_jadwal',$row->id)->where('id_tahun',$id_tahun)->count();
        }

        foreach($prodi as $row){
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }

        $angkatans = Mahasiswa::select('angkatan')
            ->orderBy('angkatan', 'asc')
            ->whereNotNull('angkatan')
            ->distinct()
            ->get();

        $angkatan = [];
        foreach ($angkatans as $item) {
            $angkatan[] = $item->angkatan;
        }

        // Mendapatkan jumlah mahasiswa untuk setiap angkatan
        $angkatan = Mahasiswa::whereIn('angkatan', $angkatan)
            ->select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->pluck('total', 'angkatan');

        $totalMahasiswa = $angkatan->sum();
        // var_dump($list_pertemuan);
        $dosen = 1;

        return view('admin.akademik.jadwal.pertemuan', compact('title', 'list_pengajar','dosen','jumlah_pertemuan','list_pertemuan','ta','sesi','days','ruang','mk', 'no', 'jadwal','id_prodi','prodi','nama','jumlah_input_krs', 'angkatan', 'totalMahasiswa'));
    }
}
