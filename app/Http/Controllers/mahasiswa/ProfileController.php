<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Wilayah;
use App\Models\PegawaiBiodatum;
use App\Models\ModelHasRole;
use App\Models\User;
use App\Models\MahasiswaBerkasPendukung;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\TahunAjaran;

class ProfileController extends Controller
{
    public function index(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $nim = $mhs->nim;
        $title = "Mahasiswa";
        $ta = TahunAjaran::where("status", "Aktif")->first();

        $mahasiswa = Mahasiswa::select(
            'mahasiswa.*', 
            'pegawai_biodata.nama_lengkap as dosenWali', 
            'mahasiswa_berkas_pendukung.kk AS foto_kk',
            'mahasiswa_berkas_pendukung.ktp AS foto_ktp',
            'mahasiswa_berkas_pendukung.akte AS foto_akte',
            'mahasiswa_berkas_pendukung.ijazah_depan AS foto_ijazah_depan',
            'mahasiswa_berkas_pendukung.ijazah_belakang AS foto_ijazah_belakang',
            'mahasiswa_berkas_pendukung.foto_sistem',
        )
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
        ->leftJoin('mahasiswa_berkas_pendukung', function($join) use ($ta) {
                $join->on('mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
                    ->where('mahasiswa_berkas_pendukung.id_ta', '=', $ta->id);
            })
        ->where('mahasiswa.nim', $nim)
        ->first();
        
        $program_studi = Prodi::all();
        $curr_prodi = Prodi::where('id',$mahasiswa->id_program_studi)->first();
        $prodi = [];
        foreach($program_studi as $row){
            $prodi[$row->id] = $row->nama_prodi;
        }
        $agama = array('1'=>'Islam','Kristen','Katolik','Hindu','Budha','Konghuchu','Lainnya');
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();

        $kota = [];
        if($mahasiswa->provinsi != 0 && !empty($mahasiswa->provinsi)){
            $kota = Wilayah::where('id_induk_wilayah', $mahasiswa->provinsi)->get();
        }

        $kecamatan = [];
        if($mahasiswa->kecamatan != 0 && !empty($mahasiswa->kecamatan)){
            $kecamatan = Wilayah::where('id_induk_wilayah', $mahasiswa->kokab)->get();
        }

        $status = array(
          1 => 'aktif',
          2 => 'cuti',
          3 => 'Keluar',
          4 => 'lulus',
          5 => 'meninggal',
          6 => 'DO'
        );
        $dosen = PegawaiBiodatum::where('id_posisi_pegawai',1)->get();
        $dosen_wali = "Tidak ada";
        if(!empty($mahasiswa->id_dsn_wali) && $mahasiswa->id_dsn_wali != 1){
            $dosen_wali = PegawaiBiodatum::where('id',$mahasiswa->id_dsn_wali)->first()->nama_lengkap;
        }

        $user = User::where('id',$mahasiswa->user_id)->first();

        return view('mahasiswa.edit_user', compact('dosen_wali','user','status','dosen','kecamatan','wilayah','kota','title', 'mahasiswa','prodi','agama','curr_prodi'));
    }
    public function heregistrasi(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $nim = $mhs->nim;
        $title = "Mahasiswa";
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $program_studi = Prodi::all();
        $prodi = [];

        foreach($program_studi as $row){
            $prodi[$row->id] = $row->nama_prodi;
        }
        $agama = array('1'=>'Islam','Kristen','Katolik','Hindu','Budha','Konghuchu','Lainnya');
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();

        $kota = [];
        if($mahasiswa->provinsi != 0 && !empty($mahasiswa->provinsi)){
            $kota = Wilayah::where('id_induk_wilayah', $mahasiswa->provinsi)->get();
        }

        $kecamatan = [];
        if($mahasiswa->kecamatan != 0 && !empty($mahasiswa->kecamatan)){
            $kecamatan = Wilayah::where('id_induk_wilayah', $mahasiswa->kokab)->get();
        }

        $status = array(
          1 => 'aktif',
          2 => 'cuti',
          3 => 'Keluar',
          4 => 'lulus',
          5 => 'meninggal',
          6 => 'DO'
        );
        $dosen = PegawaiBiodatum::where('id_posisi_pegawai',1)->get();
        $user = User::where('id',$mahasiswa->user_id)->first();
        $berkas = MahasiswaBerkasPendukung::where('nim',$mahasiswa->nim)->first() ?? '';

        return view('mahasiswa.edit_berkas', compact('user','status','dosen','kecamatan','wilayah','kota','title', 'mahasiswa','prodi','agama','berkas'));
    }
}
