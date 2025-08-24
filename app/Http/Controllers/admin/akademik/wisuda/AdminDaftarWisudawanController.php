<?php

namespace App\Http\Controllers\admin\akademik\wisuda;

use App\Models\Alumni;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\DaftarWisudawan;
use App\Http\Controllers\Controller;
use App\Models\MahasiswaBerkasPendukung;

class AdminDaftarWisudawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nim', 'wisuda', 'yudisium', 'berkas', 'status_pembayaran'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Daftar Wisudawan";
            $title2 = "daftar-wisudawan";

            $indexed = $this->indexed;
            return view('admin.akademik.wisuda.daftar-wisudawan.index', compact('title', 'title2','indexed'));
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

            $totalData = DaftarWisudawan::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $query = DaftarWisudawan::select([
                        'tb_daftar_wisudawan.id',
                        'mahasiswa.nim',
                        'mahasiswa.nama',
                        'mahasiswa.foto_mhs AS fotoMhs',
                        'tb_daftar_wisudawan.status AS statusDaftar',
                        'tb_gelombang_wisuda.nama AS gelombangWisuda',
                        'tb_gelombang_wisuda.waktu_pelaksanaan AS pelaksanaanWisuda',
                        'gelombang_yudisium.nama AS gelombangYudisium',
                        'tb_pembayaran_wisuda.status AS statusPembayaran',
                        'tb_pembayaran_wisuda.bukti AS buktiPembayaran'
                    ])
                    ->where('tb_daftar_wisudawan.status', '=', 1)
                    ->leftJoin('mahasiswa', 'tb_daftar_wisudawan.nim', '=', 'mahasiswa.nim')
                    ->leftJoin('tb_yudisium', 'mahasiswa.nim', '=', 'tb_yudisium.nim')
                    ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
                    ->leftJoin('tb_gelombang_wisuda', 'tb_daftar_wisudawan.id_gelombang_wisuda', '=', 'tb_gelombang_wisuda.id')
                    ->leftJoin('tb_pembayaran_wisuda', 'tb_daftar_wisudawan.nim', '=', 'tb_pembayaran_wisuda.nim');


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

                $pendaftar = $query->where('mahasiswa.nim', 'LIKE', "%{$search}%")
                    ->orWhere('mahasiswa.nama', 'LIKE', "%{$search}%")
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


                $totalFiltered = $query->where('mahasiswa.nim', 'LIKE', "%{$search}%")
                    ->orWhere('mahasiswa.nama', 'LIKE', "%{$search}%")
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

                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nim'] = $row->nim ;
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
        try {
            foreach ($request->nim as $nim) {
                $mhs = Mahasiswa::select([
                    'mahasiswa.nim',
                    'mahasiswa.nama',
                    'mahasiswa.angkatan',
                    'mahasiswa.jk',
                    'mahasiswa.hp',
                    'mahasiswa.email',
                    'gelombang_yudisium.tanggal_pengesahan AS tahunLulus',
                    'program_studi.jenjang',
                    'program_studi.nama_prodi AS prodi',
                    'pengajuan_judul_skripsi.judul AS judulSkripsi',
                ])
                ->where('mahasiswa.nim', $nim)
                ->where('pengajuan_judul_skripsi.status', 1)
                ->leftJoin('program_studi', 'mahasiswa.id_program_studi', '=', 'program_studi.id')
                ->leftJoin('tb_yudisium', 'mahasiswa.nim', '=', 'tb_yudisium.nim')
                ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
                ->leftJoin('master_skripsi', 'mahasiswa.nim', '=', 'master_skripsi.nim')
                ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
                ->first();

                Alumni::create([    
                    'nim' => $mhs->nim,
                    'nama' => $mhs->nama,
                    'jenjang' => $mhs->jenjang,
                    'angkatan' => $mhs->angkatan,
                    'tahun_lulus' => !empty($mhs->tahunLulus) ? date('Y', strtotime($mhs->tahunLulus)) : null,
                    'jenis_kelamin' => $mhs->jk,
                    'no_hp' => $mhs->hp,
                    'email_pribadi' => $mhs->email,
                    'prodi' => $mhs->prodi,
                    'judul_skripsi' => $mhs->judulSkripsi
                ]);

                DaftarWisudawan::where('nim', $mhs->nim)->delete();
                $mhs->delete();
            }
            return response()->json(['message' => 'Data alumni berhasil ditambahkan', 'code' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan data alumni', 'error' => $e->getMessage()], 500);
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
