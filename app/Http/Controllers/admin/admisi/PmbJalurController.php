<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbJalur;
use App\Models\PmbJalurProdi;
use App\Models\Prodi;

class PmbJalurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'kode', 'nama', 'keterangan'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "jalur_pendaftaran";
            $title2 = "Jalur Pendaftaran";
            $indexed = $this->indexed;
            $prodi = Prodi::all();
            return view('admin.admisi.jalur.index', compact('title2','title','indexed','prodi'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode',
                3 => 'nama',
                4 => 'keterangan',
            ];

            $search = [];

            $totalData = PmbJalur::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $jalur = PmbJalur::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $jalur = PmbJalur::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('no_gel', 'LIKE', "%{$search}%")
                    ->orWhere('nama_gel', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_mulai', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_akhir', 'LIKE', "%{$search}%")
                    ->orWhere('ujian', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = PmbJalur::where('id', 'LIKE', "%{$search}%")
                ->orWhere('no_gel', 'LIKE', "%{$search}%")
                ->orWhere('nama_gel', 'LIKE', "%{$search}%")
                ->orWhere('tgl_mulai', 'LIKE', "%{$search}%")
                ->orWhere('tgl_akhir', 'LIKE', "%{$search}%")
                ->orWhere('ujian', 'LIKE', "%{$search}%")
                ->count();

            }

            $data = [];

            if (!empty($jalur)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($jalur as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['kode'] = $row->kode;
                    $nestedData['nama'] = $row->nama;
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
            $jalur = PmbJalur::updateOrCreate(
                ['id' => $id],
                [
                    'kode' => $request->kode,
                    'nama' => $request->nama,
                    'keterangan' => $request->keterangan,
                ]
            );
            $jalur_prodi = PmbJalurProdi::where('id_jalur', $id)->delete();
            if(!empty($id_prodi)){
                foreach($id_prodi as $value){
                    $insert_jalur_prodi = PmbJalurProdi::create(
                        [
                            'id_jalur' => $id,
                            'id_program_studi' => $value,
                        ]
                    );
                }
            }

            return response()->json('Updated');
        } else {
            $jalur = PmbJalur::updateOrCreate(
                ['id' => $id],
                [
                    'kode' => $request->kode,
                    'nama' => $request->nama,
                    'keterangan' => $request->keterangan,
                ]
            );
            $jalur_prodi = PmbJalurProdi::where('id_jalur', $id)->delete();
            if(!empty($id_prodi)){
                foreach($id_prodi as $value){
                    $insert_jalur_prodi = PmbJalurProdi::create(
                        [
                            'id_jalur' => $jalur->id,
                            'id_program_studi' => $value,
                        ]
                    );
                }
            }
            if ($jalur) {
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

        $gel[0] = PmbJalur::where($where)->first();
        $gel[1] = PmbJalurProdi::where('id_jalur',$id)->get();

        return response()->json($gel);
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
        $jalur_prodi = PmbJalurProdi::where('id_jalur', $id)->delete();
        $jalur = PmbJalur::where('id', $id)->delete();
    }
}
