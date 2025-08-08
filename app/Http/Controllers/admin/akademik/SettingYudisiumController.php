<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use App\Models\GelombangYudisium;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingYudisiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'periode', 'tanggal_mulai_daftar', 'tanggal_selesai_daftar'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Setting Yudisium";
            $title2 = "setting";
            $data = GelombangYudisium::all();
            $indexed = $this->indexed;

            return view('admin.akademik.yudisium.setting.index', compact('title', 'title2', 'data','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'periode',
                3 => 'tanggal_mulai_daftar',
                4 => 'tanggal_selesai_daftar',
            ];

            $search = [];

            $totalData = GelombangYudisium::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $gelombang = GelombangYudisium::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $gelombang = GelombangYudisium::where('periode', 'LIKE', "%{$search}%")
                    ->orWhere('tanggal_mulai_daftar', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = GelombangYudisium::where('periode', 'LIKE', "%{$search}%")
                    ->orWhere('tanggal_mulai_daftar', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];

            if (!empty($gelombang)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($gelombang as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['periode'] = $row->periode;
                    $nestedData['tanggal_mulai_daftar'] = Carbon::parse($row->tanggal_mulai_daftar)->translatedFormat('d F Y');
                    $nestedData['tanggal_selesai_daftar'] = Carbon::parse($row->tanggal_selesai_daftar)->translatedFormat('d F Y');
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
        $id = $request->id;

        try {
            $request->validate([
                'periode' => 'required',
                'tanggal_mulai_daftar' => 'required',
                'tanggal_selesai_daftar' => 'required',
            ]);

            // Ambil hanya tahun dari input 'periode'
            $periode = date('Y', strtotime($request->periode));

            if ($id) {
                $save = GelombangYudisium::updateOrCreate(
                    ['id' => $id],
                    [
                        'periode' => $periode,
                        'tanggal_mulai_daftar' => $request->tanggal_mulai_daftar,
                        'tanggal_selesai_daftar' => $request->tanggal_selesai_daftar,
                    ]
                );

                // user updated
                return response()->json('Updated', 200);
            } else {
                $save = GelombangYudisium::updateOrCreate(
                    ['id' => $id],
                    [
                        'periode' => $periode,
                        'tanggal_mulai_daftar' => $request->tanggal_mulai_daftar,
                        'tanggal_selesai_daftar' => $request->tanggal_selesai_daftar,
                    ]
                );

            if ($save) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Alumni');
            }
        }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
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
        $where = ['id' => $id];

        $data = GelombangYudisium::where($where)->first();

        return response()->json($data);
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
        $data = GelombangYudisium::where('id', $id)->delete();
    }
}
