<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekeningKoranTemp; 
use App\Models\PmbPesertaOnline; 
use App\Models\Mahasiswa; 
use App\Models\Prodi; 
use App\Models\TbPembayaran; 
use App\Imports\PembayaranImport;
use Maatwebsite\Excel\Facades\Excel;  
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    //
    public $indexed = ['','id','nim','nama','prodi','jumlah', 'keterangan', 'tanggal_bayar','status'];
    public function index(Request $request)
    {
        $bulan = date('m');
        $tahun = date('Y');
        $prodi = 0;
        if(!empty($request->bulan)){
            $bulan = $request->bulan;
        }
        if(!empty($request->tahun)){
            $tahun = $request->tahun;
        }
        if(!empty($request->prodi)){
            $prodi = $request->prodi;
        }
        if (empty($request->input('length'))) {
            $title = "pembayaran";
            $title2 = "Pembayaran";
            $indexed = $this->indexed;
            
            
            $list_bulan = [
                1=>"Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember"
            ];
            $mhs_all = Mahasiswa::where('status',1)->get();
            $all_prodi = Prodi::all();
            $nama = [];
            foreach($all_prodi as $row){
                $nama_prodi = explode(' ',$row->nama_prodi);
                $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
            }
            return view('admin.keuangan.pembayaran.index', compact('title', 'bulan','tahun','list_bulan','title2','mhs_all', 'indexed','nama','prodi','all_prodi'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nama',
                4 => 'prodi',
                5 => 'jumlah',
                6 => 'keterangan',
                7 => 'tanggal_bayar',
                8 => 'status',
            ];

            $totalData = TbPembayaran::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(!empty($request->bulan)){
                $bulan = $request->bulan;
            }
            if(!empty($request->tahun)){
                $tahun = $request->tahun;
            }
            if(!empty($request->prodi)){
                $prodi = $request->prodi;
            }

            if (empty($request->input('search.value'))) {
                if(empty($prodi)){
                    $pembayaran = TbPembayaran::whereMonth('tanggal_bayar',$bulan)
                        ->whereYear('tanggal_bayar',$tahun)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
                }else{
                    // Prefix column with table name to avoid ambiguity in joins
                    $orderColumn = in_array($order, ['nim', 'id']) ? 'tb_pembayaran.' . $order : $order;
                    $pembayaran = TbPembayaran::select('tb_pembayaran.*')
                        ->where('mahasiswa.id_program_studi',$prodi)
                        ->join('mahasiswa','mahasiswa.nim','=','tb_pembayaran.nim')
                        ->whereMonth('tanggal_bayar',$bulan)
                        ->whereYear('tanggal_bayar',$tahun)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($orderColumn, $dir)
                        ->get();
                }   
            } else {
                $search = $request->input('search.value');
                if(empty($prodi)){
                    $pembayaran = TbPembayaran::whereMonth('tanggal_bayar',$bulan)
                        ->whereYear('tanggal_bayar',$tahun)
                        ->where(fn($query) =>
                            $query ->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('keterangan', 'LIKE', "%{$search}%")
                            ->orWhere('nim', 'LIKE', "%{$search}%")
                        )
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();

                    $totalFiltered = TbPembayaran::whereMonth('tanggal_bayar',$bulan)
                        ->whereYear('tanggal_bayar',$tahun) 
                        ->where(fn($query) =>
                            $query ->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('keterangan', 'LIKE', "%{$search}%")
                            ->orWhere('nim', 'LIKE', "%{$search}%")
                        )
                        ->count();
                }else{
                    // Prefix column with table name to avoid ambiguity in joins
                    $orderColumn = in_array($order, ['nim', 'id']) ? 'tb_pembayaran.' . $order : $order;
                    $pembayaran = TbPembayaran::select('tb_pembayaran.*')
                        ->where('mahasiswa.id_program_studi',$prodi)
                        ->join('mahasiswa','mahasiswa.nim','=','tb_pembayaran.nim')
                        ->whereMonth('tanggal_bayar',$bulan)
                        ->whereYear('tanggal_bayar',$tahun)
                        ->where(fn($query) =>
                            $query ->where('tb_pembayaran.id', 'LIKE', "%{$search}%")
                            ->orWhere('tb_pembayaran.keterangan', 'LIKE', "%{$search}%")
                            ->orWhere('tb_pembayaran.nim', 'LIKE', "%{$search}%")
                        )
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($orderColumn, $dir)
                        ->get();
                    $totalFiltered = TbPembayaran::where('mahasiswa.id_program_studi',$prodi)
                        ->join('mahasiswa','mahasiswa.nim','=','tb_pembayaran.nim') 
                        ->whereMonth('tanggal_bayar',$bulan)
                        ->whereYear('tanggal_bayar',$tahun)
                        ->where(fn($query) =>
                            $query ->where('tb_pembayaran.id', 'LIKE', "%{$search}%")
                            ->orWhere('tb_pembayaran.keterangan', 'LIKE', "%{$search}%")
                            ->orWhere('tb_pembayaran.nim', 'LIKE', "%{$search}%")
                        )
                        ->count();
                }
            }

            $data = [];
            if (!empty($pembayaran)) {
                foreach ($pembayaran as $index => $row) {
                    $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
                    if(!empty($mahasiswa)){
                        $prodi = Prodi::find($mahasiswa->id_program_studi);
                        $nestedData = [];
                        $nestedData['fake_id'] = $start + $index + 1;
                        $nestedData['id'] = $row->id;
                        $nestedData['tanggal_bayar'] = date('d-m-Y H:i', strtotime($row->tanggal_bayar)) ?? "";
                        
                        $nestedData['nim'] = $row->nim ?? "";
                        $nestedData['nama'] = $mahasiswa->nama ?? "";
                        $nestedData['prodi'] = $prodi->nama_prodi ?? "";
                        $nestedData['description'] = $row->description ?? "";
                        $nestedData['jumlah'] =  number_format($row->jumlah ?? 0,0,",",".");
                        $nestedData['keterangan'] =  $row->keterangan ?? '';
                        $nestedData['status'] =  $row->status ?? '';
                        $data[] = $nestedData;
                    }
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

    public function removeDuplicates()
    {
        try {
            // 1. Cari kriteria data yang dianggap duplikat
            $duplicates = DB::table('pembayarans') // Ganti dengan nama tabel Anda
                ->select('nim', 'jumlah', 'tanggal_bayar', DB::raw('COUNT(*) as total'))
                ->groupBy('nim', 'jumlah', 'tanggal_bayar')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            $deletedCount = 0;

            foreach ($duplicates as $duplicate) {
                // 2. Ambil ID dari data yang duplikat, urutkan dari yang paling lama (ID terkecil)
                $ids = DB::table('pembayarans')
                    ->where('nim', $duplicate->nim)
                    ->where('jumlah', $duplicate->jumlah)
                    ->where('tanggal_bayar', $duplicate->tanggal_bayar)
                    ->orderBy('id', 'asc')
                    ->pluck('id');

                // 3. Sisihkan ID pertama (index 0), lalu hapus sisanya
                $idsToDelete = $ids->slice(1); 
                
                DB::table('pembayarans')->whereIn('id', $idsToDelete)->delete();
                $deletedCount += $idsToDelete->count();
            }

            return back()->with('success', "Berhasil menghapus $deletedCount data duplikat.");
            
        } catch (\Exception $e) {
            return back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $id = $request->id;

        $pembayaran = TbPembayaran::updateOrCreate(
            ['id' => $id],
            [
                'nim' => $request->nim,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'status' => $request->status,
                'tanggal_bayar' => $request->tanggal_bayar,
            ]
        );
        if($pembayaran){
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
    public function show(TbPembayaran $TbPembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gedung = TbPembayaran::find($id);

        if ($gedung) {
            return response()->json($gedung);
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
    public function update(Request $request, TbPembayaran $TbPembayaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pembayaran = TbPembayaran::where('id', $id)->delete();
    }
    public function import(Request $request){
        Excel::import(new PembayaranImport, $request->file('file_excel'));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran Berhasil ditambah/update',
        ], 200);
    }
    public function cetak(Int $bulan, Int $tahun, Int $prodi){
        $nama_prodi = '';
        if($prodi == 0){
            $pembayaran = TbPembayaran::select('tb_pembayaran.*')
                        ->join('mahasiswa','mahasiswa.nim','=','tb_pembayaran.nim')
                        ->whereMonth('tanggal_bayar',$bulan)
                        ->whereYear('tanggal_bayar',$tahun)
                        ->orderBy('tanggal_bayar', 'asc')
                        ->get();
        }else{
            $nama_prodi = Prodi::find($prodi)->nama_prodi;
            $pembayaran = TbPembayaran::select('tb_pembayaran.*')
                ->where('mahasiswa.id_program_studi',$prodi)
                ->join('mahasiswa','mahasiswa.nim','=','tb_pembayaran.nim')
                ->whereMonth('tanggal_bayar',$bulan)
                ->whereYear('tanggal_bayar',$tahun)
                ->orderBy('tanggal_bayar', 'asc')
                ->get();
        }
        $list_pembayaran = [];
        foreach ($pembayaran as $index => $row) {
            $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
            if(!empty($mahasiswa)){
                $curr_prodi = Prodi::find($mahasiswa->id_program_studi);
                $nestedData = [];
                $nestedData['id'] = $row->id;
                $nestedData['tanggal_bayar'] = date('d-m-Y H:i', strtotime($row->tanggal_bayar)) ?? "";
                
                $nestedData['nim'] = $row->nim ?? "";
                $nestedData['nama'] = $mahasiswa->nama ?? "";
                $nestedData['prodi'] = $curr_prodi->nama_prodi ?? "";
                $nestedData['description'] = $row->description ?? "";
                $nestedData['jumlah'] =  number_format($row->jumlah ?? 0,0,",",".");
                $nestedData['keterangan'] =  $row->keterangan ?? '';
                $nestedData['status'] =  $row->status ?? '';
                $list_pembayaran[] = $nestedData;
            }
        }
        $list_bulan = [
            1=>"Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];
        $data = [
            'pembayaran' => $list_pembayaran,
            'no' => 1,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'prodi' => $prodi,
            'nama_prodi' => $nama_prodi,
            'list_bulan' => $list_bulan,
            'logo' => public_path('/assets/images/logo/logo-icon.png')
        ];
        $filename = 'rekap-pembayaran-'.$bulan.'-'.$tahun.' ' . $prodi . '.pdf';
        $pdf = PDF::loadView('admin.keuangan.pembayaran.cetak_pembayaran', $data);
        return $pdf->download($filename);
    }
}
