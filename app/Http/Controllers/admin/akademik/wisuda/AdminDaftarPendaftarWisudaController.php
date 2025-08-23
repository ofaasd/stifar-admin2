<?php

namespace App\Http\Controllers\admin\akademik\wisuda;

use Illuminate\Http\Request;
use App\Models\DaftarWisudawan;
use App\Models\TbGelombangWisuda;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\MahasiswaBerkasPendukung;
use App\Models\TbPembayaranWisuda;

class AdminDaftarPendaftarWisudaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nim', 'wisuda', 'yudisium', 'berkas', 'status_pembayaran'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Pendaftar Wisuda";
            $title2 = "daftar-pendaftar-wisuda"; 

            $mhs = Mahasiswa::where('is_yudisium', 1)
                ->whereNotIn('nim', DaftarWisudawan::pluck('nim'))
                ->get();
            $gelombang = TbGelombangWisuda::all();
            $indexed = $this->indexed;
            return view('admin.akademik.wisuda.daftar-pendaftar-wisuda.index', compact('title', 'title2','indexed', 'gelombang', 'mhs'));
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
                        'gelombang_yudisium.nama AS gelombangYudisium',
                        'tb_pembayaran_wisuda.status AS statusPembayaran',
                        'tb_pembayaran_wisuda.bukti AS buktiPembayaran'
                    ])
                    ->where('tb_daftar_wisudawan.status', '=', 0)
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
                    if ($row->statusPembayaran == 'Belum diverifikasi' && $row->buktiPembayaran) {
                        $rowStatusPembayaran = '<span class="badge bg-warning">' . $row->statusPembayaran .
                            ' <a href="' . asset($tujuan_upload . '/' . $row->buktiPembayaran) . '" target="_blank" class="ms-2">Lihat Bukti</a></span>';
                    }else if($row->statusPembayaran == 'Sudah diverifikasi'){
                        $rowStatusPembayaran = '<span class="badge bg-success">' . $row->statusPembayaran . '</span>';
                    } else {
                        $rowStatusPembayaran = '<span class="badge ' .
                            ($row->statusPembayaran == 'Belum upload bukti' ? 'bg-secondary' : ($row->statusPembayaran == 'Belum diverifikasi' ? 'bg-warning text-dark' : 'bg-success')) . '">' .
                            $row->statusPembayaran . '</span>';
                    }
                    
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nim'] = $row->nim ;
                    $nestedData['nama'] = $row->nama ;
                    $nestedData['photo'] = $row->fotoMhs ;
                    $nestedData['wisuda'] = $row->gelombangWisuda;
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
        $id = $request->id;

        try {
            $request->validate([
                'gelombang_id' => 'required',
                'nim' => 'required',
            ]);

            if ($id) {
                $save = DaftarWisudawan::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_gelombang_wisuda' => $request->gelombang_id,
                        'nim' => $request->nim,
                        'status' => 0
                    ]
                );

                // user updated
                return response()->json('Updated', 200);
            } else {
                $save = DaftarWisudawan::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_gelombang_wisuda' => $request->gelombang_id,
                        'nim' => $request->nim,
                        'status' => 0
                    ]
                );

            if ($save) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Daftar Wisudawan');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function acc(Request $request, string $id)
    {
        try {
            $data = DaftarWisudawan::where('id', $id)->first();
            $pembayaran = TbPembayaranWisuda::where('nim', $data->nim)->first();
            activity()
            ->performedOn($data)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data_wisudawan'  => $data
                ])
                ->log('acc-wisudawan');
                
            $pembayaran->update(['status' => 1]);
            $data->update(['status' => 1]);
            return response()->json(['message' => 'Accepted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to accept', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = DaftarWisudawan::where('id', $id)->first();

            activity()
            ->performedOn($data)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data_wisudawan'  => $data
                ])
            ->log('delete-wisudawan');

            $data->delete();
            return response()->json(['message' => 'Rejected successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to reject', 'error' => $e->getMessage()], 500);
        }
    }
}
