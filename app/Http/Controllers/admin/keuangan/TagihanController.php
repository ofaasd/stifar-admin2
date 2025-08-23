<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagihanKeuangan;
use App\Models\DetailTagihanKeuangan;
use App\Models\JenisKeuangan;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;

class TagihanController extends Controller
{
    //
    public $indexed = ['', 'id', 'nim', 'nama'];    
    public function index(Request $request, String $id="1")
    {
        $TagihanKeuangan = TagihanKeuangan::all();
        if (empty($request->input('length'))) {
            $jenis = JenisKeuangan::all();
            $indexed = $this->indexed;
            foreach($jenis as $jen){
                $indexed[] = str_replace(' ', '', $jen->nama);
            }
            $title = "tagihan";
            $title2 = "Tagihan";
            return view('admin.keuangan.tagihan.index', compact('title', 'title2', 'jenis','TagihanKeuangan', 'indexed','id'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nama',
            ];
            $last_id = 4;
            $jenis = JenisKeuangan::all();
           
            foreach($jenis as $jen){
                $columns[$last_id] = str_replace(' ', '', $jen->nama);
                $last_id++;
            }

            $totalData = Mahasiswa::where('id_program_studi',$id)->count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $tagihan = Mahasiswa::where('id_program_studi',$id)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $tagihan = Mahasiswa::where('id_program_studi',$id)
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = TagihanKeuangan::where('id_program_studi',$id)
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($tagihan)) {
                $list_keu = [];
                $tahun = TahunAjaran::where('status','Aktif')->first();
                foreach($tagihan as $tag){
                    $real_tagihan = TagihanKeuangan::where('nim',$tag->nim)->where('id_tahun',$tahun->id);
                    if($real_tagihan->count() > 0){
                        $id_tagihan = $real_tagihan->first()->id;
                        foreach($jenis as $jen){
                            $list_keu[$tag->id][$jen->id] = DetailTagihan::where('id_tagihan',$id_tagihan)->where('id_jenis',$jen->id)->first()->jumlah; 
                        }
                    }else{
                        foreach($jenis as $jen){
                            $list_keu[$tag->id][$jen->id] = 0; 
                        }
                    }
                }
                
                foreach ($tagihan as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['nim'] = $row->nama;
                    $nestedData['nama'] = $row->nama;
                    foreach($jenis as $jen){
                        $nestedData[str_replace(' ', '', $jen->nama)] = $list_keu[$row->id][$jen->id];
                    }
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
        $validatedData = $request->validate([

            'nama' => 'string|required',
            'id' => 'nullable',
        ]);

        try {
            $id = $validatedData['id'];

            $save = TagihanKeuangan::updateOrCreate(
                ['id' => $id],
                [
                    'nama' => $validatedData['nama'],
                ]
            );

            if ($id) {
                return response()->json('Updated');
            } elseif ($save) {
                return response()->json('Created');
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save Kategori Aset',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TagihanKeuangan $TagihanKeuangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tagihan = TagihanKeuangan::find($id);

        if ($tagihan) {
            return response()->json($tagihan);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset tagihan not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TagihanKeuangan $TagihanKeuangan)
    {
        //
    }
}
