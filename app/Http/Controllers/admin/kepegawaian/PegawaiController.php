<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\PegawaiBiodatum;
use App\Models\PegawaiGolongan;
use App\Models\PegawaiJeni as PegawaiJenis;
use App\Models\PegawaiJabatanStruktural;
use App\Models\PegawaiJabatanFungsional;
use App\Models\PegawaiPosisi;
use App\Models\Wilayah;
use App\Models\User;
use App\Models\ModelHasRole;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use App\Models\Prodi;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $pegawai_biodata = PegawaiBiodatum::all();
        foreach($pegawai_biodata as $row){
            $pegawai = Pegawai::where('nama',$row->nama_lengkap)->first();
            if($pegawai){
                $new_pegawai = PegawaiBiodatum::find($row->id);
                $new_pegawai->id_pegawai = $pegawai->id;
                $new_pegawai->save();
            }
        }
        $title = "Data Pegawai";
        $pegawai = PegawaiBiodatum::all();
        $programStudi = Prodi::all();
        $homebase = [];
        $homebase[0] = "Tidak Ada";
        foreach($programStudi as $row){
            $homebase[$row->id] = $row->nama_jurusan;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/index', compact('title','pegawai','homebase','fake_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = "Tambah Pegawai";
        $jenis_kelamin = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ];
        $progdi = Prodi::all();
        $jenis_pegawai = PegawaiJenis::all();
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $status = array('aktif','cuti','keluar','meninggal');
        $status_kawin = array("Lajang","Kawin");
        return view("admin/kepegawaian/pegawai/create2", compact('title','jenis_kelamin','progdi','jenis_pegawai','wilayah','status','status_kawin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $id = $request->id;
        if(empty($id)){
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
                    'role_id' => 3,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user_id,
                ]
            );

            $pegawai = PegawaiBiodatum::updateOrCreate(
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
            return response()->json('created');
        }else{
            $pegawai = PegawaiBiodatum::updateOrCreate(
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
                ]
            );
            return response()->json('updated');
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        // /$pegawai = Pegawai::where()
        $title = "Data Pegawai";
        $pegawai = PegawaiBiodatum::find($id);
        $jenis_kelamin = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ];
        $user = User::where('id',$pegawai->user_id)->first();
        $progdi = Prodi::all();
        $jenis_pegawai = PegawaiJenis::all();
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $status = array('aktif','cuti','keluar','meninggal');
        $status_kawin = array("Lajang","Kawin");
        $posisi = [];
        $pos = PegawaiPosisi::all();
        $curr_jenis_pegawai = PegawaiPosisi::where('id',$pegawai->id_posisi_pegawai)->first();
        $list_jenis = PegawaiPosisi::where('id_jenis_pegawai',$curr_jenis_pegawai->id_jenis_pegawai)->get();
        foreach($pos as $row){
            $posisi[$row->id] = $row->nama;
        }
        $kota = [];
        if($pegawai->provinsi != 0 && !empty($pegawai->provinsi)){
            $kota = Wilayah::where('id_induk_wilayah', $pegawai->provinsi)->get();
        }

        $kecamatan = [];
        if($pegawai->kecamatan != 0 && !empty($pegawai->kecamatan)){
            $kecamatan = Wilayah::where('id_induk_wilayah', $pegawai->kotakab)->get();
        }
        return view("admin/kepegawaian/pegawai/edit", compact('kota','kecamatan','title','pegawai','posisi','jenis_pegawai','curr_jenis_pegawai','list_jenis','wilayah','status','status_kawin','progdi','jenis_kelamin','id','user'));

    }
    public function user_update(Request $request){
        $id = $request->id;
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
                    'role_id' => 3,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user_id,
                ]
            );
            return response()->json('User Created');
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $Pegawai = PegawaiBiodatum::where('id', $id)->delete();
    }

    public function get_status(Request $request){
        $id = $request->id;
        $status = PegawaiPosisi::where('id_jenis_pegawai',$id)->get();
        return response()->json($status);
    }
    public function foto_update(Request $request){
        $id = $request->id;
        if ($request->file('foto')) {

            $photo = $request->file('foto');
            $filename = date('YmdHi') . $photo->getClientOriginalName();
            $tujuan_upload = 'assets/images/pegawai';
            $photo->move($tujuan_upload,$filename);
            $pegawai_biodata = PegawaiBiodatum::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                    'foto' => $filename,
                ]
            );
            return response()->json('updated');
        }else{
            return response()->json('Failed');
        }
    }
}
