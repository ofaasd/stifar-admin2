<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\Fakultas;

class ProdiController extends Controller
{
    public $indexed = ['','id', 'kode_prodi', 'kode_nim', 'jenjang', 'nama_prodi', 'tgl_pendirian', 'no_sk_pendirian'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Program Studi";
            $url = "Program-Studi";
            $indexed = $this->indexed;
            $fakultas = Fakultas::all();
            return view('admin.master.prodi.index', compact('title','indexed', 'url', 'fakultas'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode_prodi',
                3 => 'kode_nim',
                4 => 'jenjang',
                5 => 'nama_prodi',
                6 => 'tgl_pendirian',
                7 => 'no_sk_pendirian'
            ];

            $search = [];

            $totalData = Prodi::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $prodi = Prodi::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $prodi = Prodi::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode_prodi', 'LIKE', "%{$search}%")
                    ->orWhere('kode_nim', 'LIKE', "%{$search}%")
                    ->orWhere('jenjang', 'LIKE', "%{$search}%")
                    ->orWhere('nama_prodi', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_pendirian', 'LIKE', "%{$search}%")
                    ->orWhere('no_sk_pendirian', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Prodi::where('id', 'LIKE', "%{$search}%")
                ->orWhere('kode_prodi', 'LIKE', "%{$search}%")
                ->orWhere('kode_nim', 'LIKE', "%{$search}%")
                ->orWhere('jenjang', 'LIKE', "%{$search}%")
                ->orWhere('nama_prodi', 'LIKE', "%{$search}%")
                ->orWhere('tgl_pendirian', 'LIKE', "%{$search}%")
                ->orWhere('no_sk_pendirian', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($prodi)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($prodi as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['kode_prodi'] = $row->kode_prodi;
                    $nestedData['kode_nim'] = $row->kode_nim;
                    $nestedData['jenjang'] = $row->jenjang;
                    $nestedData['nama_prodi'] = $row->nama_prodi;
                    $nestedData['tgl_pendirian'] = $row->tgl_pendirian;
                    $nestedData['no_sk_pendirian'] = $row->no_sk_pendirian;
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
        $krs = $request->input('is_krs');

        if ($id) {
            // update the value
            $prodi = Prodi::updateOrCreate(
                ['id' => $id],
                [
                    'id_fakultas' => $request->id_fakultas,
                    'kode_prodi' => $request->kode_prodi,
                    'kode_nim' => $request->kode_nim,
                    'jenjang' => $request->jenjang,
                    'nama_prodi' => $request->nama_prodi,
                    'tgl_pendirian' => $request->tgl_pendirian,
                    'no_sk_pendirian' => $request->no_sk_pendirian,
                    'is_krs' => ($krs == "on") ? 1 : 0,
                ]
            );

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            //$userEmail = User::where('email', $request->email)->first();

            $prodi = Prodi::updateOrCreate(
                ['id' => $id],
                [
                    'id_fakultas' => $request->id_fakultas,
                    'kode_prodi' => $request->kode_prodi,
                    'kode_nim' => $request->kode_nim,
                    'jenjang' => $request->jenjang,
                    'nama_prodi' => $request->nama_prodi,
                    'tgl_pendirian' => $request->tgl_pendirian,
                    'no_sk_pendirian' => $request->no_sk_pendirian,
                    'is_krs' => ($krs == "on") ? 1 : 0,
                ]
            );
            if ($prodi) {
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

        $prodi = Prodi::where($where)->first();

        return response()->json($prodi);
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
        $prodi = Prodi::where('id', $id)->delete();
    }
}
