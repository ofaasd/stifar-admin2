<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\Fakultas;

class BukaTutupController extends Controller
{
    //
    public $indexed = ['','id', 'kode_prodi','nama_prodi', 'status'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Bukta Tutup KRS";
            $url = "Buka-Tutup-Prodi";
            $indexed = $this->indexed;
            return view('admin.keuangan.buka_tutup_prodi.index', compact('title','indexed', 'url', 'fakultas'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode_prodi',
                3 => 'nama_prodi',
                4 => 'status',
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
                    $nestedData['nama_prodi'] = $row->nama_prodi;
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
}
