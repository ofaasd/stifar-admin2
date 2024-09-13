<?php

namespace App\Http\Controllers\admin\master;

use App\Models\Lantai;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LantaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'lantai'];
    public function index(Request $request)
    {
        $lantai = Lantai::all();
        if (empty($request->input('length'))) {
            $title = "aset-lantai";
            $title2 = "Lantai";
            $indexed = $this->indexed;
            return view('admin.master.lantai.index', compact('title', 'title2', 'lantai', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'lantai',
            ];

            $totalData = Lantai::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $lantai = Lantai::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $lantai = Lantai::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('lantai', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Lantai::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('lantai', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($lantai)) {
                foreach ($lantai as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['lantai'] = $row->lantai;
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
            'lantai' => 'string|required',
            'id' => 'nullable',
        ]);

        try {
            $id = $validatedData['id'];

            $save = Lantai::updateOrCreate(
                ['id' => $id],
                [
                    'lantai' => $validatedData['lantai'],
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
    public function show(Lantai $lantai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lantai = Lantai::find($id);

        if ($lantai) {
            return response()->json($lantai);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset Lantai not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lantai $lantai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lantai = Lantai::where('id', $id)->delete();
    }
}
