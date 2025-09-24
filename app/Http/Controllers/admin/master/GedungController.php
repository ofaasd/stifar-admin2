<?php

namespace App\Http\Controllers\admin\master;

use App\Models\MasterGedung;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GedungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'kode', 'nama'];
    public function index(Request $request)
    {
        $masterGedung = MasterGedung::all();
        if (empty($request->input('length'))) {
            $title = "gedung";
            $title2 = "Master Gedung";
            $indexed = $this->indexed;
            return view('admin.master.gedung.index', compact('title', 'title2', 'masterGedung', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'kode',
                2 => 'nama',
            ];

            $totalData = MasterGedung::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $gedung = MasterGedung::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $gedung = MasterGedung::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterGedung::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($gedung)) {
                foreach ($gedung as $index => $row) {
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
            'kode' => 'required',
            'nama' => 'string|required',
            'id' => 'nullable',
        ]);

        try {
            $id = $validatedData['id'];

            $save = MasterGedung::updateOrCreate(
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
    public function show(MasterGedung $masterGedung)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gedung = MasterGedung::find($id);

        if ($gedung) {
            return response()->json($gedung);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset Gedung not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterGedung $masterGedung)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gedung = MasterGedung::where('id', $id)->delete();
    }
}
