<?php

namespace App\Http\Controllers\admin\master\akademik;

use App\Http\Controllers\Controller;
use App\Models\MasterBidangMinat;
use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Http\Request;

class BidangMinatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nama'];
    public function index(Request $request)
    {
        $data = MasterBidangMinat::all();
        $prodi = Prodi::all();
        if (empty($request->input('length'))) {
            $title = "bidang-minat";
            $title2 = "Master Bidang Minat";
            $indexed = $this->indexed;
            return view('admin.master.akademik.bidang-minat.index', compact('title', 'title2', 'data', 'prodi', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                // 2 => 'prodi',
                2 => 'nama'
            ];

            $totalData = MasterBidangMinat::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $bidangMinat = MasterBidangMinat::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $bidangMinat = MasterBidangMinat::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterBidangMinat::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }


            $data = [];
            if (!empty($bidangMinat)) {
                foreach ($bidangMinat as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    // $nestedData['prodi'] = $prodi->where('id', $row->id_prodi)->first()->nama_prodi ?? '';
                    $nestedData['nama'] = $row->nama . ' - ' . $prodi->where('id', $row->id_prodi)->first()->nama_prodi ?? '';
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
                'idProgramStudi'  => 'int|required',
                'nama'  => 'string|required',
            ]);

            $save = MasterBidangMinat::updateOrCreate(
                ['id' => $id],
                [
                    'id_prodi' => $validatedData['idProgramStudi'],
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
                'message' => 'Failed to save Master Bidang Minat',
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
        $data = MasterBidangMinat::find($id);

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
        $data = MasterBidangMinat::where('id', $id)->delete();
    }
}
