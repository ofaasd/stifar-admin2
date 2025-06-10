<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MasterJenisRuang;

use Illuminate\Validation\Rule;

class JenisRuangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'kode', 'nama'];
    public function index(Request $request)
    {
        $jenisRuang = MasterJenisRuang::all();
        if (empty($request->input('length'))) {
            $title = "jenis-ruang";
            $title2 = "Master Jenis Ruang";
            $indexed = $this->indexed;
            return view('admin.master.jenis_ruang.index', compact('title', 'title2', 'jenisRuang', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'kode',
                3 => 'nama',
            ];

            $totalData = MasterJenisRuang::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $jenisRuang = MasterJenisRuang::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $jenisRuang = MasterJenisRuang::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterJenisRuang::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($jenisRuang)) {
                foreach ($jenisRuang as $index => $row) {
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
                                    ? ['required', 'string', Rule::unique('master_jenis_ruang', 'kode')->ignore($id)]
                                    : 'required|string|unique:master_jenis_ruang,kode',
                'id'    => 'nullable',
            ]);

            $save = MasterJenisRuang::updateOrCreate(
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
    public function show(MasterJenisRuang $jenisRuang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = MasterJenisRuang::find($id);

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
    public function update(Request $request, MasterJenisRuang $jenisRuang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = MasterJenisRuang::where('id', $id)->delete();
    }
}
