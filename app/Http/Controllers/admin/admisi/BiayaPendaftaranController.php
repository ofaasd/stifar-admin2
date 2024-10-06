<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\BiayaPendaftaran;

class BiayaPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'tahun_ajaran','id_prodi','rpl','jumlah'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "biaya_pendaftaran";
            $title2 = "Biaya Pendaftaran";
            $indexed = $this->indexed;
            $prodi = Prodi::all();
            return view('admin.admisi.biaya_pendaftaran.index', compact('title2','title','indexed','prodi'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'tahun_ajaran',
                3 => 'id_prodi',
                4 => 'rpl',
                5 => 'jumlah',
            ];

            $search = [];

            $totalData = BiayaPendaftaran::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $biaya = BiayaPendaftaran::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $biaya = BiayaPendaftaran::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('tahun_ajaran', 'LIKE', "%{$search}%")
                    ->orWhere('jumlah', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = BiayaPendaftaran::where('id', 'LIKE', "%{$search}%")
                ->orWhere('tahun_ajaran', 'LIKE', "%{$search}%")
                ->orWhere('jumlah', 'LIKE', "%{$search}%")
                ->count();

            }

            $data = [];

            if (!empty($biaya)) {
            // providing a dummy id instead of database ids
                $ids = $start;
                $prodi = Prodi::all();
                $list_prodi = [];
                foreach($prodi as $row){
                    $list_prodi[$row->id] = $row->nama_prodi;
                }
                foreach ($biaya as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['id_prodi'] = $list_prodi[$row->id_prodi];
                    $nestedData['tahun_ajaran'] = $row->tahun_ajaran;
                    $nestedData['rpl'] = ($row->rpl == 1)?"RPL":"Tidak";
                    $nestedData['jumlah'] = number_format($row->jumlah,0,",",".");
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
        //
        $id = $request->id;
        $id_prodi = $request->id_program_studi;

        if ($id) {
            $biaya = BiayaPendaftaran::updateOrCreate(
                ['id' => $id],
                [
                    'id_prodi' => $id_prodi,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'rpl' => $request->rpl,
                    'jumlah' => $request->jumlah,
                ]
            );

            return response()->json('Updated');
        } else {
            $biaya = BiayaPendaftaran::updateOrCreate(
                ['id' => $id],
                [
                    'id_prodi' => $id_prodi,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'rpl' => $request->rpl,
                    'jumlah' => $request->jumlah,
                ]
            );
            if ($biaya) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
            }
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
        //
        $where = ['id' => $id];

        $biaya[0] = BiayaPendaftaran::where($where)->first();

        return response()->json($biaya);
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
        //
        $biaya = BiayaPendaftaran::where('id', $id)->delete();
    }
}
