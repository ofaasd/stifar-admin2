<?php

namespace App\Http\Controllers\admin\akademik\wisuda;

use App\Http\Controllers\Controller;
use App\Models\TbGelombangWisuda;
use Illuminate\Http\Request;

class SettingWisudaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'periode', 'nama', 'tempat', 'waktu_pelaksanaan', 'tanggal_pendaftaran'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Setting Wisuda";
            $title2 = "setting"; 
            $indexed = $this->indexed;

            return view('admin.akademik.wisuda.setting.index', compact('title', 'title2','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'periode',
                3 => 'nama',
                4 => 'tempat',
                5 => 'waktu_pelaksanaan',
                6 => 'tanggal_pendaftaran'
            ];

            $search = [];

            $totalData = TbGelombangWisuda::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $gelombang = TbGelombangWisuda::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $gelombang = TbGelombangWisuda::where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('periode', 'LIKE', "%{$search}%")
                    ->orWhere('tempat', 'LIKE', "%{$search}%")
                    ->orWhere('mulai_pendaftaran', 'LIKE', "%{$search}%")
                    ->orWhere('selesai_pendaftaran', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = TbGelombangWisuda::where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('periode', 'LIKE', "%{$search}%")
                    ->orWhere('tempat', 'LIKE', "%{$search}%")
                    ->orWhere('mulai_pendaftaran', 'LIKE', "%{$search}%")
                    ->orWhere('selesai_pendaftaran', 'LIKE', "%{$search}%")
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
                    $nestedData['nama'] = $row->nama;
                    $nestedData['tempat'] = $row->tempat;
                    $nestedData['waktu_pelaksanaan'] = \Carbon\Carbon::parse($row->waktu_pelaksanaan)->translatedFormat('d F Y H:i');
                    $nestedData['tanggal_pendaftaran'] = \Carbon\Carbon::parse($row->mulai_pendaftaran)->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse($row->selesai_pendaftaran)->translatedFormat('d F Y');
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
                'nama' => 'required',
                'periode' => 'required',
                'tempat' => 'required',
                'tanggal_gladi' => 'required',
                'tanggal_pemberkasan' => 'required',
                'tarif_wisuda' => 'required',
                'waktu_pelaksanaan' => 'required',
                'mulai_pendaftaran' => 'required|date',
                'selesai_pendaftaran' => 'required|date|after_or_equal:mulai_pendaftaran',
            ]);


            if ($id) {
                $save = TbGelombangWisuda::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'periode' => $request->periode,
                        'tempat' => $request->tempat,
                        'tarif_wisuda' => $request->tarif_wisuda,
                        'tanggal_gladi' => $request->tanggal_gladi,
                        'tanggal_pemberkasan' => $request->tanggal_pemberkasan,
                        'waktu_pelaksanaan' => $request->waktu_pelaksanaan,
                        'mulai_pendaftaran' => $request->mulai_pendaftaran,
                        'selesai_pendaftaran' => $request->selesai_pendaftaran,
                    ]
                );

                // user updated
                return response()->json('Updated', 200);
            } else {
                $save = TbGelombangWisuda::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'periode' => $request->periode,
                        'tempat' => $request->tempat,
                        'tarif_wisuda' => $request->tarif_wisuda,
                        'tanggal_gladi' => $request->tanggal_gladi,
                        'tanggal_pemberkasan' => $request->tanggal_pemberkasan,
                        'waktu_pelaksanaan' => $request->waktu_pelaksanaan,
                        'mulai_pendaftaran' => $request->mulai_pendaftaran,
                        'selesai_pendaftaran' => $request->selesai_pendaftaran,
                    ]
                );

            if ($save) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Gelombang Wisuda');
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
        try {
            $where = ['id' => $id];
            $data = TbGelombangWisuda::where($where)->first();

            if ($data) {
                return response()->json($data);
            } else {
                return response()->json('Data not found', 404);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
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
        try {
            $deleted = TbGelombangWisuda::where('id', $id)->delete();
            if ($deleted) {
                return response()->json('Deleted', 200);
            } else {
                return response()->json('Data not found', 404);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
