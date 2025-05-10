<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\Kurikulum;
use App\Models\MatakuliahKurikulum;
use App\Models\Jadwal;
use App\Models\hari;
use App\Models\MasterRuang;
use App\Models\Sesi;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\master_nilai as MasterNilai;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    //
    public function index(int $id=0){
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;
        $title = "Jadwal Harian";
        $mk = MataKuliah::where('status', 'Aktif')->get();
        $id_prodi = 0;
        if($id != 0){
            $id_prodi = $id;
            $prodi = Prodi::find($id);

            $kurikulum = Kurikulum::where('progdi',$prodi->kode_prodi)->get();
            $list_mk = [];
            if($kurikulum){
                foreach($kurikulum as $row){
                    $mk_kurikulum = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
                    foreach($mk_kurikulum as $mkkurikulum){
                        $list_mk[] = $mkkurikulum->id;
                    }
                }
            }


            $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                    ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                    ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                    ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                    ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                    ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                    ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                    ->whereIn('id_mk', $list_mk)
                    ->where('jadwals.id_tahun',$id_tahun)
                    ->get();

        }else{
            $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                    ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                    ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                    ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                    ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                    ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                    ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                    ->where('jadwals.id_tahun',$id_tahun)
                    ->get();
        }
        $nilaiPublish = [];
        $nilaiValidasi = [];
        foreach($jadwal as $row){
            $nilaiValidasi[$row->id] = MasterNilai::where('id_jadwal',$row->id)->where(['validasi_tugas'=>1,'validasi_uts'=>1,'validasi_uas'=>1])->count();
            $nilaiPublish[$row->id] = MasterNilai::where('id_jadwal',$row->id)->where(['publish_tugas'=>1,'publish_uts'=>1,'publish_uas'=>1])->count();
        }
        $no = 1;

        $prodi = Prodi::all();

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

        return view('admin.akademik.nilai.index', compact('nilaiPublish','nilaiValidasi','title','no', 'jadwal','id_prodi','prodi','nama','jumlah_input_krs', 'angkatan', 'totalMahasiswa'));
    }
}
