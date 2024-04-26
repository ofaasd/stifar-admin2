<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Waktu;

class WaktuController extends Controller
{
    public $indexed = ['', 'id', 'nama_sesi', 'waktu_mulai', 'waktu_selesai', 'jml_sks', 'status'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Waktu";
            $indexed = $this->indexed;
            return view('admin.master.waktu.index', compact('title','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nama_sesi',
                3 => 'waktu_mulai',
                4 => 'waktu_selesai',
                5 => 'jml_sks',
                6 => 'status',
            ];

            $search = [];

            $totalData = Waktu::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $waktu = Waktu::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $waktu = Waktu::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama_sesi', 'LIKE', "%{$search}%")
                    ->orWhere('waktu_mulai', 'LIKE', "%{$search}%")
                    ->orWhere('waktu_selesai', 'LIKE', "%{$search}%")
                    ->orWhere('jml_sks', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Waktu::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama_sesi', 'LIKE', "%{$search}%")
                ->orWhere('waktu_mulai', 'LIKE', "%{$search}%")
                ->orWhere('waktu_selesai', 'LIKE', "%{$search}%")
                ->orWhere('jml_sks', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($waktu)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($waktu as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nama_sesi'] = $row->nama_sesi;
                    $nestedData['waktu_mulai'] = $row->waktu_mulai;
                    $nestedData['waktu_selesai'] = $row->waktu_selesai;
                    $nestedData['jml_sks'] = $row->jml_sks;
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
            $waktu = Waktu::updateOrCreate(
                ['id' => $id],
                [
                    'nama_sesi' => $request->nama_sesi, 
                    'waktu_mulai' => $request->waktu_mulai, 
                    'waktu_selesai' => $request->waktu_selesai, 
                    'jml_sks' => $request->jml_sks, 
                    'status' => $request->status
                ]
            );

            return response()->json('Updated');
        } else {
            $waktu = Waktu::updateOrCreate(
                ['id' => $id],
                [
                    'nama_sesi' => $request->nama_sesi, 
                    'waktu_mulai' => $request->waktu_mulai, 
                    'waktu_selesai' => $request->waktu_selesai, 
                    'jml_sks' => $request->jml_sks, 
                    'status' => $request->status
                ]
            );
            if ($waktu) {
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

        $waktu = Waktu::where($where)->first();

        return response()->json($waktu);
    }
    public function destroy(string $id)
    {
        //
        $waktu = Waktu::where('id', $id)->delete();
    }
}
