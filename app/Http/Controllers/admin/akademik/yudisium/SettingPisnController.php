<?php

namespace App\Http\Controllers\admin\akademik\yudisium;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingPisnController extends Controller
{
    /**
    * menampilkan data mahasiswa yang memiliki pisn.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public $indexed = ['', 'id', 'nim', 'pisn'];
    public function index(Request $request)
    {
        $query = Mahasiswa::where('is_yudisium', 1)
            ->where(function ($q) {
                $q->whereNull('no_pisn')
                  ->orWhere('no_pisn', '==', '');
            });

        if (empty($request->input('length'))) {
            $title = "Setting PISN";
            $title2 = "setting-pisn";
            $data = $query->get();
            $indexed = $this->indexed;

            return view('admin.akademik.yudisium.setting-pisn.index', compact('title', 'title2', 'data','indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'nama',
                3 => 'pisn',
            ];

            $search = $request->input('search.value');

            $query2 = Mahasiswa::where('is_yudisium', 1)
                ->whereNotNull('no_pisn')
                ->where('no_pisn', '!=', '');

            $totalData = $query2->count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($search)) {
                $mahasiswa = $query2->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $mahasiswa = $query2->where(function ($q) use ($search) {
                        $q->where('nim', 'LIKE', "%{$search}%")
                          ->orWhere('nama', 'LIKE', "%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = $query2->where(function ($q) use ($search) {
                        $q->where('nim', 'LIKE', "%{$search}%")
                          ->orWhere('nama', 'LIKE', "%{$search}%");
                    })->count();
            }

            $data = [];

            if (!empty($mahasiswa)) {
                $ids = $start;

                foreach ($mahasiswa as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nim_value'] = $row->nim;
                    $nestedData['nim'] = $row->nim . " | " . $row->nama ;
                    $nestedData['pisn'] = $row->no_pisn;
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
    * menyimpan pisn mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function store(Request $request)
    {
        $nim = $request->nim;

        try {
            $request->validate([
                'nim' => 'required',
                'noPisn' => 'required',
            ]);

            if ($nim) {
                $save = Mahasiswa::updateOrCreate(
                    ['nim' => $nim],
                    [
                        'no_pisn' => $request->noPisn,
                    ]
                );

                // user updated
                return response()->json('Updated', 200);
            } 
            // else {
            //     $save = Mahasiswa::updateOrCreate(
            //         ['nim' => $nim],
            //         [
            //             'no_pisn' => $request->noPisn,
            //         ]
            //     );

            // }

            // if ($save) {
            //         return response()->json('Created');
            //     } else {
            //         return response()->json('Failed Create No PISN');
            //     }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to store data', 'error' => $e->getMessage()], 500);
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
    * menampilkan spesifik pisn mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function edit(string $nim)
    {
        try {
            $where = ['nim' => $nim];
            $data = Mahasiswa::where($where)->first();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch data', 'error' => $e->getMessage()], 500);
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
    * menghapus datapisn mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function destroy(string $nim)
    {
        try {
            $data = Mahasiswa::where('nim', $nim)->first();
            $data->update([
                'no_pisn' => null
            ]);
            return response()->json(['message' => 'Deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete', 'error' => $e->getMessage()], 500);
        }
    }
}
