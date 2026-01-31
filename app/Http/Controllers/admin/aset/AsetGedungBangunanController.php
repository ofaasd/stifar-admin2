<?php

namespace App\Http\Controllers\admin\aset;

use App\Http\Controllers\Controller;
use App\Models\AsetGedungBangunan;
use App\Models\AsetTanah;
use App\Models\Lantai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AsetGedungBangunanController extends Controller
{
    /**
    * menampilkan data aset gedung dan bangunan.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public $indexed = ['', 'id', 'kode_tanah', 'id_lantai', 'kode', 'nama', 'luas'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "gedung-bangunan";
            $title2 = "Aset Gedung & Bangunan";
            $indexed = $this->indexed;
            $asetTanah = AsetTanah::all();
            $masterLantai = Lantai::all();
            $aset = AsetGedungBangunan::all();
            $gedungCountPerKode = AsetGedungBangunan::select('aset_tanah.nama', DB::raw('count(*) as total'))
                ->leftJoin('aset_tanah', DB::raw('aset_gedung_bangunan.kode_tanah COLLATE utf8mb4_general_ci'), '=', DB::raw('aset_tanah.kode COLLATE utf8mb4_general_ci'))
                ->groupBy('aset_tanah.nama')
                ->get();

            $statsDitanah = $gedungCountPerKode->pluck('total', 'nama');
            $totalLuas = AsetGedungBangunan::sum('luas');
            return view('admin.aset.gedung-bangunan.index', compact('title', 'title2', 'indexed', 'asetTanah', 'masterLantai', 'aset', 'statsDitanah', 'totalLuas'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode_tanah',
                3 => 'id_lantai',
                4 => 'kode',
                5 => 'nama',
                6 => 'luas',
            ];

            $search = [];

            $totalData = AsetGedungBangunan::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $asetTanah = AsetGedungBangunan::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            } else {
                $search = $request->input('search.value');

                $asetTanah = AsetGedungBangunan::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode_tanah', 'LIKE', "%{$search}%")
                    ->orWhere('id_lantai', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('luas', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = AsetGedungBangunan::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode_tanah', 'LIKE', "%{$search}%")
                    ->orWhere('id_lantai', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('luas', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($asetTanah)) {

                foreach ($asetTanah as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['kode_tanah'] = $row->kode_tanah;
                    $nestedData['id_lantai'] = $row->id_lantai;
                    $nestedData['kode'] = $row->kode;
                    $nestedData['nama'] = $row->nama;
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
    * menyimpan data aset gedung dan bangunan.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function store(Request $request)
    {
        try {
            $id = $request->id;
            
            $validatedData = $request->validate([
                'kodeTanah' => 'required',
                'idLantai'  => 'required',
                'kode'      => $id
                                ? ['required', 'string', Rule::unique('aset_gedung_bangunan', 'kode')->ignore($id)]
                                : 'required|string|unique:aset_gedung_bangunan,kode',
                'nama'      => 'required|string',
                'luas'      => 'required|string',
            ]);


            $save = AsetGedungBangunan::updateOrCreate(
                ['id' => $id],
                [
                    'kode_tanah' => $validatedData['kodeTanah'],
                    'id_lantai' => $validatedData['idLantai'],
                    'kode' => $validatedData['kode'],
                    'nama' => $validatedData['nama'],
                    'luas' => $validatedData['luas']
                ]
            );

            if ($id) {
                return response()->json('Updated');
            } elseif ($save) {
                return response()->json('Created');
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save Kategori Aset',
                'error' => $e->getMessage(),
            ], 500);
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
    * menampilkan spesifik data aset gedung dan bangunan.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function edit(string $id)
    {
        $data = AsetGedungBangunan::where("id", $id)->first();

        if ($data) {
            return response()->json($data); // Kembalikan objek KategoriAset langsung
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gedung & Bangunan not found',
            ], 404);
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
    * menghapus data aset gedung dan bangunan.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function destroy(string $id)
    {
        $data = AsetGedungBangunan::where('id', $id)->delete();
    }
}
