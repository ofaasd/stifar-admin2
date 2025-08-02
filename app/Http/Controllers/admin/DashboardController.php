<?php

namespace App\Http\Controllers\admin;

use Auth;
use App\Models\Krs;
use App\Models\Prodi;
use App\Models\Jadwal;
use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\PmbPesertum;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\PegawaiBiodatum;
use App\Models\PmbPesertaOnline;
use Illuminate\Support\Facades\DB;
use App\Models\MatakuliahKurikulum;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //
    public function index(){
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $jumlah_mhs = Mahasiswa::count();
        $jumlah_pegawai = PegawaiBiodatum::count();
        $pendaftaran_online = PmbPesertaOnline::count();
        $pendaftaran_offline = PmbPesertum::count();
        $total_pendaftar = $pendaftaran_offline + $pendaftaran_online;
        $jumlah_matkul = MataKuliah::count();
        $jumlah_kurikulum = Kurikulum::count();
        $jumlah_teori = MataKuliah::whereNotNull('sks_teori')->count();
        $jumlah_praktek = MataKuliah::whereNotNull('sks_praktek')->count();
        $prodi = Prodi::all();
        $list_prodi = '';
        $list_rombel = '';
        $list_valid = '';
        $list_tidak_valid = '';
        $i = 0;
        $list_teori = '';
        $list_praktek = '';
        $pembayaran = Mahasiswa::select('master_jadwal.rombel',
        DB::raw('
            SUM(CASE WHEN pembayaran.krs = 1 AND pembayaran.uts = 1 AND pembayaran.uas = 1 THEN 1 ELSE 0 END) as total_valid,
            SUM(CASE WHEN pembayaran.krs = 0 OR pembayaran.uts = 0 OR pembayaran.uas = 0 THEN 1 ELSE 0 END) as total_tidak_valid
        '))
    ->join('krs', 'krs.id_mhs', '=', 'mahasiswa.id')
    ->join('master_jadwal', 'master_jadwal.id_jadwal', '=', 'krs.id_jadwal')
    ->join('master_keuangan_mhs as pembayaran', 'pembayaran.id_mahasiswa', '=', 'mahasiswa.id')
    ->groupBy('master_jadwal.rombel')
    ->get();

    foreach ($pembayaran as $index => $validasi) {
        if ($index == 0) {
            $list_rombel .= "'" . $validasi->rombel . "'";
            $list_valid .= "" . $validasi->total_valid . "";
            $list_tidak_valid .= "" . $validasi->total_tidak_valid . "";
        } else {
            $list_rombel .= ",'" . $validasi->rombel . "'";
            $list_valid .= "," . $validasi->total_valid . "";
            $list_tidak_valid .= "," . $validasi->total_tidak_valid . "";
        }
    }
    
    
            foreach($prodi as $row){
            $kurikulum = Kurikulum::where('progdi',$row->kode_prodi)->get();
            $matakuliah_praktek = 0;
            $matakuliah_teori = 0;

            foreach($kurikulum as $kur ){
                $matakuliah_praktek += MatakuliahKurikulum::join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('id_kurikulum',$kur->id)->whereNotNull('sks_praktek')->count();
                $matakuliah_teori += MatakuliahKurikulum::join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('id_kurikulum',$kur->id)->whereNotNull('sks_teori')->count();
            }

            if($i == 0){
                $list_prodi .= "'" . $row->nama_prodi . "'";
                $list_teori .= "" . $matakuliah_teori . "";
                $list_praktek .= "" . $matakuliah_praktek . "";
            }else{
                $list_prodi .= ",'" . $row->nama_prodi . "'";
                $list_teori .= "," . $matakuliah_teori . "";
                $list_praktek .= "," . $matakuliah_praktek . "";
            }
            $i++;
        }
        return view('index', compact('list_praktek','list_teori','list_prodi','jumlah_kurikulum','jumlah_teori','jumlah_praktek','jumlah_mhs','jumlah_pegawai','total_pendaftar','jumlah_matkul','list_rombel', 'list_valid', 'list_tidak_valid'));
    }
    public function mhs(){
        $user_id = Auth::user()->id;
        $mahasiswa = Mahasiswa::where('user_id',$user_id)->first();
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $krs = Krs::select('krs.*', 'a.hari','a.kode_jadwal', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul', 'c.nama_sesi', 'd.nama_ruang', 'b.rps')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('id_mhs',$mahasiswa->id)
                    ->where('is_publish',1)
                    ->get();
        $no = 1;
        return view('index_mhs',compact('mahasiswa','krs','no'));
    }
    public function dosen(){
        $user_id = Auth::user()->id;
        $pegawai = PegawaiBiodatum::where('user_id',$user_id)->first();
        $perwalian = Mahasiswa::where('id_dsn_wali',$pegawai->id)->count();
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
                        ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->where([ 'pengajars.id_dsn' => $pegawai->id, 'jadwals.status' => 'Aktif', 'jadwals.id_tahun'=>$tahun_ajaran->id]);
        $total_jadwal = $jadwal->count();
        $jadwal = $jadwal->get();
        $no = 1;
        return view('index_pegawai',compact('perwalian','total_jadwal','jadwal','no'));
    }
    public function akademik(){
        $prodi = Prodi::all();
        $list_jumlah_krs = [];
        $list_total = [];
        $angkatan = Mahasiswa::select('angkatan')->orderBy('angkatan','desc')->distinct()->get();
        $list_prodi = '';
        $list_jumlah_krs_valid = [];
        $list_jumlah_krs_invalid = [];
        $list_total_mahasiswa = [];
        $ta = TahunAjaran::where('status','Aktif')->first()->id;
        foreach($angkatan as $value){
            $list_total_mahasiswa[$value->angkatan] = '';
            $list_jumlah_krs[$value->angkatan] = '';
            $list_jumlah_krs_valid[$value->angkatan] = '';
            $list_jumlah_krs_invalid[$value->angkatan] = '';
        }

        foreach($prodi as $row){
            $list_prodi .= "'" . $row->nama_prodi . "',";
            foreach($angkatan as $value){
                $total = Mahasiswa::where('angkatan',$value->angkatan)->where('status',1)->where('id_program_studi',$row->id)->count();
                $total_input = Krs::join('mahasiswa','mahasiswa.id','=','krs.id_mhs')->where('mahasiswa.angkatan',$value->angkatan)->where('id_program_studi', $row->id)->where('id_tahun',$ta)->distinct()->count('id_mhs');
                $total_input_valid = Krs::join('mahasiswa','mahasiswa.id','=','krs.id_mhs')->where('mahasiswa.angkatan',$value->angkatan)->where('id_program_studi', $row->id)->where('is_publish',1)->where('id_tahun',$ta)->distinct()->count('id_mhs');
                $list_jumlah_krs[$value->angkatan] .=  $total_input . ',';
                $list_total_mahasiswa[$value->angkatan] .= ($total - $total_input) . ',';
                $list_jumlah_krs_valid[$value->angkatan] .= $total_input_valid . ',';
                $list_jumlah_krs_invalid[$value->angkatan] .= ($total_input - $total_input_valid) . ',';
            }
        }
        return view('admin.akademik.index_view',compact('prodi','list_jumlah_krs','list_total_mahasiswa','list_jumlah_krs_valid','list_jumlah_krs_invalid','list_prodi','angkatan'));
    }
}
