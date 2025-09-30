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
use App\Models\Pertemuan;
use App\Models\Jadwal;
use App\Models\AbsensiModel;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    //
    public function index(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $idmhs = $mhs->id;
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $prodi = Prodi::find($mhs->id_program_studi);

        $title = 'Absensi Mahasiswa';
        $kd_prodi_mhs = Prodi::where('id',$mhs->id_program_studi)->first()->kode_prodi;
        $kurikulum = Kurikulum::where('progdi',$kd_prodi_mhs)->where('angkatan','<=',$mhs->angkatan)->where('angkatan_akhir','>=',$mhs->angkatan)->where('thn_ajar',$ta)->get();
        $mk = [];
        if($kurikulum){
            foreach($kurikulum as $row){
                $mk[] = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
            }
        }
        //$mk = MataKuliah::get();
        $krs = Krs::select('krs.*', 'a.hari','a.kode_jadwal','a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul', 'c.nama_sesi', 'd.nama_ruang')
                    ->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
                    ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('id_mhs',$idmhs)
                    ->where('is_publish',1)
                    ->get();
        $pertemuan = [];
        foreach($krs as $row){
            $pertemuan[$row->id_jadwal] = [];
            $list_pertemuan = Pertemuan::where(['id_jadwal'=>$row->id_jadwal,'buka_kehadiran'=>1])->where('tgl_pertemuan','like','%' . date('Y-m-d') .'%')->get();
            foreach($list_pertemuan as $list){
                $pertemuan[$row->id_jadwal][$list->id] = $list->no_pertemuan;
            }
        }
        $no = 1;

        $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        return view('mahasiswa.absensi.index', compact('prodi','pertemuan','mhs','title', 'permission','mk', 'krs', 'no', 'ta', 'idmhs'));
    }
    public function setAbsensiSatuan($id_jadwal){
        $title = "Absensi";
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $nim = $mhs->nim;
        $pertemuan = Pertemuan::select('pertemuans.*', 'pegawai_biodata.nama_lengkap')
                              ->join('pegawai_biodata', 'pegawai_biodata.id', '=', 'pertemuans.id_dsn')
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
        $tanggal_absen = [];
        $keterangan = [];
        $type_mhs = [];
        foreach($pertemuan as $row){
            $model_absen = AbsensiModel::where('id_jadwal',$id_jadwal)->where("id_pertemuan",$row->id)->where("id_mhs",$mhs->id)->first();
            $tanggal_absen[$row->id] = $model_absen->tanggal_absen ?? '';
            $keterangan[$row->id] = '';
            $type_mhs[$row->id] = 0;

            if(!empty($model_absen->tanggal_absen)){
                    if($model_absen->tanggal_absen > $row->tgl_expired){
                        $keterangan[$row->id] = "<div class='btn btn-danger btn-sm'>Terlambat</div>";
                    }else{
                        $keterangan[$row->id] = "<div class='btn btn-success btn-sm'>Tepat Waktu</div>";
                    }
            }
            $type_mhs[$row->id] = $model_absen->type ?? '';
        }
        $status_kehadiran = ['Tidak Hadir','Hadir','Sakit','Izin'];
        $no = 1;
        return view('mahasiswa.absensi.detail_absensi', compact('title','status_kehadiran','type_mhs','tanggal_absen','keterangan','pertemuan', 'jadwal', 'mhs', 'no', 'prodi'));
    }
    public function saveAbsensi($id_jadwal){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $tanggal = date('Y-m-d');
        $pertemuan = Pertemuan::select('pertemuans.*', 'pegawai_biodata.nama_lengkap')
                              ->join('pegawai_biodata', 'pegawai_biodata.id', '=', 'pertemuans.id_dsn')
                              ->where('pertemuans.id_jadwal', $id_jadwal)->where('tgl_pertemuan',$tanggal)->where('buka_kehadiran',1)->first();
        if($pertemuan){
            $absensi_model = AbsensiModel::where('id_jadwal',$id_jadwal)->where('id_pertemuan',$pertemuan->id)->where('id_mhs',$mhs->id);
            if($absensi_model->count() > 0){
                // echo "update data";
                $absensi_model->update(['type'=>1,'tanggal_absen'=>date('Y-m-d H:i:s')]);
            }else{
                // echo "bikin baru";
                $absensi_model_new = new AbsensiModel;
                $absensi_model_new->id_jadwal = $id_jadwal;
                $absensi_model_new->id_pertemuan = $pertemuan->id;
                $absensi_model_new->id_mhs = $mhs->id;
                $absensi_model_new->type = 1;
                $absensi_model_new->input_by = $mhs->id;
                $absensi_model_new->tanggal_absen = date('Y-m-d H:i:s');
                $absensi_model_new->save();
            }
        }
        return redirect('/mhs/absensi/history/' . $id_jadwal);
    }
}
