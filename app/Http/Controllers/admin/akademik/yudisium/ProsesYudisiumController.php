<?php

namespace App\Http\Controllers\admin\akademik\yudisium;

use App\helpers;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\TbYudisium;
use App\Models\master_nilai;
use Illuminate\Http\Request;
use App\Models\DaftarWisudawan;
use App\Models\GelombangYudisium;
use App\Models\JabatanStruktural;
use App\Models\TbGelombangWisuda;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class ProsesYudisiumController extends Controller
{
    protected $helpers;
    public function __construct()
    {
        $this->helpers = new helpers();
    }
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
    * menampilkan data peserta yudisium.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public $indexed = ['', 'id', 'nim', 'nilai', 'nilai2', 'gelombang'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Proses Yudisium";
            $title2 = "proses"; 
            $data = TbYudisium::all();
            $indexed = $this->indexed;
            $nimMatkulSkripsi = master_nilai::join('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
                    ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->where('b.nama_matkul', 'like', '%skripsi%')
                    ->whereNotNull('master_nilai.nakhir')
                    ->pluck('nim');

            $nimSudahTerdaftarYudisium = TbYudisium::whereIn('nim', $nimMatkulSkripsi)->pluck('nim');

            $mhs = Mahasiswa::select([  
                    'mahasiswa.id',
                    'mahasiswa.nama',
                    'mahasiswa.nim',
                    'mahasiswa.foto_mhs',
                    'mahasiswa.id_program_studi',
                ])
                ->whereIn('mahasiswa.nim', $nimMatkulSkripsi)
                ->whereNotIn('mahasiswa.nim', $nimSudahTerdaftarYudisium)
                ->get()
                ->map(function ($item) {
                    $item->nimEnkripsi = Crypt::encryptString($item->nim . "stifar");
                    return $item;
                });

            foreach ($mhs as $item) {   
                $getNilai = $this->helpers->getDaftarNilaiMhs($item->nim);

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

            $gelombang = GelombangYudisium::select([
                'gelombang_yudisium.*',
                'program_studi.nama_prodi'
            ])
            ->leftJoin('program_studi', 'gelombang_yudisium.id_prodi', '=', 'program_studi.id')
            ->orderBy('created_at', 'desc')
            ->get();

            $prodi = Prodi::all();

            return view('admin.akademik.yudisium.proses.index', compact('title', 'title2', 'data','indexed', 'mhs', 'gelombang', 'prodi'));
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

            $limit = $request->input('length') ?? 0;
            $start = $request->input('start') ?? 0;
            $order = 'tb_yudisium.created_at';
            $dir = $request->input('order.0.dir') ?? 'desc';

            $query = TbYudisium::select([
                        'tb_yudisium.*',
                        'gelombang_yudisium.periode as gelombang',
                        'gelombang_yudisium.nama as namaGelombang',
                        'gelombang_yudisium.tanggal_pengesahan as tanggalPengesahan',
                        'mahasiswa.nama AS namaMahasiswa',
                        'mahasiswa.foto_yudisium AS fotoYudisium',
                        'mahasiswa.id_program_studi'
                    ]) 
                    ->leftJoin('mahasiswa', 'tb_yudisium.nim', '=', 'mahasiswa.nim')
                    ->leftJoin('gelombang_yudisium', 'tb_yudisium.id_gelombang_yudisium', '=', 'gelombang_yudisium.id');

            $daftarWisudawan = DaftarWisudawan::get();
            $gelombangWisuda = TbGelombangWisuda::get();

            $filterGelombang = $request->input('filtergelombang') ?? null;
            $filterProdi = $request->input('filterprodi') ?? null;

            if (empty($request->input('search.value')) || !empty($filterGelombang) || !empty($filterProdi)) {
                if ($filterGelombang != '') {
                    $query->where('tb_yudisium.id_gelombang_yudisium', $filterGelombang);
                }

                if ($filterProdi != '') {
                    $query->where('mahasiswa.id_program_studi', $filterProdi);
                }

                $proses = $query
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get()
                    ->map(function ($item) use ($daftarWisudawan, $gelombangWisuda){
                        $item->nimEnkripsi = Crypt::encryptString($item->nim . "stifar");
                        $wisudawan = $daftarWisudawan->where('nim', $item->nim)->where('status', 1)->first();
                        $tanggalPelaksanaan = null;
                        if ($wisudawan && isset($wisudawan->id_gelombang_wisuda)) {
                            $gelombang = $gelombangWisuda->where('id', $wisudawan->id_gelombang_wisuda)->first();
                            $tanggalPelaksanaan = $gelombang ? $gelombang->waktu_pelaksanaan : null;
                        }
                        $item->tanggalDiberikan = $tanggalPelaksanaan ? \Carbon\Carbon::parse($tanggalPelaksanaan)->format('Y-m-d') : '-';
                        return $item;
                    });

                foreach ($proses as $item) {
                    $getNilai = $this->helpers->getDaftarNilaiMhs($item->nim);

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
                    ->where('tb_yudisium.nim', 'LIKE', "%{$search}%")
                    ->orWhere('tb_yudisium.id_gelombang_yudisium', $filterGelombang)
                    ->orWhere('mahasiswa.id_program_studi', $filterProdi)
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
                    $getNilai = $this->helpers->getDaftarNilaiMhs($item->nim);

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
                    ->where('tb_yudisium.nim', 'LIKE', "%{$search}%")
                    ->orWhere('tb_yudisium.id_gelombang_yudisium', $filterGelombang)
                    ->orWhere('mahasiswa.id_program_studi', $filterProdi)
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
                    $nestedData['tanggalDiberikan'] = $row->tanggalDiberikan;
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
    * menyimpan mahasiswa sebagai yudisium.
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

            $mahasiswa = Mahasiswa::select([
                'pegawai_biodata.id AS idKaprodi'
            ])
            ->leftJoin('jabatan_struktural', 'mahasiswa.id_program_studi', '=', 'jabatan_struktural.prodi_id')
            ->leftJoin('pegawai_biodata', 'jabatan_struktural.id_pegawai', '=', 'pegawai_biodata.id')
            ->where(function($query){
                $query->where('jabatan_struktural.jabatan', 'like', '%kepala%')
                      ->orWhereNull('jabatan_struktural.jabatan');
            })
            ->where('nim', $request->nim)
            ->first();

            $jabatanStruktural = JabatanStruktural::select([
                'pegawai_biodata.id AS idKetua',
            ])
            ->where('jabatan', 'Ketua')
            ->leftJoin('pegawai_biodata', 'jabatan_struktural.id_pegawai', '=', 'pegawai_biodata.id')
            ->first();

            if ($id) {
                $save = TbYudisium::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_gelombang_yudisium' => $request->gelombang,
                        'nim' => $request->nim,
                        'id_kaprodi' => $mahasiswa->idKaprodi ?? null,
                        'id_ketua' => $jabatanStruktural->idKetua ?? null,
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
                            'id_kaprodi' => $mahasiswa->idKaprodi ?? null,
                            'id_ketua' => $jabatanStruktural->idKetua ?? null,
                        ]
                    );

                    if (!$created) {
                        $save = false;
                    }

                    $updateMhs = Mahasiswa::where('nim', $nim)->update([
                        'is_yudisium' => 1
                    ]);

                    $mhs = Mahasiswa::where('nim', $nim)->first();

                    $dataWa['no_wa'] = $mhs->hp ?? '';
                    $message = "*MYSTIFAR - Yudisium*\n\n"
                        . "Halo, " . ($mhs->nama ?? '-') . ",\n\n"
                        . "Selamat! Anda telah berhasil terdaftar sebagai peserta Yudisium pada gelombang.\n\n"
                        . "Silakan pantau informasi lebih lanjut melalui MyStifar atau hubungi bagian akademik jika ada pertanyaan.\n\n"
                        . "Terima kasih.\n\n";
                    $dataWa['pesan'] = $message;
                    
                    $pesan = helpers::send_wa($dataWa);
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
    * menampilkan data spesifik data yudisium.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
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
    * menghilangkan status mahasiswa sebagai yudisium.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
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

    /**
    * upload foto yudisium.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function storeFotoYudisium(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'foto_yudisium' => 'required|image|mimes:jpeg,jpg|max:5024',
        ]);

        try {
            $mhs = Mahasiswa::where('nim', $request->nim)->first();
            $file = $request->file('foto_yudisium');
            $filename = $mhs->nim . '-' . time() . '.' . $file->getClientOriginalExtension();
            $mhs->update([
                'foto_yudisium' => $filename,
            ]);
            
            $file->move(public_path('assets/images/mahasiswa/foto-yudisium'), $filename);

            // Save the filename to the database or perform any other necessary actions

            return response()->json(['Berhasil mengupload foto Yudisium'], 200);
        } catch (\Exception $e) {
            return response()->json(['Gagal mengupload foto Yudisium', 'error' => $e->getMessage()], 500);
        }
    }
}
