<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rumpun;

class RumpunController extends Controller
{
    public $indexed = ['', 'id', 'nama_rumpun', 'status'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Rumpun";
            $indexed = $this->indexed;
            return view('admin.master.rumpun.index', compact('title','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nama_rumpun',
                3 => 'status',
            ];

            $search = [];

            $totalData = Rumpun::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $rumpun = Rumpun::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $rumpun = Rumpun::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama_rumpun', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Rumpun::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama_rumpun', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($rumpun)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($rumpun as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nama_rumpun'] = $row->nama_rumpun;
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
            $rumpun = Rumpun::updateOrCreate(
                ['id' => $id],
                [
                    'nama_rumpun' => $request->nama_rumpun,
                    'status' => $request->status
                ]
            );

            return response()->json('Updated');
        } else {
            $rumpun = Rumpun::updateOrCreate(
                ['id' => $id],
                [
                    'nama_rumpun' => $request->nama_rumpun,
                    'status' => $request->status
                ]
            );
            if ($rumpun) {
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

        $rumpun = Rumpun::where($where)->first();

        return response()->json($rumpun);
    }
    public function destroy(string $id)
    {
        //
        $rumpun = Rumpun::where('id', $id)->delete();
    }
}
