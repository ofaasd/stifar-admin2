<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sesi;
use App\Models\Waktu;
use App\Models\MasterRuang;


class SesiController extends Controller
{
    public $indexed = ['', 'id', 'kode_sesi', 'id_ruang', 'id_waktu', 'status'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Sesi";
            $indexed = $this->indexed;
            $data_waktu = Waktu::orderBy('id')->get();
            $data_ruang = MasterRuang::orderBy('id')->get();
            return view('admin.master.sesi.index', compact('title','indexed', 'data_waktu', 'data_ruang'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode_sesi',
                3 => 'id_ruang',
                4 => 'id_waktu',
                5 => 'status'
            ];

            $search = [];

            $totalData = Sesi::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $sesi = Sesi::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $sesi = Sesi::select('sesis.id', 'sesis.kode_sesi','waktus.nama_sesi','master_ruang.nama_ruang','sesis.status')
                    ->leftJoin('waktus', 'waktus.id', '=', 'sesis.id_waktu')
                    ->leftJoin('master_ruang', 'master_ruang.id', '=', 'sesis.id_ruang')
                    ->where('sesis.id', 'LIKE', "%{$search}%")
                    ->orWhere('sesis.kode_sesi', 'LIKE', "%{$search}%")
                    ->orWhere('waktus.nama_sesi', 'LIKE', "%{$search}%")
                    ->orWhere('master_ruang.nama_ruang', 'LIKE', "%{$search}%")
                    ->orWhere('sesis.status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Sesi::select('sesis.*', 'waktus.nama_sesi','master_ruang.nama_ruang')
                ->leftJoin('waktus', 'waktus.id', '=', 'sesis.id_waktu')
                ->leftJoin('master_ruang', 'master_ruang.id', '=', 'sesis.id_ruang')
                ->where('sesis.id', 'LIKE', "%{$search}%")
                ->orWhere('sesis.kode_sesi', 'LIKE', "%{$search}%")
                ->orWhere('waktus.nama_sesi', 'LIKE', "%{$search}%")
                ->orWhere('master_ruang.nama_ruang', 'LIKE', "%{$search}%")
                ->orWhere('sesis.status', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($sesi)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($ta as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['kode_sesi'] = $row->kode_sesi;
                    $nestedData['nama_sesi'] = $row->nama_sesi;
                    $nestedData['nama_ruang'] = $row->nama_ruang;
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
            $sesi = Sesi::updateOrCreate(
                ['id' => $id],
                [
                    'kode_sesi' => $request->kode_sesi,
                    'id_waktu' => $request->id_waktu,
                    'id_ruang' => $request->id_ruang,
                    'status' => $request->status
                ]
            );

            return response()->json('Updated');
        } else {
            $sesi = Sesi::updateOrCreate(
                ['id' => $id],
                [
                    'kode_sesi' => $request->kode_sesi,
                    'id_waktu' => $request->id_waktu,
                    'id_ruang' => $request->id_ruang,
                    'status' => $request->status
                ]
            );
            if ($sesi) {
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

        $sesi = Sesi::where($where)->first();

        return response()->json($sesi);
    }
    public function destroy(string $id)
    {
        //
        $sesi = Sesi::where('id', $id)->delete();
    }
    
}
