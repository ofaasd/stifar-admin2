<?php

namespace App\Http\Controllers\admin\master;
use App\Http\Controllers\Controller;

use App\Models\MasterJenisKendaaran as ModelsJenisKendaaran;
use Illuminate\Http\Request;

class JenisKendaaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'kode', 'nama'];
    public function index(Request $request)
    {
        $jenisKendaraan = ModelsJenisKendaaran::all();
        if (empty($request->input('length'))) {
            $title = "jenis-kendaraan";
            $title2 = "Master Jenis Kendaraan";
            $indexed = $this->indexed;
            return view('admin.master.jenis_kendaraan.index', compact('title', 'title2', 'jenisKendaraan', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'kode',
                3 => 'nama',
            ];

            $totalData = ModelsJenisKendaaran::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $jenisKendaraan = ModelsJenisKendaaran::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $jenisKendaraan = ModelsJenisKendaaran::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = ModelsJenisKendaaran::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($jenisKendaraan)) {
                foreach ($jenisKendaraan as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['kode'] = $row->kode;
                    $nestedData['nama'] = $row->nama;
                    $data[] = $nestedData;
                }
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ]);
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
        // Validasi data
        $validatedData = $request->validate([
            'kode' => 'string|required',
            'nama' => 'string|required',
            'id' => 'nullable',
        ]);

        try {
            $id = $validatedData['id'];

            $save = ModelsJenisKendaaran::updateOrCreate(
                ['id' => $id],
                [
                    'kode' => $validatedData['kode'],
                    'nama' => $validatedData['nama'],
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenisKendaraan = ModelsJenisKendaaran::find($id);

        if ($jenisKendaraan) {
            return response()->json($jenisKendaraan);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenisKendaraan = ModelsJenisKendaaran::where('id', $id)->delete();
    }
}
