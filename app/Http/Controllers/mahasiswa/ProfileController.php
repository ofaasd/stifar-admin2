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
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(){
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $nim = $mhs->nim;
        $title = "Mahasiswa";
        $mahasiswa = Mahasiswa::select('mahasiswa.*', 'pegawai_biodata.nama_lengkap as dosenWali', 'mahasiswa_berkas_pendukung.*')
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
        ->leftJoin('mahasiswa_berkas_pendukung', 'mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
        ->where('mahasiswa.nim', $nim)
        ->first();
        
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

        return view('mahasiswa.edit_user', compact('user','status','dosen','kecamatan','wilayah','kota','title', 'mahasiswa','prodi','agama'));
      }
}
