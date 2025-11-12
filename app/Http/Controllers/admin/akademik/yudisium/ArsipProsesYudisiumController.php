<?php

namespace App\Http\Controllers\admin\akademik\yudisium;

use Carbon\Carbon;
use App\Models\Mahasiswa;
use App\Models\TbYudisium;
use Illuminate\Http\Request;
use App\Models\GelombangYudisium;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\master_nilai;
use App\Models\TbYudisiumArchive;

class ArsipProsesYudisiumController extends Controller
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
    * menampilkan halaman dan data arsip yudisium.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public $indexed = ['', 'id', 'nim', 'nilai', 'nilai2', 'gelombang'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Proses Yudisium Arsip";
            $title2 = "proses-arsip"; 
            $data = TbYudisiumArchive::all();
            $indexed = $this->indexed;
            $nimMatkulSkripsi = master_nilai::join('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
                    ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->where('b.nama_matkul', 'like', '%skripsi%')
                    ->whereNotNull('master_nilai.nakhir')
                    ->pluck('nim');

            $nimSudahTerdaftarYudisium = TbYudisiumArchive::whereIn('nim', $nimMatkulSkripsi)->pluck('nim');

            $mhs = Mahasiswa::select([  
                    'mahasiswa.id',
                    'mahasiswa.nama',
                    'mahasiswa.nim',
                    'mahasiswa.foto_mhs',
                ])
                ->whereIn('mahasiswa.nim', $nimMatkulSkripsi)
                ->whereNotIn('mahasiswa.nim', $nimSudahTerdaftarYudisium)
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

            $gelombang = GelombangYudisium::all();
            $isArsip = true;
            return view('admin.akademik.yudisium.proses.index', compact('title', 'title2', 'data','indexed', 'mhs', 'gelombang', 'isArsip'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nilai',
                4 => 'nilai2',
                5 => 'gelombang',
            ];

            $search = [];

            $totalData = TbYudisiumArchive::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $query = TbYudisiumArchive::select([
                        'tb_yudisium_archive.*',
                        'gelombang_yudisium.periode as gelombang',
                        'gelombang_yudisium.nama as namaGelombang',
                        'gelombang_yudisium.tanggal_pengesahan as tanggalPengesahan',
                        'tb_alumni.nama AS namaMahasiswa',
                        'tb_alumni.foto AS fotoYudisium'
                    ])
                    ->leftJoin('tb_alumni', 'tb_yudisium_archive.nim', '=', 'tb_alumni.nim')
                    ->leftJoin('gelombang_yudisium', 'tb_yudisium_archive.id_gelombang_yudisium', '=', 'gelombang_yudisium.id');

            if (empty($request->input('search.value'))) {
                $proses = $query
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

                $proses = $query
                    ->where('tb_yudisium_archive.nim', 'LIKE', "%{$search}%")
                    ->orWhere('gelombang_yudisium.periode', 'LIKE', "%{$search}%")
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

                $totalFiltered = $query
                    ->where('tb_yudisium_archive.nim', 'LIKE', "%{$search}%")
                    ->orWhere('gelombang_yudisium.periode', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];

            if (!empty($proses)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($proses as $row) {

                    $teksGelombang = $row->gelombang . " | " . $row->namaGelombang;
                    if ($row->tanggalPengesahan) {
                        $teksGelombang .= ' <i class="bi bi-check-circle-fill text-success"></i>';
                    }

                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nim'] = $row->nim;
                    $nestedData['nilai'] = $row->totalSks . " | " . $row->ipk ;
                    $nestedData['nilai2'] = $row->totalD . " | " . $row->totalE ;
                    $nestedData['gelombang'] = $teksGelombang;
                    $nestedData['nimEnkripsi'] = $row->nimEnkripsi;
                    $nestedData['namaMahasiswa'] = $row->namaMahasiswa;
                    $nestedData['fotoYudisium'] = $row->fotoYudisium;
                    $nestedData['tanggalPengesahan'] = $row->tanggalPengesahan ? \Carbon\Carbon::parse($row->tanggalPengesahan)->translatedFormat('d F Y') : null;
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
    * membuat mahasiswa jadi yudisium.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function store(Request $request)
    {
        $id = $request->id;

        try {
            $request->validate([
                'gelombang' => 'required',
            ]);

            if ($id) {
                $save = TbYudisiumArchive::updateOrCreate(
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
                    $created = TbYudisiumArchive::updateOrCreate(
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
    * menampilkan data spesifik data arsip yudisium.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function edit(string $id)
    {
        try {
            $where = ['id' => $id];
            $data = TbYudisiumArchive::where($where)->first();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch data', 'error' => $e->getMessage()], 500);
        }
    }
}