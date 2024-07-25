<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Wilayah;
use App\Models\PegawaiBiodatum;
use App\Models\ModelHasRole;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MahasiswaController extends Controller
{
  public function index(Request $request)
  {
      $title = "Daftar Mahasiswa";
      $mhs = Mahasiswa::get();
      $no = 1;
      return view('mahasiswa.daftar', compact('title', 'mhs', 'no'));
  }
  public function edit($nim){
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
        $kecamatan = Wilayah::where('id_induk_wilayah', $mahasiswa->kotakab)->get();
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

    return view('mahasiswa.edit', compact('status','dosen','kecamatan','wilayah','kota','title', 'mahasiswa','prodi','agama'));
  }
  public function detail($nim){
    $title = "Detail Mahasiswa";
    $detail = Mahasiswa::where('nim', $nim)->get();

    return view('mahasiswa.detail', compact('title', 'detail'));
  }
  public function store(Request $request){
    $id = $request->id;
    if(empty($id)){
      //create user
      $user = User::create(
        [
            'name'=>$request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]
      );
      $user_id = $user->id;
      $role = ModelHasRole::create(
        [
            'role_id' => 4,
            'model_type' => 'App\Models\User',
            'model_id' => $user_id,
        ]
      );
      $mahasiswa = Mahasiswa::updateOrCreate(
        ['id' => $id],
        [
            'id_posisi_pegawai' => $request->posisi_pegawai,
            'id_progdi' => $request->homebase,
            'ktp' => $request->no_ktp,
            'npp' => $request->npp,
            'nidn' => $request->nidn,
            'nama_lengkap' => $request->nama_lengkap,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email1' => $request->email,
            'no_kk' => $request->no_kk,
            'no_bpjs_kesehatan' => $request->no_bpjs_kesehatan,
            'no_bpjs_ketenagakerjaan' => $request->no_bpjs_ketenagakerjaan,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kotakab' => $request->kotakab,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'golongan_darah' => $request->golongan_darah,
            'status_pegawai' => $request->status,
            'status_nikah' => $request->status_nikah,
            'nama_pasangan' => $request->nama_pasangan,
            'jumlah_anak' => $request->junmlah_anak,
            'pekerjaan_pasangan' => $request->pekerjaan_pasangan,
            'user_id'=>$user_id,
        ]
    );
    }else{
      //update user

    }
  }
}
