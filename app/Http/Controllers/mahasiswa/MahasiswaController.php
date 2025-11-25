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
use App\Models\TbYudisium;
use Intervention\Image\Drivers\Gd\Driver;

class MahasiswaController extends Controller
{
    protected $helpers;
    public function __construct()
    {
        $this->helpers = new helpers;
    }

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

        return view('mahasiswa.create', compact('user','status','dosen','kecamatan','wilayah','kota','title', 'mahasiswa','prodi','agama','list_bulan'));
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
        $noTranskrip = $request->no_transkrip ?? null;

        $mhs = Mahasiswa::where('no_transkrip', $noTranskrip)
                ->where('no_tranksrip', '!=', '')
                ->where('id', '!=', $id)
                ->first();

        if($mhs){
            $data = [
                'status' => 500,
                'message' => 'No Seri Transkrip sudah digunakan oleh mahasiswa lain.',
            ];
            return response()->json($data, 500);
        }

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
                    'nilai_toefl'=>$request->nilai_toefl ?? null,
                    'no_transkrip'=>$request->no_transkrip ?? null,
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
                    'nilai_toefl'=>$request->nilai_toefl ?? null,
                    'no_pisn' => $request->no_pisn ?? null,
                    'no_transkrip'=>$request->no_transkrip ?? null,
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
        $prodi = $request->prodi;
        $angkatan = $request->angkatan;

        // Ambil hanya kolom yang diperlukan, gunakan kondisi with when() untuk Efisiensi query
        $mahasiswa = Mahasiswa::query()
            ->select(
                'nim',
                'nama',
                'agama',
                'tempat_lahir as tempatLahir',
                'tgl_lahir as tanggalLahir',
                'alamat',
                'angkatan',
                'foto_mhs as fotoMahasiswa',
                'created_at as createdAt',
                'id_program_studi'
            )
            ->when($prodi, fn($q) => $q->where('id_program_studi', $prodi))
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->orderBy('nama')
            ->get();

