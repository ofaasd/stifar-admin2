<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gelombang;

class GelombangController extends Controller
{
    public $indexed = ['', 'id', 'no_gel', 'nama_gel', 'tgl_mulai', 'tgl_akhir', 'ujian'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Gelombang";
            $indexed = $this->indexed;
            return view('admin.master.gelombang.index', compact('title','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'no_gel',
                3 => 'nama_gel',
                4 => 'tgl_mulai',
                5 => 'tgl_akhir',
                6 => 'ujian',
            ];

            $search = [];

            $totalData = Gelombang::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $ruang = Gelombang::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $ruang = Gelombang::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('no_gel', 'LIKE', "%{$search}%")
                    ->orWhere('nama_gel', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_mulai', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_akhir', 'LIKE', "%{$search}%")
                    ->orWhere('ujian', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Gelombang::where('id', 'LIKE', "%{$search}%")
                ->orWhere('no_gel', 'LIKE', "%{$search}%")
                ->orWhere('nama_gel', 'LIKE', "%{$search}%")
                ->orWhere('tgl_mulai', 'LIKE', "%{$search}%")
                ->orWhere('tgl_akhir', 'LIKE', "%{$search}%")
                ->orWhere('ujian', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($ruang)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($ruang as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['no_gel'] = $row->no_gel;
                    $nestedData['nama_gel'] = $row->nama_gel;
                    $nestedData['tgl_mulai'] = $row->tgl_mulai;
                    $nestedData['tgl_akhir'] = $row->tgl_akhir;
                    $nestedData['ujian'] = $row->ujian;
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
            $gel = Gelombang::updateOrCreate(
                ['id' => $id],
                [
                    'no_gel' => $request->no_gel, 
                    'nama_gel' => $request->nama_gel, 
                    'tgl_mulai' => $request->tgl_mulai, 
                    'tgl_akhir' => $request->tgl_akhir, 
                    'ujian' => $request->ujian
                ]
            );

            return response()->json('Updated');
        } else {
            $gel = Gelombang::updateOrCreate(
                ['id' => $id],
                [
                    'no_gel' => $request->no_gel, 
                    'nama_gel' => $request->nama_gel, 
                    'tgl_mulai' => $request->tgl_mulai, 
                    'tgl_akhir' => $request->tgl_akhir, 
                    'ujian' => $request->ujian
                ]
            );
            if ($gel) {
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

        $gel = Gelombang::where($where)->first();

        return response()->json($gel);
    }
    public function destroy(string $id)
    {
        //
        $gel = Gelombang::where('id', $id)->delete();
    }
}
