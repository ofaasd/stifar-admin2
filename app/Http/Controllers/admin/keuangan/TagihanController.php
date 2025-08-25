<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagihanKeuangan;
use App\Models\DetailTagihanKeuangan;
use App\Models\JenisKeuangan;
use App\Models\SettingKeuangan;
use App\Models\TahunAjaran;
use App\Models\Prodi;
use App\Models\Mahasiswa;

class TagihanController extends Controller
{
    //
    public $indexed = ['', 'id', 'nim', 'nama', 'prodi'];
    public function index(Request $request, String $id="1")
    {
        $TagihanKeuangan = TagihanKeuangan::all();
        $prodi = Prodi::all();
        $nama = [];
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;

        foreach($prodi as $row){
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        if (empty($request->input('length'))) {
            $jenis = JenisKeuangan::all();
            $indexed = $this->indexed;
            $angkatan = Mahasiswa::select("angkatan")->distinct()->orderBy('angkatan','desc')->get();
            $jumlah_jenis = [];
            foreach($jenis as $jen){
                $jumlah_jenis[$jen->id] = SettingKeuangan::where('id_tahun',$id_tahun)->where('id_jenis',$jen->id)->where('id_prodi',$id)->first()->jumlah ?? 0;
                $indexed[] = str_replace(' ', '', $jen->nama);
            }

            $title = "tagihan";
            $title2 = "Tagihan";
            return view('admin.keuangan.tagihan.index', compact('title', 'angkatan','prodi','title2', 'jenis','jumlah_jenis','TagihanKeuangan', 'indexed','id','nama'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nama',
                4 => 'prodi',
            ];
            $last_id = 4;
            $jenis = JenisKeuangan::all();

            foreach($jenis as $jen  ){
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
                    ->orWhere('nim', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = Mahasiswa::where('id_program_studi',$id)
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nim', 'LIKE', "%{$search}%")
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
                            $list_keu[$tag->id][$jen->id] = DetailTagihanKeuangan::where('id_tagihan',$id_tagihan)->where('id_jenis',$jen->id)->first()->jumlah;
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
                    $nestedData['nim'] = $row->nim;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['prodi'] = $nama[$row->id_program_studi];
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
        $angkatan = $request->angkatan;
        $id_prodi = $request->id_prodi;
        $mhs = Mahasiswa::where('angkatan',$angkatan)->where('id_program_studi',$id_prodi)->get();
        $ta = TahunAjaran::where('status','Aktif')->first();
        $jenis = $request->jenis;
        foreach($mhs as $row){
            $tagihan = TagihanKeuangan::where('nim',$row->nim)->where('id_tahun',$ta->id);
            if($tagihan->count() > 0){
                $tagihan = $tagihan->first();
                $new_tagihan = TagihanKeuangan::find($tagihan->id);
                $detail_delete = DetailTagihanKeuangan::where('id_tagihan',$tagihan->id)->delete();
                $total = 0;
                foreach($jenis as $key=>$value){

                    $new_detail = DetailTagihanKeuangan::create(
                        [
                            'id_tagihan' => $tagihan->id,
                            'id_jenis' => $request->id_jenis[$key],
                            'jumlah' => $value,
                        ]
                    );
                    $total += $value;
                }
                $new_tagihan->total = $total;
                $new_tagihan->save();
            }else{
                $tagihan = TagihanKeuangan::create(
                    [
                        'id_tahun' => $ta->id,
                        'nim' => $row->nim,
                    ]
                );
                $total = 0;
                foreach($jenis as $key=>$value){

                    $new_detail = DetailTagihanKeuangan::create(
                        [
                            'id_tagihan' => $tagihan->id,
                            'id_jenis' => $request->id_jenis[$key],
                            'jumlah' => $value,
                        ]
                    );
                    $total += $value;
                }
                $new_tagihan = TagihanKeuangan::find($tagihan->id);
                $new_tagihan->total = $total;
                $new_tagihan->save();
            }
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
