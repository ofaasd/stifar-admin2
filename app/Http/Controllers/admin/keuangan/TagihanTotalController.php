<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan; 
use App\Models\DetailTagihanKeuangan; 
use App\Models\Mahasiswa; 
use App\Imports\TagihanImport;
use App\Imports\TagihanS1Import;
use App\Models\JenisKeuangan;
use App\Models\Prodi;
use App\Models\TahunAjaran;

use Maatwebsite\Excel\Facades\Excel;  
use Barryvdh\DomPDF\Facade\Pdf;

class TagihanTotalController extends Controller
{
    //
    public $indexed = ['','id','gelombang', 'nim','nama', 'total_bayar'];
    public function index(Request $request, String $id = null)
    {
        if (empty($request->input('length'))) {
            $title = "tagihan_total";
            $title2 = "Total Tagihan";
            $indexed = $this->indexed;
            $mahasiswa = Mahasiswa::where('status',1)->get();
            $prodi = Prodi::all();
            foreach($prodi as $row){
                $nama_prodi = explode(' ',$row->nama_prodi);
                $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
            }
            $jenis = JenisKeuangan::whereNotNull('kode')->get();
            return view('admin.keuangan.tagihan_total.index', compact('title', 'title2','prodi', 'indexed','mahasiswa','jenis','id','nama'));
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
                if($id != null){
                    $tagihan = Tagihan::where('tagihan.status',0)
                        ->join('mahasiswa','mahasiswa.nim','=','tagihan.nim')
                        ->join('program_studi','program_studi.id','=','mahasiswa.id_program_studi')
                        ->select('tagihan.*')
                        ->where('program_studi.id',$id)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
                }else{
                    $tagihan = Tagihan::where('tagihan.status',0)
                    ->join('mahasiswa','mahasiswa.nim','=','tagihan.nim')
                    ->select('tagihan.*')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                }   
            } else {
                $search = $request->input('search.value');
                if($id != null){
                    $tagihan = Tagihan::where('tagihan.status',0)
                    ->join('mahasiswa','mahasiswa.nim','=','tagihan.nim')
                    ->join('program_studi','program_studi.id','=','mahasiswa.id_program_studi')
                    ->select('tagihan.*')
                    ->where('program_studi.id',$id)
                    ->where(fn($query) =>
                        $query ->where('tagihan.id', 'LIKE', "%{$search}%")
                        ->orWhere('tagihan.nim', 'LIKE', "%{$search}%")
                        ->orWhere('nama', 'LIKE', "%{$search}%")
                    )
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                    $totalFiltered = Tagihan::where('tagihan.status',0)
                    ->join('mahasiswa','mahasiswa.nim','=','tagihan.nim')
                    ->join('program_studi','program_studi.id','=','mahasiswa.id_program_studi')
                    ->select('tagihan.*')
                    ->where('program_studi.id',$id)
                    ->where(fn($query) =>
                        $query ->where('tagihan.id', 'LIKE', "%{$search}%")
                        ->orWhere('tagihan.nim', 'LIKE', "%{$search}%")
                        ->orWhere('nama', 'LIKE', "%{$search}%")
                    )
                    ->count();
                }else{
                    $tagihan = Tagihan::where('tagihan.status',0)
                    ->join('mahasiswa','mahasiswa.nim','=','tagihan.nim')
                    ->select('tagihan.*')
                    ->where(fn($query) =>
                        $query ->where('tagihan.id', 'LIKE', "%{$search}%")
                        ->orWhere('tagihan.nim', 'LIKE', "%{$search}%")
                        ->orWhere('nama', 'LIKE', "%{$search}%")
                    )
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                    $totalFiltered = Tagihan::where('tagihan.status',0)
                    ->join('mahasiswa','mahasiswa.nim','=','tagihan.nim')
                    ->select('tagihan.*')
                    ->where(fn($query) =>
                        $query ->where('tagihan.id', 'LIKE', "%{$search}%")
                        ->orWhere('tagihan.nim', 'LIKE', "%{$search}%")
                        ->orWhere('nama', 'LIKE', "%{$search}%")
                    )
                    ->count();
                }
                
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
        $jenjang = substr($request->prodi,0,2);
        if($jenjang == 'D3' || $jenjang == 'Pr'){
            Excel::import(new TagihanImport, $request->file('file_excel'));
        }else{
            Excel::import(new TagihanS1Import, $request->file('file_excel'));
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Rekening Koran Berhasil ditambah/update',
        ], 200);
    }
    public function cetak(Int $id = null){
        
        $nama_prodi = '';
        if($id != null){
            $nama_prodi = Prodi::find($id)->nama_prodi;
            $tagihan = Tagihan::where('tagihan.status',0)
                ->join('mahasiswa','mahasiswa.nim','=','tagihan.nim')
                ->join('program_studi','program_studi.id','=','mahasiswa.id_program_studi')
                ->select('tagihan.*')
                ->where('program_studi.id',$id)
                ->get();
        }else{
            $tagihan = Tagihan::where('tagihan.status',0)
            ->join('mahasiswa','mahasiswa.nim','=','tagihan.nim')
            ->select('tagihan.*')
            ->get();
        }  

        $new_data = [];
        if (!empty($tagihan)) {
            foreach ($tagihan as $index => $row) {
                $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
                $nestedData = [];
                $nestedData['id'] = $row->id;
                $nestedData['gelombang'] = $row->gelombang ?? "";
                $nestedData['nim'] = $row->nim ?? "";
                $nestedData['nama'] = $mahasiswa->nama ?? "";
                $nestedData['total_bayar'] =  number_format($row->total_bayar ?? 0,0,",",".");
                $new_data[] = $nestedData;
            }
        }

        $data = [
            'tagihan' => $new_data,
            'no' => 1,
            'id' => $id,
            'nama_prodi' => $nama_prodi,
            'logo' => public_path('/assets/images/logo/logo-icon.png')
        ];
        $filename = 'rekap-total-tagihan-' . $id . '.pdf';
        $pdf = PDF::loadView('admin.keuangan.tagihan_total.cetak_tagihan_total', $data);
        return $pdf->download($filename);
    }
    //fungsi ini untuk menghitung ulang jumlah pembayaran pada prodi S1 dan S2 karena menggunakan DPP dan UPP per semester dimana UPP yang digunakan hanya record detail pembyaran UPP pertama
    public function update_jumlah_upp(Int $id_prodi){
        //ambil data mahasiswaa berdasarkan id_prodi (Data yang diolah adalah data angkatan 2024 keatas / > 2024) dan status adalah aktif (1)
        $mahasiswa = Mahasiswa::where('id_program_studi',$id_prodi)->where('angkatan','>=',2024)->where('status',1)->get();
        foreach($mahasiswa as $mhs){
            $tagihan = Tagihan::where('nim',$mhs->nim)->first();
            //tambahkan kondisi mahasiswa yang diproses adalaha mahasiswa yang memiliki tagihan detail DPP dan UPP selain itu skip perhitungan
            
            if($tagihan){
                $detail_tagihan = DetailTagihanKeuangan::where('id_tagihan',$tagihan->id)->where('id_jenis',1)->count();
                if($detail_tagihan > 0){
                //hitung banyaknya TA dari pertama kali angkatan tersebut masuk misal 2024 kode ta adalah 20241 untuk semester ganjll dan 20242 untuk semester genap
                $angkatan = $mhs->angkatan;
                $kode_ta = $angkatan . '1'; //default semester ganjil
                $ta_now = TahunAjaran::where('status',1)->first();
                $semester_count = TahunAjaran::where('kode_ta', '>=', $kode_ta)
                    ->where('kode_ta', '<=', $ta_now->kode_ta)
                    ->count();
                
                    $detail_tagihan = DetailTagihanKeuangan::where('id_tagihan',$tagihan->id)->get();
                    $total_bayar = 0;
                    foreach($detail_tagihan as $detail){
                        //jika jenis keuangan adalah UPP (id_jenis = 2) maka ambil hanya record pertama
                        if($detail->id_jenis == 2){
                            $total_bayar += $detail->jumlah*$semester_count;
                            break; //keluar dari loop setelah mengambil record pertama
                        }else{
                            $total_bayar += $detail->jumlah;
                        }
                    }
                    //update total_bayar pada tabel tagihan
                    Tagihan::where('id',$tagihan->id)->update(['total_bayar' => $total_bayar]);
                    
                }
            }
        }
        redirect()->back()->with('success', 'Jumlah tagihan UPP berhasil diperbarui.');
    }
}
