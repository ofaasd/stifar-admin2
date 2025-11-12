<?php

namespace App\Http\Controllers\admin;

use App\Models\Lantai;
use App\Models\AsetBarang;
use App\Models\MasterRuang;
use Illuminate\Http\Request;
use App\Models\MasterJenisRuang;
use App\Models\AsetGedungBangunan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class RuangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'kodeGedung', 'kodeJenis', 'nama_ruang', 'kapasitas', 'lantai' , 'luas'];
    public function index(Request $request)
    {
        $query = MasterRuang::select(
            'master_ruang.*',
            'master_ruang.id AS idRuang',
            'master_lantai.lantai as lantai',
            'master_jenis_ruang.kode as kodeJenis',
            'aset_gedung_bangunan.kode AS kodeGedung'
        )
        ->leftJoin('master_lantai', 'master_lantai.id', '=', 'master_ruang.lantai_id')
        ->leftJoin('master_jenis_ruang', 'master_jenis_ruang.kode', '=', 'master_ruang.kode_jenis')
        ->leftJoin('aset_gedung_bangunan', 'aset_gedung_bangunan.kode', '=', 'master_ruang.kode_gedung')
        ->groupBy('master_ruang.id');

        $ruang = $query->get()
        ->map(function ($item) {
            $item->idEnkripsi = Crypt::encryptString($item->idRuang . "stifar");
            return $item;
        });

        if (empty($request->input('length'))) {
            $title = "Ruang";
            $indexed = $this->indexed;
            $asetGedung = AsetGedungBangunan::all();
            $asetJenisRuang = MasterJenisRuang::all();
            $asetLantai = Lantai::all();
            return view('admin.master.ruang.index', compact('title','indexed', 'asetGedung', 'asetJenisRuang', 'asetLantai'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kodeGedung',
                3 => 'kodeJenis',
                4 => 'nama_ruang',
                5 => 'kapasitas',
                6 => 'lantai',
                7 => 'luas',
            ];

            $search = [];

            $totalData = MasterRuang::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $ruang = $query->get()
                ->map(function ($item) {
                    $item->idEnkripsi = Crypt::encryptString($item->idRuang . "stifar");
                    return $item;
                });
            } else {
                $search = $request->input('search.value');

                $ruang = MasterRuang::select('master_ruang.*', 'master_ruang.id AS idRuang', 'master_lantai.lantai as lantai', 'master_jenis_ruang.kode as kodeJenis', 'aset_gedung_bangunan.kode AS kodeGedung')
                    ->leftJoin('master_lantai', 'master_lantai.id', '=', 'master_ruang.lantai_id')
                    ->leftJoin('master_jenis_ruang', 'master_jenis_ruang.kode', '=', 'master_ruang.kode_jenis')
                    ->leftJoin('aset_gedung_bangunan', 'aset_gedung_bangunan.kode', '=', 'master_ruang.kode_gedung')
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kodeGedung', 'LIKE', "%{$search}%")
                    ->orWhere('nama_ruang', 'LIKE', "%{$search}%")
                    ->orWhere('kodeJenis', 'LIKE', "%{$search}%")
                    ->orWhere('kapasitas', 'LIKE', "%{$search}%")
                    ->orWhere('lantai', 'LIKE', "%{$search}%")
                    ->orWhere('luas', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get()->map(function ($item) {
                        $item->idEnkripsi = Crypt::encryptString($item->idRuang . "stifar");
                        return $item;
                    });

                $ruang = MasterRuang::select('master_ruang.*', 'master_ruang.id AS idRuang', 'master_lantai.lantai as lantai', 'master_jenis_ruang.kode as kodeJenis', 'aset_gedung_bangunan.kode AS kodeGedung')
                    ->leftJoin('master_lantai', 'master_lantai.id', '=', 'master_ruang.lantai_id')
                    ->leftJoin('master_jenis_ruang', 'master_jenis_ruang.kode', '=', 'master_ruang.kode_jenis')
                    ->leftJoin('aset_gedung_bangunan', 'aset_gedung_bangunan.kode', '=', 'master_ruang.kode_gedung')
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kodeGedung', 'LIKE', "%{$search}%")
                    ->orWhere('nama_ruang', 'LIKE', "%{$search}%")
                    ->orWhere('kodeJenis', 'LIKE', "%{$search}%")
                    ->orWhere('kapasitas', 'LIKE', "%{$search}%")
                    ->orWhere('lantai', 'LIKE', "%{$search}%")
                    ->orWhere('luas', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];

            if (!empty($ruang)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($ruang as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['idEnkripsi'] = $row->idEnkripsi;
                    $nestedData['kodeGedung'] = $row->kodeGedung;
                    $nestedData['kodeJenis'] = $row->kodeJenis;
                    $nestedData['nama_ruang'] = $row->nama_ruang;
                    $nestedData['lantai'] = $row->lantai;
                    $nestedData['kapasitas'] = $row->kapasitas;
                    $nestedData['luas'] = $row->luas;
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
        $id = $request->id;

        if ($id) {
            // update the value
            $ruang = MasterRuang::updateOrCreate(
                ['id' => $id],
                [
                    'kode_gedung' => $request->kodeGedung, 
                    'nama_ruang' => $request->namaRuang, 
                    'kapasitas' => $request->kapasitas, 
                    'lantai_id' => $request->lantai, 
                    'kode_jenis' => $request->kodeJenis, 
                    'luas' => $request->luas, 
                    'log_date'=>date('Y-m-d H:i:s')
                ]
            );

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            //$userEmail = User::where('email', $request->email)->first();

            $ruang = MasterRuang::updateOrCreate(
                ['id' => $id],
                [
                    'kode_gedung' => $request->kodeGedung, 
                    'nama_ruang' => $request->namaRuang, 
                    'kapasitas' => $request->kapasitas, 
                    'lantai_id' => $request->lantai, 
                    'kode_jenis' => $request->kodeJenis, 
                    'luas' => $request->luas, 
                    'log_date'=>date('Y-m-d H:i:s')
                ]
            );
            if ($ruang) {
                // user created
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $ruang = MasterRuang::where(['id' => $id])->first();
        $barang = AsetBarang::where(['kode_ruang'   => $ruang->kode])->get();

        $data = [
            'title' => 'Detail Ruang ' . $ruang->nama_ruang,
            'ruang' => $ruang,
            'barang' => $barang,
        ];

        return view('admin.master.ruang.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $where = ['id' => $id];

        $ruang = MasterRuang::where($where)->first();

        return response()->json($ruang);
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
        $ruang = MasterRuang::where('id', $id)->delete();
    }
}
