<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\PegawaiBiodatum;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Krs;
use App\Models\AbsensiModel;
use App\Models\Pertemuan;
use App\Models\Prodi;
use App\Models\KontrakKuliahModel;

class KrmController extends Controller
{
    public function index(){
        $title = "Daftar Jadwal Mengajar";
        $id_dsn = PegawaiBiodatum::where('user_id', Auth::id())->first();
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                        ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->where([ 'pengajars.id_dsn' => $id_dsn->id, 'jadwals.status' => 'Aktif'])->get();
        $no = 1;
        return view('dosen.krm', compact('title', 'jadwal', 'no'));
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
    public function setAbsensiSatuan($nim, $id_jadwal){
        $title = "Setting Absensi";
        $pertemuan = Pertemuan::select('pertemuans.*', 'pegawai_biodata.nama_lengkap', 'absensi_models.type')
                              ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pertemuans.id_dsn')
                              ->leftJoin('absensi_models', 'absensi_models.id_pertemuan', '=', 'pertemuans.id')
                              ->where('pertemuans.id_jadwal', $id_jadwal)->get();
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                        ->where([ 'jadwals.id' => $id_jadwal, 'jadwals.status' => 'Aktif'])->first();
        $mhs = Mahasiswa::where('nim', $nim)->first();
        $program_studi = Prodi::all();
        $prodi = [];
        foreach($program_studi as $row){
            $prodi[$row->id] = $row->nama_prodi;
        }
        $no = 1;
        return view('dosen.input_absen_satuan', compact('title', 'pertemuan', 'jadwal', 'mhs', 'no', 'prodi'));
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
        return json_encode(['kode' => 200]);
    }
    public function daftarMhsNilai($id){
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
        $kontrak = KontrakKuliahModel::where('id_jadwal', $id)->first();
        $no = 1;
        return view('dosen.daftar_mhs_nilai', compact('title', 'jadwal', 'daftar_mhs', 'no', 'kontrak'));
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
}
