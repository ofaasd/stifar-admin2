<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JenisRuang;
use App\Models\Lantai;
use Illuminate\Http\Request;
use App\Models\MasterRuang;

class RuangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nama_ruang', 'nama_lantai', 'jenis_ruang', 'kapasitas', 'luas'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Ruang";
            $indexed = $this->indexed;
            $lantais = Lantai::all();
            $jenisRuangs = JenisRuang::all();
            return view('admin.master.ruang.index', compact('title','indexed', 'jenisRuangs', 'lantais'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nama_ruang',
                3 => 'nama_lantai',
                4 => 'jenis_ruang',
                5 => 'kapasitas',
                6 => 'luas',
            ];

            $search = [];

            $totalData = MasterRuang::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $ruang = MasterRuang::select('master_ruang.*', 'master_lantai.lantai as nama_lantai', 'master_jenis_ruang.nama as jenis_ruang')
                ->leftJoin('master_lantai', 'master_lantai.id', '=', 'master_ruang.lantai_id')
                ->leftJoin('master_jenis_ruang', 'master_jenis_ruang.id', '=', 'master_ruang.jenis_id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            } else {
                $search = $request->input('search.value');

                $ruang = MasterRuang::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama_ruang', 'LIKE', "%{$search}%")
                    ->orWhere('kapasitas', 'LIKE', "%{$search}%")
                    ->orWhere('nama_lantai', 'LIKE', "%{$search}%")
                    ->orWhere('jenis_ruang', 'LIKE', "%{$search}%")
                    ->orWhere('luas', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterRuang::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama_ruang', 'LIKE', "%{$search}%")
                ->orWhere('kapasitas', 'LIKE', "%{$search}%")
                ->orWhere('nama_lantai', 'LIKE', "%{$search}%")
                ->orWhere('jenis_ruang', 'LIKE', "%{$search}%")
                ->orWhere('luas', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($ruang)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($ruang as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nama_ruang'] = $row->nama_ruang;
                    $nestedData['nama_lantai'] = $row->nama_lantai;
                    $nestedData['jenis_ruang'] = $row->jenis_ruang;
                    $nestedData['kapasitas'] = $row->kapasitas;
                    $nestedData['luas'] = $row->luas;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $id = $request->id;

        if ($id) {
            // update the value
            $ruang = MasterRuang::updateOrCreate(
                ['id' => $id],
                [
                    'nama_ruang' => $request->nama_ruang, 
                    'kapasitas' => $request->kapasitas, 
                    'lantai_id' => $request->lantai, 
                    'jenis_id' => $request->jenis_ruang, 
                    'luas' => $request->luas, 
                    'log_date'=>date('Y-m-d H:i:s')
                ]
            );

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            //$userEmail = User::where('email', $request->email)->first();

            $ruang = MasterRuang::updateOrCreate(
                ['id' => $id],
                [
                    'nama_ruang' => $request->nama_ruang, 
                    'kapasitas' => $request->kapasitas, 
                    'lantai_id' => $request->lantai, 
                    'jenis_id' => $request->jenis_ruang, 
                    'luas' => $request->luas, 
                    'log_date'=>date('Y-m-d H:i:s')
                ]
            );
            if ($ruang) {
                // user created
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
            }
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
        //
        $where = ['id' => $id];

        $ruang = MasterRuang::where($where)->first();

        return response()->json($ruang);
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
        //
        $ruang = MasterRuang::where('id', $id)->delete();
    }
}
