<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MerkKendaraan;

use Illuminate\Validation\Rule;


class MerkKendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'kode', 'nama'];
    public function index(Request $request)
    {
        $merkKendaraan = MerkKendaraan::all();

        if (empty($request->input('length'))) {
            $title = "merk-kendaraan";
            $title2 = "Master Merk Kendaraan";
            $indexed = $this->indexed;
            return view('admin.master.merk_kendaraan.index', compact('title', 'title2', 'merkKendaraan', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'kode',
                3 => 'nama',
            ];

            $totalData = MerkKendaraan::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $getData = MerkKendaraan::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $getData = MerkKendaraan::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MerkKendaraan::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($getData)) {
                foreach ($getData as $index => $row) {
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
        try {
            $id = $request->id;

            $validatedData = $request->validate([
                'nama'  => 'string|required',
                'kode'      => $id
                                    ? ['required', 'string', Rule::unique('master_merk_kendaraan', 'kode')->ignore($id)]
                                    : 'required|string|unique:master_merk_kendaraan,kode',
            ]);

            $save = MerkKendaraan::updateOrCreate(
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
                'message' => 'Failed to save Merk Aset',
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
        $data = MerkKendaraan::find($id);

        if ($data) {
            return response()->json($data);
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
        $data = MerkKendaraan::where('id', $id)->delete();
    }
}
