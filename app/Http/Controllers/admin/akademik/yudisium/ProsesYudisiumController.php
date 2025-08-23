<?php

namespace App\Http\Controllers\admin\akademik\yudisium;

use App\Models\Mahasiswa;
use App\Models\TbYudisium;
use App\Models\master_nilai;
use Illuminate\Http\Request;
use App\Models\GelombangYudisium;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class ProsesYudisiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    public $indexed = ['', 'id', 'nim', 'nilai', 'nilai2', 'gelombang'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Proses Yudisium";
            $title2 = "proses"; 
            $data = TbYudisium::all();
            $indexed = $this->indexed;
            $getNim = master_nilai::join('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
                    ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->where('b.nama_matkul', 'like', '%skripsi%')
                    ->whereNotNull('master_nilai.nakhir')
                    ->pluck('nim');

            $mhs = Mahasiswa::select([  
                    'mahasiswa.id',
                    'mahasiswa.nama',
                    'mahasiswa.nim',
                    'mahasiswa.foto_mhs',
                ])
                ->whereIn('mahasiswa.nim', $getNim)
                ->get()
                ->map(function ($item) {
                    $item->nimEnkripsi = Crypt::encryptString($item->nim . "stifar");
                    return $item;
                });

            foreach ($mhs as $item) {   
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

            $gelombang = GelombangYudisium::all();

            return view('admin.akademik.yudisium.proses.index', compact('title', 'title2', 'data','indexed', 'mhs', 'gelombang'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nilai',
                4 => 'nilai2',
                5 => 'gelombang',
            ];

            $search = [];

            $totalData = TbYudisium::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $proses = TbYudisium::select([
                        'tb_yudisium.*',
                        'gelombang_yudisium.periode as gelombang',
                        'gelombang_yudisium.nama as namaGelombang',
                        'mahasiswa.nama AS namaMahasiswa'
                    ])
                    ->leftJoin('mahasiswa', 'tb_yudisium.nim', '=', 'mahasiswa.nim')
                    ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get()
                    ->map(function ($item) {
                        $item->nimEnkripsi = Crypt::encryptString($item->nim . "stifar");
                        return $item;
                    });

                foreach ($proses as $item) {
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
                    $totalD = 0;
                    $totalE = 0;
                    foreach ($getNilai as $row) {
                        $sks = ($row->sks_teori + $row->sks_praktek);
                        $totalSks += $sks;
                        if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1)
                        {
                            $totalIps +=  ($row->sks_teori+$row->sks_praktek) * $this->kualitas[$row['nhuruf']];
                            $totalD += $row['nhuruf'] == 'D' ? 1 : 0;
                            $totalE += $row['nhuruf'] == 'E' ? 1 : 0;
                        }
                    }
                    $item->totalSks = $totalSks;
                    $item->ipk = $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;

                    $item->totalD = $totalD;
                    $item->totalE = $totalE;
                }
            } else {
                $search = $request->input('search.value');

                $proses = TbYudisium::select([
                        'tb_yudisium.*',
                        'gelombang_yudisium.periode as gelombang',
                        'gelombang_yudisium.nama as namaGelombang',
                        'mahasiswa.nama AS namaMahasiswa'
                    ])
                    ->leftJoin('mahasiswa', 'tb_yudisium.nim', '=', 'mahasiswa.nim')
                    ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
                    ->where('tb_yudisium.nim', 'LIKE', "%{$search}%")
                    ->orWhere('gelombang', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get()
                    ->map(function ($item) {
                        $item->nimEnkripsi = Crypt::encryptString($item->nim . "stifar");
                        return $item;
                    });

                foreach ($proses as $item) {
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
                    $totalD = 0;
                    $totalE = 0;
                    foreach ($getNilai as $row) {
                        $sks = ($row->sks_teori + $row->sks_praktek);
                        $totalSks += $sks;
                        if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1)
                        {
                            $totalIps +=  ($row->sks_teori+$row->sks_praktek) * $this->kualitas[$row['nhuruf']];
                            $totalD += $row['nhuruf'] == 'D' ? 1 : 0;
                            $totalE += $row['nhuruf'] == 'E' ? 1 : 0;
                        }
                    }
                    $item->totalSks = $totalSks;
                    $item->ipk = $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;
                    $item->totalD = $totalD;
                    $item->totalE = $totalE;
                }

                $totalFiltered = TbYudisium::select([
                        'tb_yudisium.*',
                        'gelombang_yudisium.nama as namaGelombang',
                        'gelombang_yudisium.periode as gelombang',
                        'gelombang_yudisium.nama as namaGelombang',
                        'mahasiswa.nama AS namaMahasiswa'
                    ])
                    ->leftJoin('mahasiswa', 'tb_yudisium.nim', '=', 'mahasiswa.nim')
                    ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id')
                    ->where('tb_yudisium.nim', 'LIKE', "%{$search}%")
                    ->orWhere('gelombang', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];

            if (!empty($proses)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($proses as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nim'] = $row->nim . " | " . $row->namaMahasiswa ;
                    $nestedData['nilai'] = $row->totalSks . " | " . $row->ipk ;
                    $nestedData['nilai2'] = $row->totalD . " | " . $row->totalE ;
                    $nestedData['gelombang'] = $row->gelombang . " | " . $row->namaGelombang;
                    $nestedData['nimEnkripsi'] = $row->nimEnkripsi;
                    $nestedData['namaMahasiswa'] = $row->namaMahasiswa;
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
                'gelombang' => 'required',
            ]);

            if ($id) {
                $save = TbYudisium::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_gelombang_yudisium' => $request->gelombang,
                        'nim' => $request->nim,
                    ]
                );

                // user updated
                return response()->json('Updated', 200);
            } else {
                $save = true;
                foreach ($request->listMahasiswa as $nim) {
                    $created = TbYudisium::updateOrCreate(
                        ['nim' => $nim, 'id_gelombang_yudisium' => $request->gelombang],
                        [
                            'id_gelombang_yudisium' => $request->gelombang,
                            'nim' => $nim,
                        ]
                    );

                    if (!$created) {
                        $save = false;
                    }

                    $updateMhs = Mahasiswa::where('nim', $nim)->update([
                        'is_yudisium' => 1
                    ]);
                }

                if ($save) {
                    return response()->json('Created');
                } else {
                    return response()->json('Failed Create Proses Yudisium');
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to store data', 'error' => $e->getMessage()], 500);
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
        try {
            $where = ['id' => $id];
            $data = TbYudisium::where($where)->first();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch data', 'error' => $e->getMessage()], 500);
        }
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
        try {
            $data = TbYudisium::where('id', $id)->first();
            Mahasiswa::where('nim', $data->nim)->update(['is_yudisium' => 0]);
            $data->delete();
            return response()->json(['message' => 'Deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete', 'error' => $e->getMessage()], 500);
        }
    }
}
