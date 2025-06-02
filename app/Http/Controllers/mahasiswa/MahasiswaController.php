<?php

namespace App\Http\Controllers\mahasiswa;

use stdClass;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Wilayah;
use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use App\Models\ModelHasRole;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use App\Models\DetailTagihan;
use App\Models\PegawaiBiodatum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use App\Models\MahasiswaBerkasPendukung;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Drivers\Gd\Driver;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Barryvdh\DomPDF\Facade\Pdf;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $title = "Daftar Mahasiswa";
        $mhs = Mahasiswa::get();
        $no = 1;
        $prodi = Prodi::all();
        $jumlah = [];
        $nama = [];

        foreach($prodi as $row){
            $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        return view('mahasiswa.daftar', compact('title', 'mhs', 'no', 'prodi','jumlah','nama'));
    }

    public function get_mhs(Request $request)
    {
        $id = $request->id;
        if($id == 0){
            $ta = TahunAjaran::where("status", "Aktif")->first();
            $mhs = Mahasiswa::select(
                'mahasiswa.*', 
                'pegawai_biodata.nama_lengkap as dosenWali', 
                'mahasiswa_berkas_pendukung.foto_sistem'
            )
            ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
            ->leftJoin('mahasiswa_berkas_pendukung', function($join) use ($ta) {
                $join->on('mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
                    ->where('mahasiswa_berkas_pendukung.id_ta', '=', $ta->id);
            })
            ->get()
            ->map(function ($item) {
                $item->nimEnkripsi = Crypt::encryptString($item->nim . "stifar");
                return $item;
            });

            $no = 1;
            $prodi = Prodi::all();
            $jumlah = [];
            $nama = [];

            foreach($prodi as $row){
                $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
                $nama_prodi = explode(' ',$row->nama_prodi);
                $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
            }
        return view('mahasiswa._table_mhs', compact('mhs', 'no', 'prodi','jumlah','nama'));
        }else{
        $mhs = Mahasiswa::where('id_program_studi',$id)->get();
        $no = 1;
        $prodi = Prodi::all();
        $jumlah = [];
        $nama = [];

        foreach($prodi as $row){
            $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        return view('mahasiswa._table_mhs', compact('mhs', 'no', 'prodi','jumlah','nama'));
        }
    }

    public function edit($nim){
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


        if(empty($mahasiswa)){
            return view('errors.404');
        }

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

        $tagihan = DetailTagihan::where('nim', $nim)->first();
        $statusTagihan = (!empty($tagihan) && $tagihan->status == 1) ? true : false;

        $sksTempuh = 0;
        $ipk = 0;
        $sksAktif = 0;

        $status = array(
        1 => 'aktif',
        2 => 'cuti',
        3 => 'Keluar',
        4 => 'lulus',
        5 => 'meninggal',
        6 => 'DO'
        );

        $tagihan = DetailTagihan::where('nim', $nim)->first();
        $statusTagihan = (!empty($tagihan) && $tagihan->status == 1) ? true : false;

        $sksTempuh = 0;
        $ipk = 0;
        $sksAktif = 0;


        // $dosen = PegawaiBiodatum::where('id_posisi_pegawai', 1)->get();
        $user = User::where('id', $mahasiswa->user_id)->first();

        $dosen = PegawaiBiodatum::where('id_posisi_pegawai',1)->get();

        return view('mahasiswa.edit', compact
        (
            'user',
            'status',
            'kecamatan',
            'wilayah',
            'kota',
            'title',
            'mahasiswa',
            'prodi',
            'agama',
            'statusTagihan',
            'sksTempuh',
            'ipk',
            'sksAktif',
            'dosen',
        ));
    }

    public function create(){
        $title = "Mahasiswa";
        $nim = 'asdasd';
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $program_studi = Prodi::all();
        $prodi = [];
        foreach($program_studi as $row){
            $prodi[$row->id] = $row->nama_prodi;
        }
        $agama = array('1'=>'Islam','Kristen','Katolik','Hindu','Budha','Konghuchu','Lainnya');
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();

        $kota = [];
        if(!empty($mahasiswa->provinsi) && $mahasiswa->provinsi != 0){
            $kota = Wilayah::where('id_induk_wilayah', $mahasiswa->provinsi)->get();
        }

        $kecamatan = [];
        if(!empty($mahasiswa->kecamatan) && $mahasiswa->kecamatan != 0 ){
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
        $user = '';

        return view('mahasiswa.create', compact('user','status','dosen','kecamatan','wilayah','kota','title', 'mahasiswa','prodi','agama'));
    }

    public function detail($nim){
        $title = "Detail Mahasiswa";
        $detail = Mahasiswa::where('nim', $nim)->get();
        $status = array(
            1 => 'aktif',
            2 => 'cuti',
            3 => 'Keluar',
            4 => 'lulus',
            5 => 'meninggal',
            6 => 'DO'
        );
        return view('mahasiswa.detail', compact('title', 'detail', 'status'));
    }

    public function store(Request $request){
        $id = $request->id;
        if(empty($id)){
        //create user
        $email = $request->nim . "@mhs.stifar.id";
        $password = $request->nim.'stifar';
        $user = User::create(
            [
                'name'=>$request->nama,
                'email' => $email,
                'password' => Hash::make($password),
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
                    'kokab' => $request->kotakab,
                    'kecamatan' => $request->kecamatan,
                    'kelurahan' => $request->kelurahan,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'email' => $request->email,
                    'hp' => $request->hp,
                    'id_dsn_wali' => $request->id_dsn_wali,
                    'user_id'=>$user_id,
                    'status' => 1,
                ]
            );
            $data = [
                'status' => 200,
                'id' => $mahasiswa->nim,
                'user_id' => $user_id,
            ];
            return response()->json($data);
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
                    'kokab' => $request->kotakab,
                    'kecamatan' => $request->kecamatan,
                    'kelurahan' => $request->kelurahan,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'email' => $request->email,
                    'hp' => $request->hp,
                    'id_dsn_wali' => $request->id_dsn_wali,
                    'status' => $request->status,
                ]
            );
            return response()->json('updated');
        }
    }

    public function user_update(Request $request)
    {
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

    public function user_update2(Request $request)
    {
        $id = $request->id;
        $id_mahasiswa = Mahasiswa::where('user_id',$id)->first()->id;
        $user = User::find($id);
        $cek = Hash::check($request->password_lama, $user->password);
        $password_baru = $request->password_baru;
        $confirm_password = $request->password_baru_confirm;
        if($cek){
            if($password_baru != $confirm_password){
                //password konfirmasi tidak sama
                return response()->json('Failed', 500);
            }else{
                $user = User::updateOrCreate(
                    ['id' => $id,],
                    [
                        'password' => Hash::make($request->password_baru),
                    ]
                );
                $mahasiswa = Mahasiswa::find($id_mahasiswa);
                $mahasiswa->update_password = 1;
                $mahasiswa->save();
                return response()->json('Password updated');
            }
        }else{
            $returnData = array(
                'status' => 'error',
                'message' => 'Password lama salah'
            );
            //password tidak sama dengan yang ada di db
            return response()->json($returnData, 500);
        }

    }

    public function foto_update(Request $request)
    {
        $id = $request->id;
        if ($request->file('foto')) {
            
            $photo = $request->file('foto');
            if(strtolower($photo->extension()) ==  'jpg' || strtolower($photo->extension()) ==  'png' || strtolower($photo->extension()) ==  'gif' || strtolower($photo->extension()) ==  'jpeg'){
                $filename = date('YmdHi') . $photo->getClientOriginalName();
                $tujuan_upload = 'assets/images/mahasiswa';
                // create image manager with desired driver
                $manager = new ImageManager(new Driver());

                // read image from file system
                $image = $manager->read($request->file('foto')->getPathName());

                // resize image proportionally to 300px width
                $image->scale(width: 600);

                // save modified image in new format 
                $image->save($tujuan_upload . '/' . $filename);
                //$photo->move($tujuan_upload,$filename);
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
                return response()->json('Wrong extension. Extension must be jpg, jpeg, or png', 500);
            }
        }else{
            return response()->json('Failed');
        }
    }

    public function berkas_update(Request $request)
    {
        $nim = $request->nim;
        $id = MahasiswaBerkasPendukung::where("nim",$nim)->first()->id ?? '';
        $data_upload = [
            'nim'=>$nim
        ];
        $filename = ''; // kk
        if ($request->file('kk') != null) {
            $file = $request->file('kk');
            if(strtolower($file->extension()) ==  'pdf'){
                if($file->getSize() <= 2048000){
                    $filename = 'kk' . date('YmdHi') . $file->getClientOriginalName();
                    $tujuan_upload = 'assets/file/berkas';
                    $file->move($tujuan_upload,$filename);
                    $data_upload['kk'] = $filename;
                }
            }
        }

        $filename2 = ''; // ktp
        if ($request->file('ktp') != null) {
            $file = $request->file('ktp');
            if(strtolower($file->extension()) ==  'pdf'){
                if($file->getSize() <= 2048000){
                    $filename2 = 'ktp' . date('YmdHi') . $file->getClientOriginalName();
                    $tujuan_upload = 'assets/file/berkas';
                    $file->move($tujuan_upload,$filename2);
                    $data_upload['ktp'] = $filename2;
                }
            }
        }

        $filename3 = ''; // akta
        if ($request->file('akta') != null) {
            $file = $request->file('akta');
            if(strtolower($file->extension()) ==  'pdf'){
                if($file->getSize() <= 2048000){
                    $filename3 = 'akta' . date('YmdHi') . $file->getClientOriginalName();
                    $tujuan_upload = 'assets/file/berkas';
                    $file->move($tujuan_upload,$filename3);
                    $data_upload['akta'] = $filename3;
                }
            }
        }

        $filename4 = ''; // ijazah_depan
        if ($request->file('ijazah_depan') != null) {
            $file = $request->file('ijazah_depan');
            if(strtolower($file->extension()) ==  'pdf'){
                if($file->getSize() <= 2048000){
                    $filename4 = 'ijazah_depan' . date('YmdHi') . $file->getClientOriginalName();
                    $tujuan_upload = 'assets/file/berkas';
                    $file->move($tujuan_upload,$filename4);
                    $data_upload['ijazah_depan'] = $filename4;
                }
            }
        }
        $filename5 = ''; // ijazah_belakang
        if ($request->file('ijazah_belakang') != null) {
            $file = $request->file('ijazah_belakang');
            if(strtolower($file->extension()) ==  'pdf'){
                if($file->getSize() <= 2048000){
                    $filename5 = 'ijazah_belakang' . date('YmdHi') . $file->getClientOriginalName();
                    $tujuan_upload = 'assets/file/berkas';
                    $file->move($tujuan_upload,$filename5);
                    $data_upload['ijazah_belakang'] = $filename5;
                }
            }
        }
        if($id){
            
            $berkas = MahasiswaBerkasPendukung::updateOrCreate(
                ['id' => $id],
                $data_upload
            );
            return response()->json('Updated');
        }else{
            $berkas = MahasiswaBerkasPendukung::updateOrCreate(
                ['id' => $id],
                $data_upload
            );
            if ($berkas) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
            }
        }
        // if ($request->file('foto')) {
            
        //     $photo = $request->file('foto');
        //     if(strtolower($photo->extension()) ==  'pdf'){
        //         $filename = date('YmdHi') . $photo->getClientOriginalName();
                
        //         //$photo->move($tujuan_upload,$filename);
        //         $mahasiswa = Mahasiswa::updateOrCreate(
        //             [
        //                 'id' => $id
        //             ],
        //             [
        //                 'foto_mhs' => $filename,
        //             ]
        //         );
        //         $data = [
        //             'status' => 'updated',
        //             'pegawai' => Mahasiswa::find($id),
        //         ];
        //         return response()->json($data);
        //     }else{
        //         return response()->json('Wrong extension. Extension must be jpg, jpeg, or png', 500);
        //     }
        // }else{
        //     return response()->json('Failed');
        // }
    }

    public function cetakKtm(string $nimEnkrip)
    {
        // Dekripsi NIM
        $nimDekrip = Crypt::decryptString($nimEnkrip);
        $nim = str_replace("stifar", "", $nimDekrip);

        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::select(
                'mahasiswa.nama',
                'mahasiswa.nim',
                'mahasiswa.agama',
                'mahasiswa.tempat_lahir AS tempatLahir',
                'mahasiswa.tgl_lahir AS tanggalLahir',
                'mahasiswa.alamat',
                'mahasiswa.angkatan',
                'mahasiswa.created_at AS createdAt',
                'mahasiswa_berkas_pendukung.foto_sistem AS fotoSistem',
                'master_program_studi.nama_jurusan AS programStudi'
            )
            ->where('mahasiswa.nim', $nim)
            ->leftJoin('mahasiswa_berkas_pendukung', 'mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
            ->leftJoin('master_program_studi', 'master_program_studi.id', '=', 'mahasiswa.id_program_studi')
            ->first();

        if(!$mahasiswa->fotoSistem)
        {
            return response()->json(['message' => 'Foto tidak ditemukan, silahkan update herregistrasi terlebih dahulu']);
        }

        // Kirim data ke view
        $pdf = Pdf::loadView('mahasiswa.ktm.index', [
            'data' => $mahasiswa,
        ]);

        // Tampilkan inline di browser
        return $pdf->stream('KTM-'. $mahasiswa->nim .'.pdf');
    }
}
