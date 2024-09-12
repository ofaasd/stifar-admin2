<?php

namespace App\Http\Controllers\admin\master;

use App\Models\JenisRuang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JenisRuangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nama'];
    public function index(Request $request)
    {
        $jenisRuang = JenisRuang::all();
        if (empty($request->input('length'))) {
            $title = "aset-jenis-ruang";
            $title2 = "Jenis Ruang";
            $indexed = $this->indexed;
            return view('admin.master.jenis_ruang.index', compact('title', 'title2', 'jenisRuang', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'nama',
            ];

            $totalData = JenisRuang::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $jenisRuang = JenisRuang::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $jenisRuang = JenisRuang::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = JenisRuang::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($jenisRuang)) {
                foreach ($jenisRuang as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
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
            'nama' => 'string|required',
            'id' => 'nullable',
        ]);

        try {
            $id = $validatedData['id'];

            $save = JenisRuang::updateOrCreate(
                ['id' => $id],
                [
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
    public function show(JenisRuang $jenisRuang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenisRuang = JenisRuang::find($id);

        if ($jenisRuang) {
            return response()->json($jenisRuang);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset Jenis Ruang not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisRuang $jenisRuang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenisRuang = JenisRuang::where('id', $id)->delete();
    }
}
