<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MahasiswaBerkasPendukung;
use App\Models\TbDaftarWisudawanArchive;

class ArsipAdminDaftarWisudawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nim', 'wisuda', 'yudisium', 'berkas', 'status_pembayaran'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Daftar Wisudawan Arsip";
            $title2 = "daftar-wisudawan-arsip";

            $indexed = $this->indexed;
            $isArsip = true;
            return view('admin.akademik.wisuda.daftar-wisudawan.index', compact('title', 'title2','indexed', 'isArsip'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'wisuda',
                4 => 'yudisium',
                5 => 'berkas',
                6 => 'status_pembayaran'
            ];

            $search = [];

            $totalData = TbDaftarWisudawanArchive::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $query = TbDaftarWisudawanArchive::select([
                        'tb_daftar_wisudawan_archive.id',
                        'tb_alumni.nim',
                        'tb_alumni.nama',
                        'tb_alumni.foto AS fotoMhs',
                        'tb_daftar_wisudawan_archive.status AS statusDaftar',
                        'tb_gelombang_wisuda.nama AS gelombangWisuda',
                        'tb_gelombang_wisuda.waktu_pelaksanaan AS pelaksanaanWisuda',
                        'gelombang_yudisium.nama AS gelombangYudisium',
                        'tb_pembayaran_wisuda.status AS statusPembayaran',
                        'tb_pembayaran_wisuda.bukti AS buktiPembayaran',
                    ])
                    ->where('tb_daftar_wisudawan_archive.status', '=', 1)
                    ->leftJoin('tb_alumni', 'tb_daftar_wisudawan_archive.nim', '=', 'tb_alumni.nim')
                    ->leftJoin('tb_yudisium_archive', 'tb_alumni.nim', '=', 'tb_yudisium_archive.nim')
                    ->leftJoin('gelombang_yudisium', 'tb_yudisium_archive.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
                    ->leftJoin('tb_gelombang_wisuda', 'tb_daftar_wisudawan_archive.id_gelombang_wisuda', '=', 'tb_gelombang_wisuda.id')
                    ->leftJoin('tb_pembayaran_wisuda', 'tb_daftar_wisudawan_archive.nim', '=', 'tb_pembayaran_wisuda.nim');

            if (empty($request->input('search.value'))) {
                    $pendaftar = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                    $pendaftar->each(function ($mahasiswa) {
                        $berkas = MahasiswaBerkasPendukung::where("nim", $mahasiswa->nim)->latest()->first();
                        $mahasiswa->kk = $berkas->kk ?? null;
                        $mahasiswa->ktp = $berkas->ktp ?? null;
                        $mahasiswa->akte = $berkas->akte ?? null;
                        $mahasiswa->ijazahDepan = $berkas->ijazah_depan ?? null;
                        $mahasiswa->ijazahBelakang = $berkas->ijazah_belakang ?? null;

                        // Cek status pembayaran
                        if ($mahasiswa->statusPembayaran === null) {
                            $mahasiswa->statusPembayaran = 'Belum upload bukti';
                        } elseif ($mahasiswa->statusPembayaran == 0) {
                            $mahasiswa->statusPembayaran = 'Belum diverifikasi';
                        }else{
                            $mahasiswa->statusPembayaran = 'Sudah diverifikasi';
                        }
                    });

            } else {
                $search = $request->input('search.value');

                $pendaftar = $query->where('tb_alumni.nim', 'LIKE', "%{$search}%")
                    ->orWhere('tb_alumni.nama', 'LIKE', "%{$search}%")
                    ->orWhere('tb_gelombang_wisuda.nama', 'LIKE', "%{$search}%")
                    ->orWhere('gelombang_yudisium.nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                        
                $pendaftar->each(function ($mahasiswa) {
                    $berkas = MahasiswaBerkasPendukung::where("nim", $mahasiswa->nim)->latest()->first();
                    $mahasiswa->kk = $berkas->kk ?? null;
                    $mahasiswa->ktp = $berkas->ktp ?? null;
                    $mahasiswa->akte = $berkas->akte ?? null;
                    $mahasiswa->ijazahDepan = $berkas->ijazah_depan ?? null;
                    $mahasiswa->ijazahBelakang = $berkas->ijazah_belakang ?? null;

                    // Cek status pembayaran
                    if ($mahasiswa->statusPembayaran === null) {
                        $mahasiswa->statusPembayaran = 'Belum upload bukti';
                    } elseif ($mahasiswa->statusPembayaran == 0) {
                        $mahasiswa->statusPembayaran = 'Belum diverifikasi';
                    }else{
                        $mahasiswa->statusPembayaran = 'Sudah diverifikasi';
                    }
                });


                $totalFiltered = $query->where('tb_alumni.nim', 'LIKE', "%{$search}%")
                    ->orWhere('tb_alumni.nama', 'LIKE', "%{$search}%")
                    ->orWhere('tb_gelombang_wisuda.nama', 'LIKE', "%{$search}%")
                    ->orWhere('gelombang_yudisium.nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];

            if (!empty($pendaftar)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($pendaftar as $row) {
                    
                    $rowStatusPembayaran = '';
                    $tujuan_upload = 'assets/upload/mahasiswa/wisuda/bukti-bayar';
                    if($row->statusPembayaran == 'Sudah diverifikasi'){
                        $rowStatusPembayaran = '<span class="badge bg-success">' . $row->statusPembayaran . '</span>';
                    } else {
                        $rowStatusPembayaran = '<span class="badge bg-success">' . $row->statusPembayaran . '</span>';
                    }

                    $teksWisuda = $row->gelombangWisuda;
                    if ($row->pelaksanaanWisuda && strtotime($row->pelaksanaanWisuda) < time()) {
                        $teksWisuda .= ' <i class="bi bi-check-circle-fill text-success"></i>';
                    }

                    $teksNim = $row->nim;
                    if ($row->nimAlumni) {
                        $teksNim .= ' <i class="bi bi-check-circle-fill text-success"></i>';
                    }

                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nim'] = $teksNim;
                    $nestedData['nama'] = $row->nama ;
                    $nestedData['photo'] = $row->fotoMhs ;
                    $nestedData['wisuda'] = $teksWisuda;
                    $nestedData['yudisium'] = $row->gelombangYudisium ?? '-';
                    $nestedData['kk'] = $row->kk;
                    $nestedData['ktp'] = $row->ktp;
                    $nestedData['akte'] = $row->akte;
                    $nestedData['ijazah_depan'] = $row->ijazahDepan;
                    $nestedData['ijazah_belakang'] = $row->ijazahBelakang;
                    $nestedData['status_daftar'] = $row->statusDaftar;
                    $nestedData['status_pembayaran'] = $rowStatusPembayaran;
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
