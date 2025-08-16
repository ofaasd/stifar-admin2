<?php

namespace App\Http\Controllers\admin\akademik\wisuda;

use Illuminate\Http\Request;
use App\Models\TbGelombangWisuda;
use App\Http\Controllers\Controller;
use App\Models\DaftarWisudawan;
use Illuminate\Support\Facades\Crypt;

class CetakWisudawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'periode', 'nama', 'tempat', 'waktu_pelaksanaan', 'tanggal_pendaftaran', 'jml_peserta'];
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
                7 => 'jml_peserta'
            ];

            $search = [];

            $totalData = TbGelombangWisuda::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $gelombang = TbGelombangWisuda::select([
                        'id',
                        'periode',
                        'nama',
                        'tempat',
                        'waktu_pelaksanaan',
                        'mulai_pendaftaran',
                        'selesai_pendaftaran',
                        \DB::raw('(SELECT COUNT(*) FROM tb_daftar_wisudawan WHERE tb_daftar_wisudawan.id_gelombang_wisuda = tb_gelombang_wisuda.id AND tb_daftar_wisudawan.status = 1) as jml_peserta'),
                    ])
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

                $gelombang = TbGelombangWisuda::where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('periode', 'LIKE', "%{$search}%")
                    ->orWhere('tempat', 'LIKE', "%{$search}%")
                    ->orWhere('mulai_pendaftaran', 'LIKE', "%{$search}%")
                    ->orWhere('selesai_pendaftaran', 'LIKE', "%{$search}%")
                    ->orWhere('waktu_pelaksanaan', 'LIKE', "%{$search}%")
                    ->orWhere('jml_peserta', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get()
                    ->map(function ($item) {
                        $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
                        return $item;
                    });

                $totalFiltered = TbGelombangWisuda::where('nama', 'LIKE', "%{$search}%")
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
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['periode'] = $row->periode;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['tempat'] = $row->tempat;
                    $nestedData['waktu_pelaksanaan'] = \Carbon\Carbon::parse($row->waktu_pelaksanaan)->translatedFormat('d F Y H:i');
                    $nestedData['tanggal_pendaftaran'] = \Carbon\Carbon::parse($row->mulai_pendaftaran)->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse($row->selesai_pendaftaran)->translatedFormat('d F Y');
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
     * Display the specified resource.
     */
    public function show(string $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $gelombang = TbGelombangWisuda::find($id);
        if (!$gelombang) {
            return redirect()->back()->with('error', 'Data not found');
        }

        $data = DaftarWisudawan::where('tb_daftar_wisudawan.id_gelombang_wisuda', 1)
        ->leftJoin('mahasiswa', 'tb_daftar_wisudawan.nim', '=', 'mahasiswa.nim')
        ->leftJoin('tb_yudisium', 'tb_yudisium.nim', '=', 'mahasiswa.nim')
        ->leftJoin('gelombang_yudisium', 'gelombang_yudisium.id', '=', 'tb_yudisium.id_gelombang_yudisium')
        ->where('tb_daftar_wisudawan.status', 1)
        ->select([
            'mahasiswa.nama',
            'mahasiswa.nim',
            'mahasiswa.foto_mhs',
            'gelombang_yudisium.nama AS gelombangYudisium'
            ])
        ->get();
        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'No data found for this gelombang');
        }

        // Kirim data ke view dan render HTML
        $html = view('admin.akademik.wisuda.cetak.view-cetak', compact('data', 'gelombang'))->render();

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
