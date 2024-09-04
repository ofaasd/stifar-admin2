<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;

class TahunAjaranController extends Controller
{
    public $indexed = ['', 'id', 'kode_ta', 'tgl_awal', 'tgl_akhir', 'status','keterangan'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Tahun Ajaran";
            $url = "ta";
            $indexed = $this->indexed;
            return view('admin.master.tahun_ajaran.index', compact('title','indexed', 'url'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode_ta',
                3 => 'tgl_awal',
                4 => 'tgl_akhir',
                5 => 'status',
                6 => 'keterangan'
            ];

            $search = [];

            $totalData = TahunAjaran::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $ta = TahunAjaran::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $ta = TahunAjaran::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode_ta', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_awal', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_akhir', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = TahunAjaran::where('id', 'LIKE', "%{$search}%")
                ->orWhere('kode_ta', 'LIKE', "%{$search}%")
                ->orWhere('tgl_awal', 'LIKE', "%{$search}%")
                ->orWhere('tgl_akhir', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($ta)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($ta as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['kode_ta'] = $row->kode_ta;
                    $nestedData['tgl_awal'] = $row->tgl_awal;
                    $nestedData['tgl_akhir'] = $row->tgl_akhir;
                    $nestedData['status'] = $row->status;
                    $nestedData['keterangan'] = $row->keterangan;
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
            $ta = TahunAjaran::updateOrCreate(
                ['id' => $id],
                [
                    'kode_ta' => $request->kode_ta,
                    'tgl_awal' => $request->tgl_awal,
                    'tgl_akhir' => $request->tgl_akhir,
                    'status' => $request->status,
                    'keterangan' => $request->keterangan
                ]
            );

            return response()->json('Updated');
        } else {
            $ta = TahunAjaran::updateOrCreate(
                ['id' => $id],
                [
                    'kode_ta' => $request->kode_ta,
                    'tgl_awal' => $request->tgl_awal,
                    'tgl_akhir' => $request->tgl_akhir,
                    'status' => $request->status,
                    'keterangan' => $request->keterangan
                ]
            );
            if ($ta) {
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

        $ta = TahunAjaran::where($where)->first();

        return response()->json($ta);
    }
    public function destroy(string $id)
    {
        //
        $ta = TahunAjaran::where('id', $id)->delete();
    }
}
