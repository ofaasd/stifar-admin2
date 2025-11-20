<?php

namespace App\Http\Controllers\admin\akademik\yudisium;

use App\Models\TbYudisium;
use App\Models\master_nilai;
use Illuminate\Http\Request;
use App\Models\GelombangYudisium;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class CetakYudisiumController extends Controller
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
    * menampilkan halaman dan data gelombang yudisium.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public $indexed = ['', 'id', 'nama', 'nama_prodi', 'periode', 'jml_peserta'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Cetak Yudisium";
            $title2 = "cetak";
            $gelombang = GelombangYudisium::select([
                'gelombang_yudisium.*',
                'program_studi.nama_prodi'
            ])
            ->leftJoin('program_studi', 'gelombang_yudisium.id_prodi', '=', 'program_studi.id')
            ->orderBy('created_at', 'desc')
            ->get();
            $indexed = $this->indexed;

            return view('admin.akademik.yudisium.cetak.index', compact('title', 'title2', 'gelombang','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nama',
                3 => 'periode',
                4 => 'jml_peserta',
            ];

            $search = [];

            $totalData = GelombangYudisium::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = 'gelombang_yudisium.created_at';
            $dir = $request->input('order.0.dir') ?? 'desc';

            if (empty($request->input('search.value'))) {
                $gelombang = GelombangYudisium::select([
                        'gelombang_yudisium.id',
                        'gelombang_yudisium.nama',
                        'gelombang_yudisium.periode',
                        \DB::raw('(SELECT COUNT(*) FROM tb_yudisium WHERE tb_yudisium.id_gelombang_yudisium = gelombang_yudisium.id) as jmlPeserta'),
                        'gelombang_yudisium.tanggal_pengesahan AS tanggalPengesahan',
                        'program_studi.nama_prodi'
                    ])
                    ->leftJoin('program_studi', 'gelombang_yudisium.id_prodi', '=', 'program_studi.id')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get()
                    ->map(function ($item) {
                        $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
                        return $item;
                    });
            } else {
                $search = $request->input('search.value');

                $gelombang = GelombangYudisium::select([
                        'gelombang_yudisium.id',
                        'gelombang_yudisium.nama',
                        'gelombang_yudisium.periode',
                        \DB::raw('(SELECT COUNT(*) FROM tb_yudisium WHERE tb_yudisium.id_gelombang_yudisium = gelombang_yudisium.id) as jmlPeserta'),
                        'gelombang_yudisium.tanggal_pengesahan AS tanggalPengesahan',
                        'program_studi.nama_prodi'
                    ])
                    ->leftJoin('program_studi', 'gelombang_yudisium.id_prodi', '=', 'program_studi.id')
                    ->where('gelombang_yudisium.periode', 'LIKE', "%{$search}%")
                    ->orWhere('gelombang_yudisium.nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get()
                    ->map(function ($item) {
                        $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
                        return $item;
                    });

                $totalFiltered = GelombangYudisium::select([
                        'gelombang_yudisium.id',
                        'gelombang_yudisium.nama',
                        'gelombang_yudisium.periode',
                        \DB::raw('(SELECT COUNT(*) FROM tb_yudisium WHERE tb_yudisium.id_gelombang_yudisium = gelombang_yudisium.id) as jmlPeserta'),
                        'gelombang_yudisium.tanggal_pengesahan AS tanggalPengesahan',
                        'program_studi.nama_prodi'
                    ])
                    ->leftJoin('program_studi', 'gelombang_yudisium.id_prodi', '=', 'program_studi.id')
                    ->where('gelombang_yudisium.periode', 'LIKE', "%{$search}%")
                    ->orWhere('gelombang_yudisium.nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];

            if (!empty($gelombang)) {
                // providing a dummy id instead of database ids
                $ids = $start;
                
                foreach ($gelombang as $row) {
                    $tanggalPengesahan = $row->tanggalPengesahan ? Carbon::parse($row->tanggalPengesahan)->translatedFormat('d F Y') : null;

                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['periode'] = $row->periode;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['nama_prodi'] = $row->nama_prodi;
                    $nestedData['jml_peserta'] = $row->jmlPeserta;
                    $nestedData['idEnkripsi'] = $row->idEnkripsi;
                    $nestedData['tanggalPengesahan'] = $tanggalPengesahan;
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

    public function getDataSahYudisium()
    {
        $data = GelombangYudisium::select([
            'gelombang_yudisium.id',
            'gelombang_yudisium.nama',
            'gelombang_yudisium.periode',
            \DB::raw('(SELECT COUNT(*) FROM tb_yudisium WHERE tb_yudisium.id_gelombang_yudisium = gelombang_yudisium.id) as jmlPeserta'),
            'gelombang_yudisium.tanggal_pengesahan AS tanggalPengesahan',
            'program_studi.nama_prodi'
        ])
        ->leftJoin('program_studi', 'gelombang_yudisium.id_prodi', '=', 'program_studi.id')
        ->whereNotNull('gelombang_yudisium.tanggal_pengesahan')
        ->orderBy('gelombang_yudisium.created_at', 'desc')
        ->get();
        if ($data) {
            return response()->json([
                'draw' => 1,
                'recordsTotal' => (int) $data->count(),
                'recordsFiltered' => (int) $data->count(),
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
    /**
    * mencetak daftar peserta yudisium per gelombang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function show(string $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $gelombang = GelombangYudisium::select([
            'gelombang_yudisium.*',
            'program_studi.nama_prodi'
        ])
        ->where('gelombang_yudisium.id', $id)
        ->leftJoin('program_studi', 'gelombang_yudisium.id_prodi', '=', 'program_studi.id')
        ->first();
        if (!$gelombang) {
            return redirect()->back()->with('error', 'Data not found');
        }

        $data = TbYudisium::where('id_gelombang_yudisium', $id)
        ->leftJoin('mahasiswa', 'tb_yudisium.nim', '=', 'mahasiswa.nim')
        ->leftJoin('master_skripsi', 'mahasiswa.nim', '=', 'master_skripsi.nim')
        ->leftJoin('sidang', 'master_skripsi.id', '=', 'sidang.skripsi_id')
        ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
        ->where('pengajuan_judul_skripsi.status', 1)
        ->select([
            'mahasiswa.nama',
            'mahasiswa.nim',
            'mahasiswa.foto_mhs',
            'pengajuan_judul_skripsi.judul',
            'sidang.tanggal AS tanggalSidang'
            ])
        ->get();
        
        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'No data found for this gelombang');
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
            ->leftJoin('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
            ->join('mata_kuliahs as b', function($join) {
                $join->on('a.id_mk', '=', 'b.id')
                        ->orOn('master_nilai.id_matkul', '=', 'b.id');
            })
            ->where('nim', $item->nim)
            ->whereNotNull('master_nilai.nakhir')
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
            $item->totalSks = $totalSks;
            $item->totalIps = $totalIps;
            $item->ipk = $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;
        }
        $logo = public_path('/assets/images/logo/logo-icon.png');
        // Kirim data ke view dan render HTML
        $html = view('admin.akademik.yudisium.cetak.view-cetak', compact('data', 'gelombang', 'logo'))->render();

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
            
        return view('admin.akademik.yudisium.cetak', compact('gelombang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