        if ($mahasiswa->isEmpty()) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan untuk kriteria yang dipilih.');
        }

        // Ambil nama prodi hanya jika diperlukan (lebih cepat daripada join jika data sedikit)
        $fileNameSpesifik = '-';
        if (!empty($prodi)) {
            $fileNameSpesifik = Prodi::where('id', $prodi)->value('nama_prodi') ?? '-';
        }

        $pdf = Pdf::loadView('mahasiswa.ktm.index', [
            'data' => $mahasiswa,
        ]);

        $fileName = 'KTM-' . ucwords($angkatan ?: '-') . '-' . $fileNameSpesifik . '.pdf';

        return $pdf->stream($fileName);
    }

    public function cetakTranskripNilai(Request $request)
    {
        try {
            // Dekripsi NIM
            $nimDekrip = Crypt::decryptString($request->nimEnkripsi);
            $nim = str_replace("stifar", "", $nimDekrip);

            $data = Mahasiswa::select([
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.tempat_lahir AS tempatLahir',
                'mahasiswa.tgl_lahir AS tglLahir',
                'mahasiswa.foto_mhs AS fotoMhs',
                'mahasiswa.foto_yudisium AS fotoYudisium',
                'mahasiswa.no_transkrip AS noTranskrip',
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
            ->leftJoin('tb_yudisium', 'mahasiswa.nim', '=', 'tb_yudisium.nim')
            ->leftJoin('pegawai_biodata', 'tb_yudisium.id_kaprodi', '=', 'pegawai_biodata.id')
            ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
            ->where('mahasiswa.nim', $nim)
            ->where('pengajuan_judul_skripsi.status', 1)
            ->first();

            // Jika tidak ditemukan di tabel mahasiswa, coba ambil dari alumni
            if (!$data) {
                $data = \DB::table('tb_alumni')
                    ->select([
                        'tb_alumni.nim',
                        'tb_alumni.nama',
                        'tb_alumni.tempat_lahir AS tempatLahir',
                        'tb_alumni.tgl_lahir AS tglLahir',
                        'tb_alumni.foto AS fotoMhs',
                        'tb_alumni.foto_yudisium AS fotoYudisium',
                        'tb_alumni.no_transkrip AS noTranskrip',
                        'program_studi.nama_prodi AS programStudi',
                        'program_studi.jenjang',
                        'program_studi.nama_ijazah AS gelar',
                        'pengajuan_judul_skripsi.judul',
                        'pengajuan_judul_skripsi.judul_eng AS judulEng',
                        'pegawai_biodata.npp AS nppKaprodi',
                        'pegawai_biodata.nama_lengkap AS namaKaprodi',
                        'gelombang_yudisium.tanggal_pengesahan AS tanggalLulus'
                    ])
                    ->leftJoin('program_studi', 'program_studi.id', '=', 'tb_alumni.id_program_studi')
                    ->leftJoin('master_skripsi', 'tb_alumni.nim', '=', 'master_skripsi.nim')
                    ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
                    ->leftJoin('tb_yudisium', 'tb_alumni.nim', '=', 'tb_yudisium.nim')
                    ->leftJoin('pegawai_biodata', 'tb_yudisium.id_kaprodi', '=', 'pegawai_biodata.id')
                    ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
                    ->where('tb_alumni.nim', $nim)
                    ->where(function($query){
                        // jika pengajuan judul ada pastikan status = 1, tapi jika tidak ada biarkan tetap ambil data alumni
                        $query->where('pengajuan_judul_skripsi.status', 1)
                              ->orWhereNull('pengajuan_judul_skripsi.status');
                    })
                    ->first();
            }

            if (!$data) {
                return response()->json(['message' => 'Data mahasiswa tidak ditemukan atau belum lengkap. Lengkapi data yudisium, judul skripsi, dan data lainnya.'], 404);
            }

            // pastikan properti ada dan valid sebelum di-parse
            if (!empty($data->tglLahir)) {
                try {
                    $data->tglLahir = \Carbon\Carbon::parse($data->tglLahir)->translatedFormat('d F Y');
                } catch (\Exception $e) {
                    $data->tglLahir = $data->tglLahir; // biarkan apa adanya jika bukan tanggal valid
                }
            } else {
                $data->tglLahir = '-';
            }

            if (!empty($data->tanggalLulus)) {
                try {
                    $data->tanggalLulus = \Carbon\Carbon::parse($data->tanggalLulus)->translatedFormat('d F Y');
                } catch (\Exception $e) {
                    $data->tanggalLulus = $data->tanggalLulus;
                }
            } else {
                $data->tanggalLulus = '-';
            }

            if (preg_match('/\((.*?)\)/', $data->gelar, $matches)) {
                $data->gelar = $matches[1];
            } else {
                $data->gelar = $data->gelar;
            }

            $getNilai = $this->helpers->getDaftarNilaiMhs($data->nim);

            $totalSks = 0;
            $totalIps = 0;
            $totalMutu = 0;
            $totalBobot = 0;
            $mataKuliah = [];
            // kumpulkan mata kuliah unik berdasarkan kombinasi kode + nama. jika duplikat, ambil yang 'terbesar' berdasarkan bobot (sks * mutu)
            $mataKuliahMap = [];
            foreach ($getNilai as $row) {
                $mutu = $this->helpers->getKualitas($row->nhuruf) ?? 0;
                $sks = ($row->sks_teori + $row->sks_praktek);
                $countBobot = $sks * $mutu;

                $kodePerbandingan = preg_replace('/\s+/', '', strtolower(trim($row->kode_matkul ?? '')));
                $namaMkPerbandingan = preg_replace('/\s+/', '', strtolower(trim($row->nama_matkul ?? '')));
                $key = $kodePerbandingan . '|' . $namaMkPerbandingan;

                $kode = $row->kode_matkul ?? '';
                $namaMk = $row->nama_matkul ?? '';
                $entry = [
                    'kodeMatkul'        => $kode ?: 'data tidak ditemukan',
                    'namaMataKuliah'    => $namaMk ?: 'data tidak ditemukan',
                    'namaMataKuliahEng' => $row->nama_matkul_eng ?? 'data tidak ditemukan',
                    'totalSks'          => $sks,
                    'nilai'             => $row->nhuruf,
                    'mutu'              => $mutu,
                    'bobot'             => $countBobot,
                    'validasi_tugas'    => $row->validasi_tugas ?? 0,
                    'validasi_uts'      => $row->validasi_uts ?? 0,
                    'validasi_uas'      => $row->validasi_uas ?? 0,
                ];

                // jika belum ada, atau bobot saat ini lebih besar dari yang tersimpan, replace
                if (!isset($mataKuliahMap[$key]) || $mutu > $mataKuliahMap[$key]['mutu']) {
                    $mataKuliahMap[$key] = $entry;
                }
            }

            // konversi map menjadi array dan hitung total berdasarkan item unik yang telah dipilih
            $mataKuliah = [];
            foreach ($mataKuliahMap as $entry) {
                $mataKuliah[] = $entry;
                $totalSks += $entry['totalSks'];
                if ($entry['validasi_tugas'] == 1 && $entry['validasi_uts'] == 1 && $entry['validasi_uas'] == 1) {
                    $totalIps += $entry['totalSks'] * $entry['mutu'];
                    $totalMutu += $entry['mutu'];
                    $totalBobot += $entry['bobot'];
                }
            }
            $data->totalSks = $totalSks;
            $data->totalMutu = $totalMutu;
            $data->totalBobot = $totalBobot;
            $data->ipk = $totalSks > 0 ? floatval(number_format($totalIps / $totalSks, 2)) : 0;
            $data->mataKuliah = $mataKuliah;

            $jabatanStruktural = TbYudisium::select([
                'pegawai_biodata.npp',
                'pegawai_biodata.nama_lengkap',
            ])
            ->where('tb_yudisium.nim', $data->nim)
            ->leftJoin('pegawai_biodata', 'tb_yudisium.id_ketua', '=', 'pegawai_biodata.id')
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
