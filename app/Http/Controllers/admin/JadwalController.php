<?php

namespace App\Http\Controllers\admin;

use App\Models\Krs;
use App\Models\hari;
use App\Models\Prodi;
use App\Models\Jadwal;
use App\Models\pengajar;
use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Models\Pertemuan;
use App\Models\anggota_mk;
use App\Models\MataKuliah;
use App\Models\MasterRuang;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\Waktu as Sesi;
use App\Models\koordinator_mk;
use App\Models\PegawaiBiodatum;
use Illuminate\Support\Facades\DB;

use App\Models\MatakuliahKurikulum;
use App\Http\Controllers\Controller;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $title = "Jadwal";
        $mk[] = MataKuliah::where('status', 'Aktif')->get();
        $no = 1;
        $prodi = Prodi::all();
        $nama = [];
        $id_prodi = 0;

        foreach($prodi as $row){
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        $jumlah_anggota = [];
        foreach($mk as $value){
            foreach($value as $row){
                $jumlah_anggota[$row->id] = anggota_mk::where('idmk',$row->id)->count();
            }
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

        return view('admin.akademik.jadwal.index', compact('title', 'mk', 'no','prodi', 'nama', 'id_prodi' ,'jumlah_anggota', 'angkatan', 'totalMahasiswa'));
    }
    public function jadwal_prodi(String $id){
        $title = "Jadwal";
        $id_prodi = $id;
        $prodi = Prodi::find($id);
        $kurikulum = Kurikulum::where('progdi',$prodi->kode_prodi)->get();
        $mk = [];
        if($kurikulum){
            foreach($kurikulum as $row){
                $mk[] = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
            }
        }
        $jumlah_anggota = [];
        foreach($mk as $value){
            foreach($value as $row){
                $jumlah_anggota[$row->id] = anggota_mk::where('idmk',$row->id)->count();
            }
        }
        //$mk = MataKuliah::where('status', 'Aktif')->get();
        $no = 1;
        $prodi = Prodi::all();
        $nama = [];

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

        return view('admin.akademik.jadwal.index', compact('title', 'mk', 'no','prodi', 'nama', 'id_prodi' ,'jumlah_anggota', 'angkatan', 'totalMahasiswa'));
    }
    public function daftarJadwal($id){
        $title = 'Buat Jadwal';
        $mk = MataKuliah::find($id);
        $nama_mk = $mk['nama_matkul'];
        $id_mk = $mk['id'];
        $days = hari::get();
        $ruang = MasterRuang::get();
        $sesi = Sesi::orderBy('nama_sesi','asc')->get();
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
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;
        $title = "Jadwal Harian";
        $mk = MataKuliah::where('status', 'Aktif')->get();
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                    ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                    ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                    ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                    ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                    ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                    ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                    ->get();
        $no = 1;
        $id_prodi = 0;
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

        return view('admin.akademik.jadwal.jadwal_harian', compact('title', 'ta','sesi','days','ruang','mk', 'no', 'jadwal','id_prodi','prodi','nama','jumlah_input_krs', 'angkatan', 'totalMahasiswa'));
    }

    public function daftarDistribusiSks(Request $request){
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;
        $title = "Distribusi SKS";
        $distribusi = Jadwal::select(
            'pengajars.id_dsn',
            DB::raw('MAX(pegawai_biodata.nama_lengkap) as nama_dosen'),
            DB::raw('COALESCE(SUM(mata_kuliahs.sks_teori), 0) + COALESCE(SUM(mata_kuliahs.sks_praktek), 0) as total_sks')
        )
        ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
        ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
        ->where('jadwals.id_tahun', $id_tahun)
        ->groupBy('pengajars.id_dsn')
        ->get();

        $distribusiPengajar = pengajar::select(
            'pengajars.id_dsn',
            'pegawai_biodata.nama_lengkap as nama_dosen',
            DB::raw('SUM(mata_kuliahs.sks_teori + mata_kuliahs.sks_praktek) as total_sks')
        )
        ->leftJoin('jadwals', 'jadwals.id', '=', 'pengajars.id_jadwal')
        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'jadwals.id_mk')
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
        ->where('jadwals.id_tahun', $id_tahun)
        ->groupBy('pengajars.id_dsn', 'pegawai_biodata.nama_lengkap')
        ->get();

        $distribusiByDosen = $distribusi->mapToGroups(function ($item) {
            return [
                $item->id_dsn => [
                    'id_dsn' => $item->id_dsn,
                    'nama_dosen' => $item->nama_dosen,
                    'total_sks' => $item->total_sks,
                ]
            ];
        })->map(function ($items) {
            $firstItem = $items->first();
            return [
                'id_dsn' => $firstItem['id_dsn'],
                'nama_dosen' => $firstItem['nama_dosen'],
                'total_sks' => $items->sum('total_sks'),
            ];
        });

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

        // Return ke view dengan variabel yang berisi angkatan dan jumlah mahasiswa
        $no = 1;
        return view('admin.akademik.jadwal.distribusi_sks', compact('title', 'ta','sesi','days','ruang','no', 'distribusiByDosen', 'angkatan', 'totalMahasiswa'));
    }
    public function daftarJadwalHarianProdi(String $id){
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;
        $title = "Jadwal Harian";
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
                    ->get();
        $mk = MataKuliah::where('status', 'Aktif')->whereIn('id',$list_mk)->get();
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
        return view('admin.akademik.jadwal.jadwal_harian', compact('title', 'mk', 'no', 'jadwal','id_prodi','prodi','nama','jumlah_input_krs', 'angkatan','totalMahasiswa'));
    }
    public function reqJadwalHarian(Request $request){
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;
        $hari = $request->hari;
        $matakuliah = $request->matakuliah;

        if($request->id_prodi == 0){

            $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                        ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->get();
            if (($hari != 0) && ($matakuliah == 0)) {
                $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                ->where(['jadwals.hari' => $hari])
                ->get();
            }
            if (($hari == 0) && ($matakuliah != 0)) {
                $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                ->where(['jadwals.id_mk' => $matakuliah])
                ->get();
            }
            if (($hari != 0) && ($matakuliah != 0)) {
                $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                ->where(['jadwals.hari' => $hari, 'jadwals.id_mk' => $matakuliah])
                ->get();
            }
        }else{
            $id = $request->id_prodi;
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

            $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                        ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                        ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                        ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                        ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                        ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                        ->whereIn('id_mk',$list_mk)
                        ->get();
            if (($hari != 0) && ($matakuliah == 0)) {
                $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                ->where(['jadwals.hari' => $hari])
                ->whereIn('id_mk',$list_mk)
                ->get();
            }
            if (($hari == 0) && ($matakuliah != 0)) {
                $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                ->where(['jadwals.id_mk' => $matakuliah])
                ->whereIn('id_mk',$list_mk)
                ->get();
            }
            if (($hari != 0) && ($matakuliah != 0)) {
                $q = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
                ->where(['jadwals.hari' => $hari, 'jadwals.id_mk' => $matakuliah])
                ->whereIn('id_mk',$list_mk)
                ->get();
            }
        }
        $jumlah_input_krs = [];
        foreach($q as $row){
            $jumlah_input_krs[$row->id] = Krs::where('id_jadwal',$row->id)->where('id_tahun',$id_tahun)->count();
        }
        return json_encode(['data' => $q,'jumlah_input' => $jumlah_input_krs]);
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
    public function tambahPertemuan2(Request $request){
        $id_jadwal = $request->id_jadwal;
        $no_pertemuan = $request->no_pertemuan;
        $tgl_pertemuan = $request->tanggal_pertemuan;
        $id_dosen = $request->id_dosen;
        foreach($no_pertemuan as $key=>$value){
            $jml_pertemuan = Pertemuan::where(['id_jadwal'=>$id_jadwal,'no_pertemuan'=>$value])->first();
            $id = 0;
            if(!empty($jml_pertemuan)){
                $id = $jml_pertemuan->id;
            }

            $qr[] = Pertemuan::updateOrCreate(
                ['id' => $id],
                [
                    'no_pertemuan' => $value,
                    'id_jadwal' => $id_jadwal,
                    'tgl_pertemuan' => $tgl_pertemuan[$key],
                    'id_dsn' => $id_dosen[$key]
                ]
            );
        }

        if (!empty($qr)) {
            return json_encode(['kode' => 200,'value'=>$qr]);
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
    public function updateAnggota(Request $request){
        $idmk = $request->idmk;
        $id_pegawai_bio = $request->id_pegawai_bio;
        $status = $request->status;
        $id = $request->id;

        $qr = anggota_mk::updateOrCreate(
            ['id'=>$id],
            ['idmk' => $idmk, 'id_pegawai_bio' => $id_pegawai_bio, 'status' => $status]
        );
        if($qr){
            return json_encode(['status' => 'ok', 'kode' => 200]);
        }else{
            return json_encode(['status' => 'error', 'kode' => 204]);
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
        $pegawai = PegawaiBiodatum::all();
        $idmk = $request->idmk;
        $anggota = anggota_mk::select('anggota_mks.*', 'pegawai_biodata.npp', 'pegawai_biodata.nama_lengkap', 'pegawai_biodata.gelar_belakang')
                        ->leftJoin('pegawai_biodata', 'anggota_mks.id_pegawai_bio', '=', 'pegawai_biodata.id')
                        ->where(['anggota_mks.idmk' => $idmk])->get();
        $no = 1;
        return view('admin.akademik.jadwal.tableAnggota', compact('idmk', 'anggota', 'no','pegawai'));
    }
    public function getPertemuan(Request $request){
        $id = $request->id;
        $jadwal = Jadwal::where('id',$id)->first();
        $anggota = anggota_mk::select('anggota_mks.*', 'pegawai_biodata.npp', 'pegawai_biodata.nama_lengkap', 'pegawai_biodata.gelar_belakang')
                        ->leftJoin('pegawai_biodata', 'anggota_mks.id_pegawai_bio', '=', 'pegawai_biodata.id')
                        ->where(['anggota_mks.idmk' => $jadwal->id_mk])->get();

        $list_pertemuan = [];
        for($i=1; $i<=14; $i++){
            $list_pertemuan[$i]['id_dosen'] = 0;
            $list_pertemuan[$i]['tanggal_pertemuan'] = '0000-00-00';
        }
        $pertemuan = Pertemuan::where('id_jadwal',$id)->get();
        foreach($pertemuan as $row){
            $list_pertemuan[$row->no_pertemuan]['id_dosen'] = $row->id_dsn;
            $list_pertemuan[$row->no_pertemuan]['tanggal_pertemuan'] = $row->tgl_pertemuan;
        }

        // var_dump($list_pertemuan);

        return view('admin.akademik.jadwal.tablePertemuan',compact('id','jadwal','anggota','pertemuan','list_pertemuan'));
    }

    public function settingPertemuan(int $id = 0){
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
                    ->get();
            $list_pertemuan = [];
            $jumlah_pertemuan = [];
            foreach($jadwal as $row){
                $cek_pertemuan = Pertemuan::where('id_jadwal',$row->id)->whereNotNull('tgl_pertemuan')->count();
                if($cek_pertemuan >= 14){
                    $list_pertemuan[$row->id] = 'btn-success';
                }elseif($cek_pertemuan < 14 && $cek_pertemuan > 0){
                    $list_pertemuan[$row->id] = 'btn-warning';
                }else{
                    $list_pertemuan[$row->id] = 'btn-danger';
                }
                $jumlah_pertemuan[$row->id] = $cek_pertemuan;
            }
        }else{
            $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'waktus.nama_sesi', 'ruang.nama_ruang', 'mata_kuliahs.kode_matkul', 'mata_kuliahs.nama_matkul', 'pegawai_biodata.nama_lengkap as nama_dosen')
                    ->leftJoin('pengajars', 'pengajars.id_jadwal', '=', 'jadwals.id')
                    ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'pengajars.id_dsn')
                    ->leftJoin('tahun_ajarans as ta', 'ta.id', '=', 'jadwals.id_tahun')
                    ->leftJoin('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id')
                    ->leftJoin('waktus', 'waktus.id', '=', 'jadwals.id_sesi')
                    ->leftJoin('master_ruang as ruang', 'ruang.id', '=', 'jadwals.id_ruang')
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
                $jumlah_pertemuan[$row->id] = $cek_pertemuan;
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

        return view('admin.akademik.jadwal.pertemuan', compact('title', 'jumlah_pertemuan','list_pertemuan','ta','sesi','days','ruang','mk', 'no', 'jadwal','id_prodi','prodi','nama','jumlah_input_krs', 'angkatan', 'totalMahasiswa'));
    }
}
