<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fakultas;

class FakultasController extends Controller
{
    public $indexed = ['', 'id', 'kode_fak', 'nama_fak', 'tgl_berdiri', 'no_sk', 'status'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Fakultas";
            $indexed = $this->indexed;
            return view('admin.master.fakultas.index', compact('title','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode_fak',
                3 => 'nama_fak',
                4 => 'tgl_berdiri',
                5 => 'no_sk',
                6 => 'status',
            ];

            $search = [];

            $totalData = Fakultas::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $fakultas = Fakultas::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $fakultas = Fakultas::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode_fak', 'LIKE', "%{$search}%")
                    ->orWhere('nama_fak', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_berdiri', 'LIKE', "%{$search}%")
                    ->orWhere('no_sk', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Fakultas::where('id', 'LIKE', "%{$search}%")
                ->orWhere('kode_fak', 'LIKE', "%{$search}%")
                ->orWhere('nama_fak', 'LIKE', "%{$search}%")
                ->orWhere('tgl_berdiri', 'LIKE', "%{$search}%")
                ->orWhere('no_sk', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($fakultas)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($fakultas as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['kode_fak'] = $row->kode_fak;
                    $nestedData['nama_fak'] = $row->nama_fak;
                    $nestedData['tgl_berdiri'] = $row->tgl_berdiri;
                    $nestedData['no_sk'] = $row->no_sk;
                    $nestedData['status'] = $row->status;
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

    public function store(Request $request)
    {
        //
        $id = $request->id;

        if ($id) {
            $fakultas = Fakultas::updateOrCreate(
                ['id' => $id],
                [
                    'kode_fak' => $request->kode_fak, 
                    'nama_fak' => $request->nama_fak, 
                    'tgl_berdiri' => $request->tgl_berdiri, 
                    'no_sk' => $request->no_sk, 
                    'status' => $request->status
                ]
            );

            return response()->json('Updated');
        } else {
            $fakultas = Fakultas::updateOrCreate(
                ['id' => $id],
                [
                    'kode_fak' => $request->kode_fak, 
                    'nama_fak' => $request->nama_fak, 
                    'tgl_berdiri' => $request->tgl_berdiri, 
                    'no_sk' => $request->no_sk, 
                    'status' => $request->status
                ]
            );
            if ($fakultas) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
            }
        }
    }
    public function edit(string $id)
    {
        //
        $where = ['id' => $id];

        $fakultas = Fakultas::where($where)->first();

        return response()->json($fakultas);
    }
    public function destroy(string $id)
    {
        //
        $fakultas = Fakultas::where('id', $id)->delete();
    }
}
