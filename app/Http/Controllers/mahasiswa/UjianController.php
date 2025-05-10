<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\TahunAjaran;
use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\MatakuliahKurikulum;
use App\Models\MasterKeuanganMh;
use App\Models\MasterRuang;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use PDF;

class UjianController extends Controller
{
    //
    public function index(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
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
        $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        return view('mahasiswa.ujian.index', compact('mhs','title', 'permission','mk', 'krs', 'no', 'ta', 'idmhs','list_ruang'));
    }
    public function cetak_uts(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $id = $mhs->id;
        $mhs = Mahasiswa::select('mahasiswa.nama','mahasiswa.foto_mhs', 'mahasiswa.nim', 'pegawai_biodata.nama_lengkap as dsn_wali', 'program_studi.nama_prodi')
                          ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
                          ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
                          ->where('mahasiswa.id', $id)->first();
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
    public function cetak_uas(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $id = $mhs->id;
        $mhs = Mahasiswa::select('mahasiswa.nama','mahasiswa.foto_mhs', 'mahasiswa.nim', 'pegawai_biodata.nama_lengkap as dsn_wali', 'program_studi.nama_prodi')
                          ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
                          ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
                          ->where('mahasiswa.id', $id)->first();
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
