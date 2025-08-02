<?php

namespace App\Http\Controllers\admin\krs;

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
use App\Models\LogKr as LogKrs;
use Barryvdh\DomPDF\Facade\Pdf;
use Auth;
use Session;

class KrsController extends Controller
{
    public function index(Request $request)
    {
        $curr_prodi = "";
        if(Auth::user()->hasRole('admin-prodi')){
            $pegawai = PegawaiBiodata::where('user_id',Auth::user()->id)->first();
            $curr_prodi = Prodi::find($pegawai->id_progdi);
        }
        $title = "Master KRS";
        $tahun_ajaran = TahunAjaran::get();
        $prodi = Prodi::get();
        $angkatan = Mahasiswa::select("angkatan")->distinct()->orderBy('angkatan','desc')->get();
        return view('admin.akademik.krs.index', compact('title', 'tahun_ajaran','prodi','curr_prodi','angkatan'));
    }
    public function listMhs(Request $request){
        $ta = TahunAjaran::where('id', $request->ta)->first();
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
        $jumlah_sks = [];
        $jumlah_sks_validasi = [];
        foreach($mhs as $row){
            $krs = Krs::select('a.*','krs.is_publish')->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')->where('id_mhs',$row->id)->where('krs.id_tahun',$ta->id)->get();
            $jumlah_sks[$row->id] = 0;
            $jumlah_sks_validasi[$row->id] = 0;
            foreach($krs as $k){
                $jumlah_sks[$row->id] += $list_sks[$k->id_mk];
                if($k->is_publish == 1){
                    $jumlah_sks_validasi[$row->id] += $list_sks[$k->id_mk];
                }
            }
        }
        $no = 1;
        return view('admin.akademik.krs.vMhs', compact('ta', 'mhs', 'no','jumlah_sks','jumlah_sks_validasi'));
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
    public function inputadminKRS($id, $ta){
        $idmhs = $id;
        $title = 'Input KRS [Admin]';
        $mk = MataKuliah::get();
        $krs = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek', 'c.nama_sesi', 'd.nama_ruang')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('krs.id_mhs',$idmhs)
                    ->get();
        $no = 1;
        return view('admin.akademik.krs.inputkrsadmin', compact('title', 'mk', 'krs', 'no', 'ta', 'idmhs'));
    }
    public function showJadwal(Request $request){
        $id_mk = $request->id_mk;
        $ta = $request->ta;
        $idmhs = $request->idmhs;
        $jadwal = Jadwal::select('jadwals.*', 'ta.kode_ta', 'c.nama_sesi', 'mata_kuliahs.sks_teori', 'mata_kuliahs.sks_praktek','ruang.nama_ruang', 'mata_kuliahs.nama_matkul')
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
                            ->where(['jadwals.hari' => $data_jadwal['hari'], 'jadwals.id_sesi' => $data_jadwal['id_sesi'], 'jadwals.id_tahun' => $data_jadwal['id_tahun'], 'jadwals.status' => 'Aktif','id_mhs'=>$mhs])
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
            $jumlah_kuota = Krs::where(['id_jadwal' => $id, 'id_tahun' => $data_jadwal['id_tahun']])->count();
            if($jumlah_kuota >  $data_jadwal->kuota){
                $tabel = '
                    <div class="alert alert-danger dark" role="alert">
                    <span class="mt-4"><b>maaf kuota matakuliah sudah penuh</b></span>
                    </div>
                ';
                Session::put('krs', $tabel);
                return back();
            }else{
                Krs::create(['id_jadwal' => $id, 'id_tahun' => $data_jadwal['id_tahun'], 'id_mhs' => $mhs, 'is_publish' => 0]);
                $kuota = $data_jadwal['kuota'] - 1;
                Jadwal::where('id', $id)->update(['kuota' => $kuota]);
                Session::put('krs', '<div class="alert alert-success dark mt-4" role="alert">Jadwal Berhasil di Tambahkan</div>');
                //adding log to db jika ada error log cek disini
                LogKrs::create(['id_jadwal' => $id, 'id_mhs'=>$mhs, 'id_ta' => $data_jadwal['id_tahun'], 'action'=>1]);
                return back();
            }

        }
    }
    public function hapusadminKRS($id){
        $qr = Krs::where('id', $id)->first();
        $qr_jadwal = Jadwal::where('id', $qr['id_jadwal'])->first();
        $kuota = $qr_jadwal['kuota'] + 1;
        Jadwal::where('id', $qr['id_jadwal'])->update(['kuota' => $kuota]);

        Krs::where('id', $id)->delete();
        //adding log to db jika ada error log cek disini
        LogKrs::create(['id_jadwal' => $qr['id_jadwal'], 'id_mhs'=>$qr['id_mhs'], 'id_ta' => $qr['id_tahun'], 'action'=>3]);

        return back();
    }
    public function downloadkrs($id){
        $mhs = Mahasiswa::select('mahasiswa.nama', 'mahasiswa.nim', 'pegawai_biodata.nama_lengkap as dsn_wali', 'program_studi.nama_prodi')
                          ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
                          ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
                          ->where('mahasiswa.id', $id)->first();
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $thn_awal = explode('-', $tahun_ajaran->tgl_awal);
        $thn_akhir = explode('-', $tahun_ajaran->tgl_akhir);
        $tahun_ajar = $thn_awal[0].'-'.$thn_akhir[0];
        $semester = ['', 'Ganjil', 'Ganjil', 'Antara'];
        $smt = substr($tahun_ajaran->kode_ta, 4);
        $krs = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek', 'c.nama_sesi', 'd.nama_ruang')
                    ->leftJoin('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where(['krs.id_tahun' => $ta, 'krs.id_mhs' => $id])
                    ->get();
        $filename = $mhs->nim.'-krs.pdf';
        $data = [
            'mhs' => $mhs,
            'krs' => $krs,
            'no' => 1,
            'tahun_ajar' => $tahun_ajar,
            'smt' => $smt,
            'semester' => $semester,
            'logo' => public_path('/assets/images/logo/logo-icon.png')
        ];
        $pdf = PDF::loadView('admin.akademik.krs.template_krs', $data);
        return $pdf->download($filename);
    }
}
