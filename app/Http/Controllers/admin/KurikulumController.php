<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kurikulum;
use App\Models\Prodi;
use App\Models\TahunAjaran;

class KurikulumController extends Controller
{
    public $indexed = ['', 'id', 'kode_kurikulum', 'progdi', 'thn_ajar', 'angkatan', 'status'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Kurikulum";
            $indexed = $this->indexed;
            $data_prodi = Prodi::orderBy('id')->get();
            $data_ta = TahunAjaran::orderBy('id')->get();
            return view('admin.master.kurikulum.index', compact('title','indexed', 'data_prodi', 'data_ta'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode_kurikulum',
                3 => 'progdi',
                4 => 'thn_ajar',
                5 => 'angkatan',
                6 => 'status'
            ];

            $search = [];

            $totalData = Kurikulum::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $kurikulum = Kurikulum::select("kurikulums.*")->leftJoin('prodis', 'kurikulums.progdi', '=', 'prodis.id')
                    ->leftJoin('tahun_ajarans', 'tahun_ajarans.id', '=', 'kurikulums.thn_ajar')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $kurikulum = Kurikulum::select("kurikulums.*")->leftJoin('prodis', 'kurikulums.progdi', '=', 'prodis.id')
                    ->leftJoin('tahun_ajarans', 'tahun_ajarans.id', '=', 'kurikulums.thn_ajar')
                    ->where('kurikulums.id', 'LIKE', "%{$search}%")
                    ->orWhere('kurikulums.kode_kurikulum', 'LIKE', "%{$search}%")
                    ->orWhere('kurikulums.progdi', 'LIKE', "%{$search}%")
                    ->orWhere('kurikulums.thn_ajar', 'LIKE', "%{$search}%")
                    ->orWhere('kurikulums.angkatan', 'LIKE', "%{$search}%")
                    ->orWhere('kurikulums.status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Kurikulum::leftJoin('prodis', 'kurikulums.progdi', '=', 'prodis.id')
                ->leftJoin('tahun_ajarans', 'tahun_ajarans.id', '=', 'kurikulums.thn_ajar')
                ->where('kurikulums.id', 'LIKE', "%{$search}%")
                ->orWhere('kurikulums.kode_kurikulum', 'LIKE', "%{$search}%")
                ->orWhere('kurikulums.progdi', 'LIKE', "%{$search}%")
                ->orWhere('kurikulums.thn_ajar', 'LIKE', "%{$search}%")
                ->orWhere('kurikulums.angkatan', 'LIKE', "%{$search}%")
                ->orWhere('kurikulums.status', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($kurikulum)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($kurikulum as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['kode_kurikulum'] = $row->kode_kurikulum;
                    $nestedData['progdi'] = $row->progdi;
                    $nestedData['thn_ajar'] = $row->kode_ta;
                    $nestedData['angkatan'] = $row->angkatan;
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
            $kurikulum = Kurikulum::updateOrCreate(
                ['id' => $id],
                [
                    'kode_kurikulum' => $request->kode_kurikulum,
                    'progdi' => $request->progdi,
                    'thn_ajar' => $request->thn_ajar,
                    'angkatan' => $request->angkatan,
                    'status' => $request->status
                ]
            );

            return response()->json('Updated');
        } else {
            $kurikulum = Kurikulum::updateOrCreate(
                ['id' => $id],
                [
                    'kode_kurikulum' => $request->kode_kurikulum,
                    'progdi' => $request->progdi,
                    'thn_ajar' => $request->thn_ajar,
                    'angkatan' => $request->angkatan,
                    'status' => $request->status
                ]
            );
            if ($kurikulum) {
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

        $kurikulum = Kurikulum::where($where)->first();

        return response()->json($kurikulum);
    }
    public function destroy(string $id)
    {
        //
        $kurikulum = Kurikulum::where('id', $id)->delete();
    }
}
