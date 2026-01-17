<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\JabatanFungsional;
use App\Models\JabatanStruktural;
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
            $homebase[$row->id] = $row->nama_prodi;
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
        $jabatan_fungsional = JabatanFungsional::all();
        $jabatan_struktural = JabatanStruktural::all();
        $progdi = Prodi::all();
        $jenis_pegawai = PegawaiJenis::all();
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $status = array('aktif','izin belajar','cuti','keluar','meninggal');
        $status_kawin = array("Lajang","Kawin");
        return view("admin/kepegawaian/pegawai/create2", compact('title','jenis_kelamin','progdi','jabatan_fungsional', 'jenis_pegawai', 'jabatan_struktural','wilayah','status','status_kawin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
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
                    'id_jabfung' => $request->jabatan_fungsional,
                    'id_jabstruk' => $request->jabatan_struktural,
                    'ktp' => $request->no_ktp,
                    'npp' => $request->npp,
                    'nidn' => $request->nidn,
                    'nuptk' => $request->nuptk,
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
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'user_id'=>$user_id,
                ]
            );
            return response()->json('created');
        }else{
            // Check if no_absensi is filled and validate for duplicates
            if (!empty($request->no_absensi) && $request->no_absensi != 0) {
                $existing = PegawaiBiodatum::where('no_absensi', $request->no_absensi)
                    ->where('id', '!=', $id)
                    ->first();
                
                if ($existing) {
                    return response()->json(['error' => 'Nomor Absensi sudah digunakan'], 422);
                }
            }
            $pegawai = PegawaiBiodatum::updateOrCreate(
                ['id' => $id],
                [
                    'id_posisi_pegawai' => $request->posisi_pegawai,
                    'id_progdi' => $request->homebase,
                    'id_jabfung' => $request->jabatan_fungsional,
                    'id_jabstruk' => $request->jabatan_struktural,
                    'ktp' => $request->no_ktp,
                    'npp' => $request->npp,
                    'nuptk' => $request->nuptk,
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
                    'tgl_lahir_pasangan' => $request->tgl_lahir_pasangan,
                    'jumlah_anak' => $request->jumlah_anak,
                    'pekerjaan_pasangan' => $request->pekerjaan_pasangan,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'no_absensi'=>$request->no_absensi,
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
        $jabatan_fungsional = JabatanFungsional::all();
        $jabatan_struktural = JabatanStruktural::all();
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $status = array('aktif','izin belajar','cuti','keluar','meninggal');
        $status_kawin = array("Lajang","Kawin");
        $posisi = [];
        $pos = PegawaiPosisi::all();
        $curr_jenis_pegawai = PegawaiPosisi::where('id',$pegawai->id_posisi_pegawai)->first();
        $curr_jabatan_fungsional = JabatanFungsional::where('id', $pegawai->id_jabfung)->first();
        $curr_jabatan_struktural = JabatanStruktural::where('id', $pegawai->id_jabstruk)->first();
        $list_jenis = PegawaiPosisi::where('id_jenis_pegawai',$curr_jenis_pegawai->id_jenis_pegawai ?? 2)->get();
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
        return view("admin/kepegawaian/pegawai/edit", compact('kota','kecamatan','title','pegawai','posisi', 'jabatan_fungsional', 'curr_jabatan_struktural', 'jabatan_struktural', 'curr_jabatan_fungsional','jenis_pegawai','curr_jenis_pegawai','list_jenis','wilayah','status','status_kawin','progdi','jenis_kelamin','id','user'));

    }
    public function user_update(Request $request){
        $id = $request->id;
        $id_pegawai = $request->id_pegawai;

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
            $pegawai = PegawaiBiodatum::find($id_pegawai);
            $pegawai->user_id = $user_id;
            $pegawai->email1 = $request->email;
            $pegawai->save();
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
            $data = [
                'status' => 'updated',
                'pegawai' => PegawaiBiodatum::find($id),
            ];
            return response()->json($data);
        }else{
            return response()->json('Failed');
        }
    }
    public function generate_user(){
        $pegawai = PegawaiBiodatum::all();
        foreach($pegawai as $row){
            echo $row->nama_lengkap;
            echo $row->npp;
            echo "<br />";
            $email = $row->npp . "@dsn.stifar.id";
            $password = $row->npp . "stifar";
            //cek user
            $user = User::where('email',$email);
            if($user->count() > 0){
                // $user = $user->row();
                // $new_user = User::find($user->id);
                // $new_user->password = Hash::make($password);
                // $new_user->save();
                //donothing
            }else{
                $new_user = User::create(
                    [
                        'name'=>$row->nama_lengkap,
                        'email' => $email,
                        'password' => Hash::make($password),
                    ]
                );
                $user_id = $new_user->id;
                $role = ModelHasRole::create(
                    [
                        'role_id' => 3,
                        'model_type' => 'App\Models\User',
                        'model_id' => $user_id,
                    ]
                );
                $pegawai2 = PegawaiBiodatum::find($row->id);
                $pegawai2->user_id = $user_id;
                $pegawai2->save();
            }
        }
    }
}
