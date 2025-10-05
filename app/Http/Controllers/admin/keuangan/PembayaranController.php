<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekeningKoranTemp; 
use App\Models\PmbPesertaOnline; 
use App\Models\Mahasiswa; 
use App\Models\Prodi; 
use App\Models\TbPembayaran; 

class PembayaranController extends Controller
{
    //
    public $indexed = ['','id','nim','nama','prodi','jumlah', 'keterangan', 'tanggal_bayar','status'];
    public function index(Request $request)
    {
        $bulan = date('m');
        $tahun = date('Y');
        if(!empty($request->bulan)){
            $bulan = $request->bulan;
        }
        if(!empty($request->tahun)){
            $tahun = $request->tahun;
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
            return view('admin.keuangan.pembayaran.index', compact('title', 'bulan','tahun','list_bulan','title2','mhs_all', 'indexed'));
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

            if (empty($request->input('search.value'))) {
                $pembayaran = TbPembayaran::whereMonth('tanggal_bayar',$bulan)
                    ->whereYear('tanggal_bayar',$tahun)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

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
            }

            $data = [];
            if (!empty($pembayaran)) {
                foreach ($pembayaran as $index => $row) {
                    $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
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
}
