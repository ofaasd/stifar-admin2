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
    $user = User::where('id',$mahasiswa->user_id)->first();

    return view('mahasiswa.edit', compact('user','status','dosen','kecamatan','wilayah','kota','title', 'mahasiswa','prodi','agama'));
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
                'id_program_studi' => $request->id_program_studi,
                'nim' => $request->nim,
                'nama' => $request->nama,
                'no_ktp' => $request->no_ktp,
                'jk' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'nama_ibu' => $request->nama_ibu,
                'nama_ayah' => $request->nama_ayah,
                'hp_ortu' => $request->hp_ortu,
                'angkatan' => $request->angkatan,
                'alamat' => $request->alamat,
                'alamat_semarang' => $request->alamat_semarang,
                'provinsi' => $request->provinsi,
                'kotakab' => $request->kotakab,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'email' => $request->email,
                'hp' => $request->hp,
                'id_dsn_wali' => $request->id_dsn_wali,
                'user_id'=>$user_id,
            ]
        );
        return response()->json('created');
    }else{
      //update user
        $mahasiswa = Mahasiswa::updateOrCreate(
            ['id' => $id],
            [
                'id_program_studi' => $request->id_program_studi,
                'nim' => $request->nim,
                'nama' => $request->nama,
                'no_ktp' => $request->no_ktp,
                'jk' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'nama_ibu' => $request->nama_ibu,
                'nama_ayah' => $request->nama_ayah,
                'hp_ortu' => $request->hp_ortu,
                'angkatan' => $request->angkatan,
                'alamat' => $request->alamat,
                'alamat_semarang' => $request->alamat_semarang,
                'provinsi' => $request->provinsi,
                'kotakab' => $request->kotakab,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'email' => $request->email,
                'hp' => $request->hp,
                'id_dsn_wali' => $request->id_dsn_wali,
            ]
        );
        return response()->json('updated');
    }
  }
  public function user_update(Request $request){
        $id = $request->id;
        $id_mahasiswa = $request->id_mahasiswa;

        if($id){
            $user = User::updateOrCreate(
                ['id' => $id,],
                [
                    'password' => Hash::make($request->password),
                ]
            );
            return response()->json('Password updated');
        }else{
            $user = User::updateOrCreate(
                [
                    'id' => $id,
                ],
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
            $pegawai = Mahasiswa::find($id_mahasiswa);
            $pegawai->user_id = $user_id;
            $pegawai->email = $request->email;
            $pegawai->save();
            return response()->json('User Created');
        }
    }
    public function foto_update(Request $request){
        $id = $request->id;
        if ($request->file('foto')) {

            $photo = $request->file('foto');
            $filename = date('YmdHi') . $photo->getClientOriginalName();
            $tujuan_upload = 'assets/images/mahasiswa';
            $photo->move($tujuan_upload,$filename);
            $mahasiswa = Mahasiswa::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                    'foto_mhs' => $filename,
                ]
            );
            $data = [
                'status' => 'updated',
                'pegawai' => Mahasiswa::find($id),
            ];
            return response()->json($data);
        }else{
            return response()->json('Failed');
        }
    }
}
