<?php

namespace App\Http\Controllers\admin\akademik\wisuda;

use Illuminate\Http\Request;
use App\Models\TbGelombangWisuda;
use App\Http\Controllers\Controller;
use App\Models\DaftarWisudawan;
use App\Models\master_nilai;
use App\Models\TbDaftarWisudawanArchive;
use Illuminate\Support\Facades\Crypt;

class CetakWisudawanController extends Controller
{
    protected $kualitas = [
        'A' => 4,
        'AB' => 3.5,
        'B' => 3,
        'BC' => 2.5,
        'C' => 2,
        'CD' => 1.5,
        'D' => 1,
        'ED' => 0.5,
        'E' => 0
    ];

    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'periode', 'nama', 'tempat', 'waktu_pelaksanaan', 'tanggal_pendaftaran', 'tanggal_pemberkasan', 'tanggal_gladi', 'tarif_wisuda', 'jml_peserta'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Cetak Wisudawan";
            $title2 = "cetak"; 
            $indexed = $this->indexed;

            return view('admin.akademik.wisuda.cetak.index', compact('title', 'title2','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'periode',
                3 => 'nama',
                4 => 'tempat',
                5 => 'waktu_pelaksanaan',
                6 => 'tanggal_pendaftaran',
                7 => 'tanggal_pemberkasan',
                8 => 'tanggal_gladi',
                9 => 'tarif_wisuda',
                10 => 'jml_peserta'
            ];

            $search = [];

            $totalData = TbGelombangWisuda::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = 'created_at';
            $dir = $request->input('order.0.dir') ?? 'desc';

            $query = TbGelombangWisuda::select([
                'id',
                'periode',
                'nama',
                'tempat',
                'waktu_pelaksanaan',
                'mulai_pendaftaran',
                'selesai_pendaftaran',
                'tanggal_pemberkasan',
                'tanggal_gladi',
                'tarif_wisuda',
                'created_at',
            ]);


            if (empty($request->input('search.value'))) {
                $gelombang = $query
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get()
                    ->map(function ($item) {
                        $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
                        if ($item->waktu_pelaksanaan && strtotime($item->waktu_pelaksanaan) < time()) {
                            $item->jml_peserta = \DB::table('tb_daftar_wisudawan_archive')
                                ->where('id_gelombang_wisuda', $item->id)
                                ->where('status', 1)
                                ->count();
                        } else {
                            $item->jml_peserta = \DB::table('tb_daftar_wisudawan')
                                ->where('id_gelombang_wisuda', $item->id)
                                ->where('status', 1)
                                ->count();
                        }
                        return $item;
                    });
            } else {
                $search = $request->input('search.value');

                $gelombang = $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('periode', 'LIKE', "%{$search}%")
                    ->orWhere('tempat', 'LIKE', "%{$search}%")
                    ->orWhere('mulai_pendaftaran', 'LIKE', "%{$search}%")
                    ->orWhere('selesai_pendaftaran', 'LIKE', "%{$search}%")
                    ->orWhere('waktu_pelaksanaan', 'LIKE', "%{$search}%")
                    ->orWhere('tanggal_pemberkasan', 'LIKE', "%{$search}%")
                    ->orWhere('tanggal_gladi', 'LIKE', "%{$search}%")
                    ->orWhere('tarif_wisuda', 'LIKE', "%{$search}%")
                    ->orWhere('jml_peserta', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get()
                    ->map(function ($item) {
                        $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
                        return $item;
                    });

                $totalFiltered = $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('periode', 'LIKE', "%{$search}%")
                    ->orWhere('tempat', 'LIKE', "%{$search}%")
                    ->orWhere('mulai_pendaftaran', 'LIKE', "%{$search}%")
                    ->orWhere('selesai_pendaftaran', 'LIKE', "%{$search}%")
                    ->orWhere('waktu_pelaksanaan', 'LIKE', "%{$search}%")
                    ->orWhere('jml_peserta', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];

            if (!empty($gelombang)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($gelombang as $row) {

                    $teksWisuda = $row->nama;
                    if ($row->waktu_pelaksanaan && strtotime($row->waktu_pelaksanaan) < time()) {
                        $teksWisuda .= ' <i class="bi bi-check-circle-fill text-success"></i>';
                    }
                    
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['periode'] = $row->periode;
                    $nestedData['nama'] = $teksWisuda;
                    $nestedData['tempat'] = $row->tempat;
                    $nestedData['waktu_pelaksanaan'] = \Carbon\Carbon::parse($row->waktu_pelaksanaan)->translatedFormat('d F Y H:i');
                    $nestedData['tanggal_pendaftaran'] = \Carbon\Carbon::parse($row->mulai_pendaftaran)->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse($row->selesai_pendaftaran)->translatedFormat('d F Y');
                    $nestedData['tanggal_pemberkasan'] = \Carbon\Carbon::parse($row->tanggal_pemberkasan)->translatedFormat('d F Y');
                    $nestedData['tanggal_gladi'] = \Carbon\Carbon::parse($row->tanggal_gladi)->translatedFormat('d F Y');
                    $nestedData['tarif_wisuda'] = 'Rp ' . number_format($row->tarif_wisuda, 0, ',', '.');
                    $nestedData['jml_peserta'] = $row->jml_peserta;
                    $nestedData['idEnkripsi'] = $row->idEnkripsi;
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
        //
    }

    /**
     * Cetak Wisudawan.
     */
    public function show(string $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $gelombang = TbGelombangWisuda::find($id);
        if (!$gelombang) {
            return response()->json(['message' => 'Gelombang not found.'], 404);
        }

        if ($gelombang->waktu_pelaksanaan && strtotime($gelombang->waktu_pelaksanaan) < time()) {
            $data = TbDaftarWisudawanArchive::where('tb_daftar_wisudawan_archive.id_gelombang_wisuda', $gelombang->id)
            ->leftJoin('tb_alumni', 'tb_daftar_wisudawan_archive.nim', '=', 'tb_alumni.nim')
            ->leftJoin('tb_yudisium_archive', 'tb_yudisium_archive.nim', '=', 'tb_alumni.nim')
            ->leftJoin('gelombang_yudisium', 'gelombang_yudisium.id', '=', 'tb_yudisium_archive.id_gelombang_yudisium')
            ->join('program_studi','program_studi.id','=','tb_alumni.id_program_studi')
            ->where('tb_daftar_wisudawan_archive.status', 1)
            ->select([
                'tb_alumni.nama',
                'tb_alumni.nim',
                'gelombang_yudisium.nama AS gelombangYudisium',
                'program_studi.nama_prodi AS prodi'
                ])
            ->get();
        }else
        {
            $data = DaftarWisudawan::where('tb_daftar_wisudawan.id_gelombang_wisuda', $gelombang->id)
            ->leftJoin('mahasiswa', 'tb_daftar_wisudawan.nim', '=', 'mahasiswa.nim')
            ->leftJoin('tb_yudisium', 'tb_yudisium.nim', '=', 'mahasiswa.nim')
            ->leftJoin('gelombang_yudisium', 'gelombang_yudisium.id', '=', 'tb_yudisium.id_gelombang_yudisium')
            ->join('program_studi','program_studi.id','=','mahasiswa.id_program_studi')
            ->where('tb_daftar_wisudawan.status', 1)
            ->select([
                'mahasiswa.nama',
                'mahasiswa.nim',
                'gelombang_yudisium.nama AS gelombangYudisium',
                'program_studi.nama_prodi AS prodi'
                ])
            ->get();
        }

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Tidak ada Mahasiswa tidak ditemukan'], 404);
        }

        foreach ($data as $item) {   
            $getNilai = master_nilai::select(
                    'master_nilai.*',
                    'a.hari',
                    'a.kel',
                    'b.nama_matkul',
                    'b.sks_teori',
                    'b.sks_praktek',
                    'b.kode_matkul'
                )
                ->join('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
                ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                ->where(['nim' => $item->nim])
                ->get();

            $totalSks = 0;
            $totalIps = 0;
            foreach ($getNilai as $row) {
                $sks = ($row->sks_teori + $row->sks_praktek);
                $totalSks += $sks;
                if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1)
                {
                    $totalIps +=  ($row->sks_teori+$row->sks_praktek) * $this->kualitas[$row['nhuruf']];
                }
            }
            $ipk = $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;
            $item->predikat = ($ipk >= 3.51 && $ipk <= 4.00) ? 'Dengan Pujian / Cumlaude'
                : (($ipk >= 2.76 && $ipk <= 3.50) ? 'Sangat Memuaskan / Very Satisfying'
                : (($ipk >= 2.00 && $ipk <= 2.75) ? 'Memuaskan / Satisfying'
                : '-'));
        }

        if ($data->isEmpty()) {
            return response()->json(['message' => 'No data found for this gelombang'], 404);
        }

        $logo = public_path('/assets/images/logo/logo-icon.png');

        // Kirim data ke view dan render HTML
        $html = view('admin.akademik.wisuda.cetak.view-cetak', compact('data', 'gelombang', 'logo'))->render();

        // Inisialisasi mPDF
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'mode' => 'utf-8'
        ]);

        // Pastikan HTML tidak kosong atau error
        if (empty(trim($html))) {
            return response()->json(['message' => 'Template kosong atau error.']);
        }

        // Tulis HTML ke PDF
        $mpdf->WriteHTML($html);

        // Output PDF ke browser secara inline
        return response($mpdf->Output('Yudisium-' . $gelombang->nama .'.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
            
        return view('admin.akademik.wisuda.cetak.view-cetak', compact('gelombang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    }
}
