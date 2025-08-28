<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Prodi;
use App\Models\Alumni;
use App\Models\Mahasiswa;
use App\Models\TbFlagging;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\JabatanStruktural;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nim', 'nama', 'jenjang', 'angkatan', 'tahun_lulus', 'prodi'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Alumni";
            $prodi = Prodi::all();
            $indexed = $this->indexed;

            return view('admin.alumni.index', compact('title', 'prodi','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nama',
                4 => 'jenjang',
                5 => 'angkatan',
                6 => 'tahun_lulus',
                7 => 'prodi',
            ];

            $search = [];

            $totalData = Alumni::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $alumni = Alumni::select([
                        'tb_alumni.*',
                        'program_studi.nama_prodi'
                    ])
                    ->leftJoin('program_studi', 'tb_alumni.prodi', '=', 'program_studi.id')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $alumni = Alumni::select([
                        'tb_alumni.*',
                        'program_studi.nama_prodi'
                    ])
                    ->leftJoin('program_studi', 'tb_alumni.prodi', '=', 'program_studi.id')
                    ->where('nim', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Alumni::where('nim', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];

            if (!empty($alumni)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($alumni as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nim'] = $row->nim;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['jenjang'] = $row->jenjang;
                    $nestedData['angkatan'] = $row->angkatan;
                    $nestedData['tahun_lulus'] = $row->tahun_lulus;
                    $nestedData['prodi'] = $row->nama_prodi;
                    $data[] = $nestedData;
                }
            }
            if ($data) {
                return response()->json([
                  'draw' => intval($request->input('draw')),
                  'recordsTotal' => intval($totalData),
                  'recordsFiltered' => intval($totalFiltered),
                  'code' => 200,
                  'data' => $data,
                ]);
              } else {
                return response()->json([
                  'message' => 'Internal Server Error',
                  'code' => 500,
                  'data' => [],
                ]);
              }
        }
    }

    /**
     * Get data alumni.
     */
    public function get_alumni(Request $request)
    {
        $id = $request->id;

        $no = 1;
        $prodi = Prodi::all();
        $jumlah = [];
        $nama = [];

        foreach($prodi as $row){
            $jumlah[$row->id] = Alumni::where('id_program_studi',$row->id)->count();
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        
        $isAlumni = true;
        $isPrintIjazah = true;

        $query = Alumni::select([
            'tb_alumni.*',
            'tb_flagging.count AS tercetak'
        ])
        ->leftJoin('tb_flagging', 'tb_alumni.nim', '=', 'tb_flagging.nim')
        ->get()
        ->map(function ($item) {
            $item->nimEnkripsi = Crypt::encryptString($item->nim . "stifar");
            return $item;
        });

        if($id == 0){
            $alumni = $query;

            return view('mahasiswa._table_alumni_mhs', compact('alumni', 'no', 'prodi', 'jumlah', 'nama', 'isAlumni', 'isPrintIjazah'));
        }else{
            $alumni = $query->where('id_program_studi', $id);

            return view('mahasiswa._table_alumni_mhs', compact('alumni', 'no', 'prodi', 'jumlah', 'nama', 'isAlumni', 'isPrintIjazah'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = $request->id;

        try {
            $request->validate([
                'nim' => 'required',
                'nama' => 'required',
                'jenjang' => 'required',
                'angkatan' => 'required',
                'tahun_lulus' => 'required',
                'jenis_kelamin' => 'required',
            ]);
            
            if ($id) {
                $save = Alumni::updateOrCreate(
                    ['id' => $id],
                    [
                        'nim' => $request->nim,
                        'nama' => $request->nama,
                        'jenjang' => $request->jenjang,
                        'angkatan' => $request->angkatan,
                        'tahun_lulus' => $request->tahun_lulus,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'no_hp' => $request->no_hp,
                        'email_pribadi' => $request->email_pribadi,
                        'prodi' => $request->prodi,
                        'judul_skripsi' => $request->judul_skripsi,
                        'waktu_awal_kerja' => $request->waktu_awal_kerja,
                        'waktu_mulai' => $request->waktu_mulai,
                        'status_pekerjaan' => $request->status_pekerjaan,
                        'posisi' => $request->posisi,
                        'tempat_pekerjaan' => $request->tempat_pekerjaan,
                    ]
                );

                // user updated
                return response()->json(['message' => 'Updated', 'code' => 200]);
            } else {
                $save = Alumni::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'jenjang' => $request->jenjang,
                        'angkatan' => $request->angkatan,
                        'tahun_lulus' => $request->tahun_lulus,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'no_hp' => $request->no_hp,
                        'email_pribadi' => $request->email_pribadi,
                        'prodi' => $request->prodi,
                        'judul_skripsi' => $request->judul_skripsi,
                        'waktu_awal_kerja' => $request->waktu_awal_kerja,
                        'waktu_mulai' => $request->waktu_mulai,
                        'status_pekerjaan' => $request->status_pekerjaan,
                        'posisi' => $request->posisi,
                        'tempat_pekerjaan' => $request->tempat_pekerjaan,
                    ]
                );

            if ($save) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Alumni');
            }
        }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
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
        $where = ['id' => $id];

        $data = Alumni::where($where)->first();

        if($data->waktu_awal_kerja){
            $data->teksWaktuAwalKerja = Carbon::parse($data->waktu_awal_kerja)->translatedFormat('d F Y');
        }

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function cetakIjazah(Request $request)
    {
        // Dekripsi NIM
        $nimDekrip = Crypt::decryptString($request->nimEnkripsi);
        $nim = str_replace("stifar", "", $nimDekrip);

        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::select(
                'mahasiswa.nama',
                'mahasiswa.nim',
                'mahasiswa.no_ktp AS nik',
                'mahasiswa.agama',
                'mahasiswa.tempat_lahir AS kotaKelahiran',
                'mahasiswa.tgl_lahir AS tanggalLahir',
                'mahasiswa.alamat',
                'mahasiswa.angkatan',
                'mahasiswa.foto_mhs AS fotoMhs',
                'mahasiswa.foto_yudisium AS fotoYudisium',
                'mahasiswa.created_at AS createdAt',
                'program_studi.nama_prodi AS prodiIndo',
                'program_studi.nama_prodi_eng AS prodiInggris',
                'program_studi.nama_ijazah AS namaIjazahIndo',
                'program_studi.nama_ijazah_eng AS namaIjazahInggris',
                'gelombang_yudisium.tanggal_pengesahan AS lulusPada',
                'mahasiswa.no_pisn AS noPisn',
                'pegawai_biodata.npp AS nppKaprodi',
                'pegawai_biodata.nama_lengkap AS namaKaprodi',
            )
            ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
            ->leftJoin('tb_yudisium', 'mahasiswa.nim', '=', 'tb_yudisium.nim')
            ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
            ->leftJoin('jabatan_struktural', 'mahasiswa.id_program_studi', '=', 'jabatan_struktural.prodi_id')
            ->leftJoin('pegawai_biodata', 'jabatan_struktural.id_pegawai', '=', 'pegawai_biodata.id_pegawai')
            ->where('mahasiswa.nim', $nim)
            ->where('jabatan_struktural.jabatan', 'like', '%kepala%')
            ->first();

        if (!$mahasiswa) 
        {
            return response()->json(['message' => 'Data tidak ditemukan.']);
        }
        
        if(!$mahasiswa->lulusPada)
        {
            return response()->json(['message' => 'Yudisium belum disahkan.']);
        }

        if(!$mahasiswa->noPisn)
        {
            return response()->json(['message' => 'No PISN belum ada.']);
        }

        $jabatanStruktural = JabatanStruktural::select([
            'pegawai_biodata.npp',
            'pegawai_biodata.nama_lengkap',
        ])
        ->where('jabatan', 'Ketua')
        ->leftJoin('pegawai_biodata', 'jabatan_struktural.id_pegawai', '=', 'pegawai_biodata.id_pegawai')
        ->first();

        $mahasiswa->nppKetua = $jabatanStruktural->npp ?? '-';
        $mahasiswa->namaKetua = $jabatanStruktural->nama_lengkap ?? '-';

        $cekDuplikate = TbFlagging::where('nim', $nim)->where('jenis', 1)->first();
        if (!$cekDuplikate) {
            TbFlagging::create([
                'nim' => $nim,
                'jenis' => 1,
                'count' => 0,
            ]);
            $duplikatKe = 0;
        } else {
            $cekDuplikate->count = $cekDuplikate->count + 1;
            $cekDuplikate->save();
            $duplikatKe = $cekDuplikate->count;
        }

        // Kirim data ke view dan render HTML
        $html = view('mahasiswa.ijazah.index', [
            'akreditasiBadanPtKes' => $request->akreditasi1,
            'akreditasiLamPtKes' => $request->akreditasi2,
            'akreditasiLamPtKesInggris' => $request->akreditasi2Eng,
            'duplikatKe' => $duplikatKe,
            'data' => $mahasiswa,
        ])->render();

        // Inisialisasi mPDF
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4-L',
            'mode' => 'utf-8',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);

        // Pastikan HTML tidak kosong atau error
        if (empty(trim($html))) {
            return response()->json(['message' => 'Template kosong atau error.']);
        }

        // Tulis HTML ke PDF
        $mpdf->WriteHTML($html);

        // Output PDF ke browser secara inline
        return response($mpdf->Output('ijazah-' . $mahasiswa->nim . ' ' . $mahasiswa->nama . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Alumni::where('id', $id)->delete();
    }
}
