<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Prodi;
use App\Models\Alumni;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Alumni::where('id', $id)->delete();
    }
}
