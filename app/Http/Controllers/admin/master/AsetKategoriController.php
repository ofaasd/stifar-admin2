<?php

namespace App\Http\Controllers\admin\master;
use App\Models\Prodi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KategoriAset;

class AsetKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nama'];
    public function index(Request $request)
    {
        $kategoriAset = KategoriAset::all();
        if (empty($request->input('length'))) {
            $title = "kategori-aset";
            $title2 = "Kategori Aset";
            $indexed = $this->indexed;
            return view('admin.master.kategori_aset.index', compact('title', 'title2', 'kategoriAset', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'nama',
            ];

            $totalData = KategoriAset::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $kategori = KategoriAset::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $kategori = KategoriAset::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = KategoriAset::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($kategori)) {
                foreach ($kategori as $index => $row) {
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

            $save = KategoriAset::updateOrCreate(
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = KategoriAset::find($id);

        if ($kategori) {
            return response()->json($kategori); // Kembalikan objek KategoriAset langsung
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori Aset not found',
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
        $kategori = KategoriAset::where('id', $id)->delete();
    }
}
