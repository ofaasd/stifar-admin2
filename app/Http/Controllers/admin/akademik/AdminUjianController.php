<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\PegawaiBiodatum as PegawaiBiodata;
use App\Models\Krs;
use App\Models\Prodi;
use App\Models\Kurikulum;
use App\Models\Jadwal;
use App\Models\MatakuliahKurikulum;
use App\Models\MasterKeuanganMh;
use App\Models\master_nilai;
use App\Models\MasterRuang;
use App\Models\LogKr as LogKrs;
use Barryvdh\DomPDF\Facade\Pdf;
use Auth;
use Session;

class AdminUjianController extends Controller
{
    //
    public function index(Request $request)
    {
        $curr_prodi = "";
        if(Auth::user()->hasRole('admin-prodi')){
            $pegawai = PegawaiBiodata::where('user_id',Auth::user()->id)->first();
            $curr_prodi = Prodi::find($pegawai->id_progdi);
        }
        $title = "List Mahasiswa";
        $tahun_ajaran = TahunAjaran::get();
        $prodi = Prodi::get();
        $angkatan = Mahasiswa::select("angkatan")->distinct()->orderBy('angkatan','desc')->get();
        return view('admin.akademik.ujian.mahasiswa', compact('title', 'tahun_ajaran','prodi','curr_prodi','angkatan'));
    }
    public function list_mhs(Request $request){
        $ta = TahunAjaran::where('status','Aktif')->first();
        $mhs = Mahasiswa::select('mahasiswa.*','pegawai_biodata.nama_lengkap as nama_dosen')->leftJoin('pegawai_biodata','pegawai_biodata.id','=','mahasiswa.id_dsn_wali')->where('id_program_studi',$request->prodi)->where('status',1)->where('angkatan',$request->angkatan)->get();

        $prodi = Prodi::find($request->prodi);
        $get_kurikulum = Kurikulum::where('progdi',$prodi->kode_prodi)->get();
        foreach($get_kurikulum as $kuri){
            $matakuliah = MatakuliahKurikulum::select('mata_kuliahs.*')
                ->leftJoin('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')
                ->where('id_kurikulum',$kuri->id)
                ->get();
            foreach($matakuliah as $mata){
                $list_sks[$mata->id] = $mata->sks_teori + $mata->sks_praktek;
            }
        }
        $ijinkan_uts = [];
        $ijinkan_uas = [];
        foreach($mhs as $row){
            $keuangan = MasterKeuanganMh::where('id_mahasiswa',$row->id)->where('id_tahun_ajaran',$ta->id)->first();
            $ijinkan_uts[$row->id] = $keuangan->uts ?? 0;
            $ijinkan_uas[$row->id] = $keuangan->uas ?? 0;
        }       
        $no = 1;
        return view('admin.akademik.ujian.vMhs', compact('ta', 'mhs', 'no','ijinkan_uts','ijinkan_uas'));
    }
    public function show(String $nim){
        $mhs = Mahasiswa::where('nim',$nim)->first();
        $idmhs = $mhs->id;
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;

        $title = 'Ujian UTS / UAS';
        $kd_prodi_mhs = Prodi::where('id',$mhs->id_program_studi)->first()->kode_prodi;
        $kurikulum = Kurikulum::where('progdi',$kd_prodi_mhs)->where('angkatan','<=',$mhs->angkatan)->where('angkatan_akhir','>=',$mhs->angkatan)->get();
        $mk = [];
        if($kurikulum){
            foreach($kurikulum as $row){
                $mk[] = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
            }
        }
        $list_ruang = [];
        $list_ruang[0] = '-';
        $ruang = MasterRuang::all();
        foreach($ruang as $row){
            $list_ruang[$row->id] = $row->nama_ruang;
        }
        //$mk = MataKuliah::get();
        $krs = Krs::select('krs.*', 'a.hari', 'a.kel', 'a.kode_jadwal', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul', 'c.nama_sesi', 'd.nama_ruang','tbl_jadwal_ujian.tanggal_uts', 'tbl_jadwal_ujian.jam_mulai_uts','tbl_jadwal_ujian.jam_selesai_uts','tbl_jadwal_ujian.id_ruang_uts','tbl_jadwal_ujian.tanggal_uas','tbl_jadwal_ujian.jam_mulai_uas','tbl_jadwal_ujian.jam_selesai_uas','tbl_jadwal_ujian.id_ruang_uas')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->leftJoin('tbl_jadwal_ujian', 'tbl_jadwal_ujian.id_jadwal', '=', 'a.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('id_mhs',$idmhs)
                    ->where('is_publish',1)
                    ->get();
        $no = 1;
        $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->where('id_tahun_ajaran',$ta)->first();
        return view('mahasiswa.ujian.index', compact('mhs','title', 'permission','mk', 'krs', 'no', 'ta', 'idmhs','list_ruang'));
    }
    public function cetak_uts(String $nim){
        $mhs = Mahasiswa::select('mahasiswa.nama','mahasiswa.foto_mhs', 'mahasiswa.nim', 'pegawai_biodata.nama_lengkap as dsn_wali', 'program_studi.nama_prodi')
                          ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
                          ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
                          ->where('mahasiswa.nim', $nim)->first();
        $id = $mhs->id;
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $thn_awal = substr($tahun_ajaran->kode_ta,0,4);
        $thn_akhir = explode('-', $tahun_ajaran->tgl_akhir);
        $tahun_ajar = $thn_awal.'/'.$thn_akhir[0];
        $semester = ['', 'Ganjil', 'Genap', 'Antara'];
        $smt = substr($tahun_ajaran->kode_ta, 4);
        $krs = Krs::select('krs.*', 'a.hari','a.kode_jadwal', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek', 'c.nama_sesi', 'd.nama_ruang')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where(['krs.id_tahun' => $ta, 'krs.id_mhs' => $id])
                    ->get();
        $filename = $mhs->nim.'-krs.pdf';
        $cek_foto = (!empty($mhs->foto_mhs))?'assets/images/mahasiswa/' . $mhs->foto_mhs:'assets/images/logo/logo-icon.png';
        $data = [
            'mhs' => $mhs,
            'krs' => $krs,
            'no' => 1,
            'tahun_ajar' => $tahun_ajar,
            'smt' => $smt,
            'semester' => $semester,
            'logo' => public_path('/assets/images/logo/logo-icon.png'),
            'foto' => public_path('/' . $cek_foto)
        ];

    	$pdf = PDF::loadview('mahasiswa/ujian/cetak_uts',$data);
    	return $pdf->download('Kartu-uts-' . $mhs->nim . '.pdf');
    }
    public function cetak_uas(String $nim){
        $mhs = Mahasiswa::select('mahasiswa.nama','mahasiswa.foto_mhs', 'mahasiswa.nim', 'pegawai_biodata.nama_lengkap as dsn_wali', 'program_studi.nama_prodi')
                          ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
                          ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
                          ->where('mahasiswa.nim', $nim)->first();
        $id = $mhs->id;
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $thn_awal = substr($tahun_ajaran->kode_ta,0,4);
        $thn_akhir = explode('-', $tahun_ajaran->tgl_akhir);
        $tahun_ajar = $thn_awal.'/'.$thn_akhir[0];
        $semester = ['', 'Ganjil', 'Genap', 'Antara'];
        $smt = substr($tahun_ajaran->kode_ta, 4);
        $krs = Krs::select('krs.*', 'a.hari','a.kode_jadwal', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek', 'c.nama_sesi', 'd.nama_ruang')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where(['krs.id_tahun' => $ta, 'krs.id_mhs' => $id])
                    ->get();
        $filename = $mhs->nim.'-krs.pdf';
        $cek_foto = (!empty($mhs->foto_mhs))?'assets/images/mahasiswa/' . $mhs->foto_mhs:'assets/images/logo/logo-icon.png';
        $data = [
            'mhs' => $mhs,
            'krs' => $krs,
            'no' => 1,
            'tahun_ajar' => $tahun_ajar,
            'smt' => $smt,
            'semester' => $semester,
            'logo' => public_path('/assets/images/logo/logo-icon.png'),
            'foto' => public_path('/' . $cek_foto)
        ];

    	$pdf = PDF::loadview('mahasiswa/ujian/cetak_uas',$data);
    	return $pdf->download('Kartu-uas-' . $mhs->nim . '.pdf');
    }
}
