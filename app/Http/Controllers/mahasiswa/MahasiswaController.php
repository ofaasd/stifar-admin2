<?php

namespace App\Http\Controllers\mahasiswa;

use stdClass;
use App\helpers;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Wilayah;
use App\Models\MasterPt;
use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use App\Models\master_nilai;
// use Illuminate\Support\Facades\Request;
use App\Models\ModelHasRole;
use Illuminate\Http\Request;
use App\Models\DetailTagihan;
use App\Models\LogMh as LogMhs;
use App\Models\PegawaiBiodatum;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\JabatanStruktural;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;

use Illuminate\Support\Facades\Crypt;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\MahasiswaBerkasPendukung;
use Intervention\Image\Drivers\Gd\Driver;

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
        $angkatan = $mhs->whereNotNull('angkatan')->where('angkatan', '!=', '')->pluck('angkatan')->unique();

        foreach($prodi as $row){
            $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        return view('mahasiswa.daftar', compact('title', 'mhs', 'no', 'prodi','jumlah','nama', 'angkatan'));
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
            'mahasiswa.foto_mhs AS fotoMahasiswa',
            'pegawai_biodata.nama_lengkap as dosenWali',
            'mahasiswa_berkas_pendukung.kk AS foto_kk',
            'mahasiswa_berkas_pendukung.ktp AS foto_ktp',
            'mahasiswa_berkas_pendukung.akte AS foto_akte',
            'mahasiswa_berkas_pendukung.ijazah_depan AS foto_ijazah_depan',
            'mahasiswa_berkas_pendukung.ijazah_belakang AS foto_ijazah_belakang',
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
        $list_bulan = array(
                1=>"Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember"
            );

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
            'list_bulan',
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
                    'bulan_awal'=>$request->bulan_awal,
                    'status' => 1,
                    'no_pisn' => $request->no_pisn ?? null,
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
            $mahasiswa = Mahasiswa::find($id);
            //masuk ke log jika ada perubahan status
            if($mahasiswa->status != $request->status){
                LogMhs::create(['id_mhs'=>$id,'status'=>$request->status]);
            }
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
                    'bulan_awal'=>$request->bulan_awal,
                    'status' => $request->status,
                    'no_pisn' => $request->no_pisn ?? null,
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

    public function cetakKtm(Request $request)
    {
        $pilihan = $request->pilihan;
        $spesifik = $request->spesifik;

        $query = Mahasiswa::select(
                'mahasiswa.nama',
                'mahasiswa.nim',
                'mahasiswa.agama',
                'mahasiswa.tempat_lahir AS tempatLahir',
                'mahasiswa.tgl_lahir AS tanggalLahir',
                'mahasiswa.alamat',
                'mahasiswa.angkatan',
                'mahasiswa.foto_mhs AS fotoMahasiswa',
                'mahasiswa.created_at AS createdAt',
                'program_studi.nama_prodi AS programStudi'
            )
            ->leftJoin('mahasiswa_berkas_pendukung', 'mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
            ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi');

        if($pilihan == 'prodi'){
            $mahasiswa = $query->where('mahasiswa.id_program_studi', $spesifik)->get();
        }elseif($pilihan == 'angkatan'){
            $mahasiswa = $query->where('mahasiswa.angkatan', $spesifik)->get();
        }else{
            return redirect()->back()->with('error', 'Pilihan tidak valid.');
        }

        // Pastikan $mahasiswa adalah collection dan tidak kosong
        if ($mahasiswa->isEmpty()) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan untuk kriteria yang dipilih.');
        }

        // Kirim data ke view
        $pdf = Pdf::loadView('mahasiswa.ktm.index', [
            'data' => $mahasiswa,
        ]);

        // Tampilkan inline di browser
        return $pdf->stream('KTM-'. $pilihan . '-'. $spesifik .'.pdf');
    }

    public function cetakTranskripNilai(Request $request)
    {
        try {
            // Dekripsi NIM
            $nimDekrip = Crypt::decryptString($request->nimEnkripsi);
            $nim = str_replace("stifar", "", $nimDekrip);

            $helpers = new helpers;
            $data = Mahasiswa::select([
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.tempat_lahir AS tempatLahir',
                'mahasiswa.tgl_lahir AS tglLahir',
                'mahasiswa.foto_mhs AS fotoMhs',
                'mahasiswa.foto_yudisium AS fotoYudisium',
                'program_studi.nama_prodi AS programStudi',
                'program_studi.jenjang',
                'program_studi.nama_ijazah AS gelar',
                'pengajuan_judul_skripsi.judul',
                'pengajuan_judul_skripsi.judul_eng AS judulEng',
                'pegawai_biodata.npp AS nppKaprodi',
                'pegawai_biodata.nama_lengkap AS namaKaprodi',
                'gelombang_yudisium.tanggal_pengesahan AS tanggalLulus'
            ])
            ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
            ->leftJoin('master_skripsi', 'mahasiswa.nim', '=', 'master_skripsi.nim')
            ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
            ->leftJoin('jabatan_struktural', 'mahasiswa.id_program_studi', '=', 'jabatan_struktural.prodi_id')
            ->leftJoin('pegawai_biodata', 'jabatan_struktural.id_pegawai', '=', 'pegawai_biodata.id_pegawai')
            ->leftJoin('tb_yudisium', 'mahasiswa.nim', '=', 'tb_yudisium.nim')
            ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
            ->where('mahasiswa.nim', $nim)
            ->where('pengajuan_judul_skripsi.status', 1)
            ->where(function($query){
                $query->where('jabatan_struktural.jabatan', 'like', '%kepala%')
                      ->orWhereNull('jabatan_struktural.jabatan');
            })
            ->first();

            if (!$data) {
                return response()->json(['message' => 'Data mahasiswa tidak ditemukan atau belum lengkap. Lengkapi data yudisium, judul skripsi, dan data lainnya.'], 404);
            }

            $data->tglLahir = \Carbon\Carbon::parse($data->tglLahir)->translatedFormat('d F Y');
            $data->tanggalLulus = \Carbon\Carbon::parse($data->tanggalLulus)->translatedFormat('d F Y');

            if (preg_match('/\((.*?)\)/', $data->gelar, $matches)) {
                $data->gelar = $matches[1];
            } else {
                $data->gelar = $data->gelar;
            }

            $getNilai = master_nilai::select(
                'master_nilai.*',
                'a.hari',
                'a.kel',
                'b.nama_matkul',
                'b.sks_teori',
                'b.sks_praktek',
                'b.kode_matkul'
            )
            ->leftJoin('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
            ->join('mata_kuliahs as b', function($join) {
                $join->on('a.id_mk', '=', 'b.id')
                        ->orOn('master_nilai.id_matkul', '=', 'b.id');
            })
            ->where('nim', $data->nim)
            ->whereNotNull('master_nilai.nakhir')
            ->get();

            $totalSks = 0;
            $totalIps = 0;
            $totalMutu = 0;
            $totalBobot = 0;
            $mataKuliah = [];
            foreach ($getNilai as $row) {
                $mutu = $helpers->getKualitas($row->nhuruf) ?? 0;
                $countBobot = ($row->sks_teori + $row->sks_praktek) * $helpers->getKualitas($row->nhuruf);

                $mataKuliah[] = [
                    'kodeMatkul'        => $row->kode_matkul ?? 'data tidak ditemukan',
                    'namaMataKuliah'    => $row->nama_matkul ?? 'data tidak ditemukan',
                    'namaMataKuliahEng' => $row->nama_matkul_eng ?? 'data tidak ditemukan',
                    'totalSks'          => $row->sks_teori + $row->sks_praktek,
                    'nilai'             => $row->nhuruf,
                    'mutu'              => $mutu,
                    'bobot'             => $countBobot,
                ];

                $sks = ($row->sks_teori + $row->sks_praktek);
                $totalSks += $sks;
                if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1)
                {
                    $totalIps +=  ($row->sks_teori+$row->sks_praktek) * $helpers->getKualitas($row->nhuruf);
                    $totalMutu += $mutu;
                    $totalBobot += $countBobot;
                }
            }
            $data->totalSks = $totalSks;
            $data->totalMutu = $totalMutu;
            $data->totalBobot = $totalBobot;
            $data->ipk = $totalSks > 0 ? floatval(number_format($totalIps / $totalSks, 2)) : 0;
            $data->mataKuliah = $mataKuliah;

            $jabatanStruktural = JabatanStruktural::select([
                'pegawai_biodata.npp',
                'pegawai_biodata.nama_lengkap',
            ])
            ->where('jabatan', 'Ketua')
            ->leftJoin('pegawai_biodata', 'jabatan_struktural.id_pegawai', '=', 'pegawai_biodata.id_pegawai')
            ->first();

            $data->nppKetua = $jabatanStruktural->npp ?? '-';
            $data->namaKetua = $jabatanStruktural->nama_lengkap ?? '-';

            $dataKampus = MasterPt::latest()->first();
            $printedAt = now();

            // Kirim data ke view dan render HTML
            $html = view('mahasiswa.transkrip-nilai.index', [
                'nomorSk' => $request->nomorSk,
                'nomorSeri' => $request->nomorSeri,
                'data' => $data,
                'dataKampus' => $dataKampus,
                'printedAt' => $printedAt,
            ])->render();

            // Inisialisasi mPDF
            $mpdf = new \Mpdf\Mpdf();

            // Pastikan HTML tidak kosong atau error
            if (empty(trim($html))) {
                return response()->json(['message' => 'Template kosong atau error.']);
            }

            // Tulis HTML ke PDF
            $mpdf->WriteHTML($html);

            // Output PDF ke browser secara inline
            return response($mpdf->Output('transkrip-nilai-' . $data->nim . '-'. $data->nama .'.pdf', 'I'))
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
