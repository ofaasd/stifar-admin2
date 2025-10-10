<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan; 
use App\Models\DetailTagihanKeuangan; 
use App\Models\Mahasiswa; 
use App\Imports\TagihanImport;
use App\Models\JenisKeuangan;
use Maatwebsite\Excel\Facades\Excel;  

class TagihanTotalController extends Controller
{
    //
    public $indexed = ['','id','gelombang', 'nim','nama', 'total_bayar'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "tagihan_total";
            $title2 = "Total Tagihan";
            $indexed = $this->indexed;
            $mahasiswa = Mahasiswa::where('status',1)->get();
            $jenis = JenisKeuangan::whereNotNull('kode')->get();
            return view('admin.keuangan.tagihan_total.index', compact('title', 'title2', 'indexed','mahasiswa','jenis'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'gelombang',
                3 => 'nim',
                4 => 'nama',
                5 => 'total_bayar',
            ];

            $totalData = Tagihan::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $tagihan = Tagihan::where('status',0)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $tagihan = Tagihan::where('status',0)
                    ->where(fn($query) =>
                        $query ->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('nim', 'LIKE', "%{$search}%")
                    )
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Tagihan::where('status',0)
                    ->where(fn($query) =>
                        $query ->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('nim', 'LIKE', "%{$search}%")
                    )
                    ->count();
            }

            $data = [];
            if (!empty($tagihan)) {
                foreach ($tagihan as $index => $row) {
                    $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['gelombang'] = $row->gelombang ?? "";
                    $nestedData['nim'] = $row->nim ?? "";
                    $nestedData['nama'] = $mahasiswa->nama ?? "";
                    $nestedData['total_bayar'] =  number_format($row->total_bayar ?? 0,0,",",".");
                    $data[] = $nestedData;
                }
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ]);
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
        // Validasi data
        $id = $request->id;
        $total = (int) str_replace('.', '', $request->total_jumlah);
        
        if($id){
            //update the field
            $rekening = Tagihan::updateOrCreate(
                ['id' => $id],
                [
                    'nim' => $request->nim,
                    'gelombang' => $request->gelombang,
                    'total_bayar' => $total,
                ]
            );
            $delete = DetailTagihanKeuangan::where('id_tagihan',$id)->delete();
            foreach($request->jenis as $index => $jenis){
                DetailTagihanKeuangan::create([
                    'id_tagihan' => $rekening->id,
                    'id_jenis' => $jenis,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }
        }else{
            $rekening = Tagihan::updateOrCreate(
                ['id' => $id],
                [
                    'nim' => $request->nim,
                    'gelombang' => $request->gelombang,
                    'total_bayar' => $total,
                ]
            );
            foreach($request->jenis as $index => $jenis){
                DetailTagihanKeuangan::create([
                    'id_tagihan' => $rekening->id,
                    'id_jenis' => $jenis,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }
        }
        
        if($rekening){
            return response()->json([
                'status' => 'success',
                'message' => 'Rekening Koran Berhasil ditambah/update',
            ], 200);
        }else{
           return response()->json([
                'status' => 'Error',
                'message' => 'Data gaga diupdate',
            ], 401);  
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tagihan $Tagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tagihan = [];
        $tagihan[1] = Tagihan::find($id);
        $tagihan[2] = DetailTagihanKeuangan::where('id_tagihan',$id)->get();
        if ($tagihan) {
            return response()->json($tagihan);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset Gedung not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tagihan $Tagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rekening = Tagihan::where('id', $id)->delete();
        $rekening = DetailTagihanKeuangan::where('id_tagihan', $id)->delete();
    }
    public function import(Request $request){
        Excel::import(new TagihanImport, $request->file('file_excel'));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Rekening Koran Berhasil ditambah/update',
        ], 200);
    }
}
