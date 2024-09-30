<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\gelombang as Gelombang;
use App\Models\PmbJalur as Jalur;

class GelombangController extends Controller
{
    public $indexed = ['', 'id', 'no_gel', 'nama_gel', 'nama_gel_long', 'tgl_mulai', 'tgl_akhir','jalur'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Gelombang";
            $indexed = $this->indexed;
            $jalur = Jalur::all();
            return view('admin.admisi.gelombang.index', compact('title','indexed','jalur'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'no_gel',
                3 => 'nama_gel',
                4 => 'nama_gel_long',
                5 => 'tgl_mulai',
                6 => 'tgl_akhir',
                7 => 'jalur',
            ];

            $search = [];

            $totalData = Gelombang::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $gelombang = Gelombang::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $gelombang = Gelombang::where('id', 'LIKE', "%{$search}%")
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

            if (!empty($gelombang)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($gelombang as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['no_gel'] = $row->no_gel;
                    $nestedData['nama_gel'] = $row->nama_gel;
                    $nestedData['nama_gel_long'] = substr($row->nama_gel_long,0,40) . "...";
                    $nestedData['tgl_mulai'] = $row->tgl_mulai;
                    $nestedData['tgl_akhir'] = $row->tgl_akhir;
                    $nestedData['jalur'] = $row->jalur->nama ?? '';
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
                    'nama_gel_long' => $request->nama_gel_long,
                    'tgl_mulai' => $request->tgl_mulai,
                    'tgl_akhir' => $request->tgl_akhir,
                    'ta_awal' => $request->ta_awal,
                    'ta_akhir' => $request->ta_akhir,
                    'ujian' => $request->ujian,
                    'id_jalur' => $request->id_jalur
                ]
            );

            return response()->json('Updated');
        } else {
            $gel = Gelombang::updateOrCreate(
                ['id' => $id],
                [
                    'no_gel' => $request->no_gel,
                    'nama_gel' => $request->nama_gel,
                    'nama_gel_long' => $request->nama_gel_long,
                    'tgl_mulai' => $request->tgl_mulai,
                    'tgl_akhir' => $request->tgl_akhir,
                    'ujian' => $request->ujian,
                    'ta_awal' => $request->ta_awal,
                    'ta_akhir' => $request->ta_akhir,
                    'id_jalur' => $request->id_jalur
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
