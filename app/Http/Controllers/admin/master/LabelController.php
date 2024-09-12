<?php

namespace App\Http\Controllers\admin\master;

use App\Models\Label;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nama'];
    public function index(Request $request)
    {
        $label = Label::all();
        if (empty($request->input('length'))) {
            $title = "aset-label";
            $title2 = "Label";
            $indexed = $this->indexed;
            return view('admin.master.label.index', compact('title', 'title2', 'label', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'nama',
            ];

            $totalData = Label::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $label = Label::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $label = Label::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Label::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($label)) {
                foreach ($label as $index => $row) {
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
            $nama = strtolower($validatedData['nama']);

            $save = Label::updateOrCreate(
                ['id' => $id],
                [
                    'nama' => $nama,
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
    public function show(Label $label)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $label = Label::find($id);

        if ($label) {
            return response()->json($label);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset Label not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLabelRequest $request, Label $label)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $label = Label::where('id', $id)->delete();
    }
}
