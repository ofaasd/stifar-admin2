<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JabatanStruktural;
use App\Models\PegawaiUnitKerja;
use App\Models\Prodi;
use App\Models\PegawaiBiodatum;

class JabatanStrukturalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'unit_kerja_id','bagian', 'jabatan','pegawai','parent'];
    public function index(Request $request)
    {
        //
        $unit_kerja = PegawaiUnitKerja::all();
        $progdi = Prodi::all();
        if (empty($request->input('length'))) {
            $title = "jabatan_struktural";
            $title2 = "Jabatan Struktural";
            $pegawai = PegawaiBiodatum::all();
            $progdi = Prodi::all();
            $struktur = JabatanStruktural::select('jabatan_struktural.*','pegawai_biodata.nama_lengkap')->join('pegawai_biodata','pegawai_biodata.id','=','jabatan_struktural.id_pegawai')->get();
            $indexed = $this->indexed;
            return view('admin.master.jabatan_struktural.index', compact('title','pegawai','struktur','progdi','unit_kerja','indexed','title2'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'unit_kerja_id',
                3 => 'bagian',
                4 => 'jabatan',
                5 => 'pegawai',
                6 => 'parent',
            ];

            $search = [];

            $totalData = JabatanStruktural::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $jabatan = JabatanStruktural::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $jabatan = JabatanStruktural::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('jabatan', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = JabatanStruktural::where('id', 'LIKE', "%{$search}%")
                ->orWhere('jabatan', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($jabatan)) {
            // providing a dummy id instead of database ids
                $ids = $start;
                $unit_kerja_list = [];
                foreach($unit_kerja as $row){
                    $unit_kerja_list[$row->id] = $row->unit_kerja;
                }
                $unit_kerja_list[0] = "Tidak Ada Unit Kerja";

                foreach ($jabatan as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['unit_kerja_id'] =  $unit_kerja_list[$row->unit_kerja_id];
                    if($row->unit_kerja_id == 1){
                        $nestedData['bagian'] = $row->bagian ?? "Kosong";
                    }else{
                        $nestedData['bagian'] = Prodi::find($row->prodi_id)->nama_prodi ?? $row->bagian;
                    }

                    $nestedData['jabatan'] = $row->jabatan;
                    $nestedData['pegawai'] = PegawaiBiodatum::find($row->id_pegawai)->nama_lengkap ?? '';
                    $nestedData['parent'] = JabatanStruktural::find($row->parent)->jabatan ?? '';
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
        if($id){
            $prodi = 0;
            $bagian = '';
            if($request->unit_kerja == 1){
                $prodi = 0;
                $bagian = $request->bagian;
            }else{
                $bagian = '';
                $prodi = $request->prodi_id;
            }
            $jabatan  = JabatanStruktural::updateOrCreate(
                ['id' => $id],
                [
                    'unit_kerja_id' => $request->unit_kerja,
                    'bagian' => $bagian,
                    'jabatan' => $request->jabatan,
                    'prodi_id' => $prodi,
                    'id_pegawai' => $request->id_pegawai,
                    'parent' => $request->parent,
                ]
            );
            return response()->json('Updated');
        }else{
            $jabatan  = JabatanStruktural::updateOrCreate(
                ['id' => $id],
                [
                    'unit_kerja_id' => $request->unit_kerja,
                    'bagian' => $request->bagian,
                    'jabatan' => $request->jabatan,
                    'prodi_id' => $request->prodi_id,
                    'id_pegawai' => $request->id_pegawai,
                    'parent' => $request->parent,
                ]
            );
            if ($jabatan) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create jabatan Struktural');
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

        $jabatan = JabatanStruktural::where($where)->first();

        return response()->json($jabatan);
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
        $jabatan = JabatanStruktural::where('id', $id)->delete();
    }
}
