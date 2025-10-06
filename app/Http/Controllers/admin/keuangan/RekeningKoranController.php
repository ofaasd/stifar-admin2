<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekeningKoranTemp; 
use App\Models\PmbPesertaOnline; 
use App\Models\Mahasiswa; 
use App\Models\TbPembayaran; 
use App\Imports\RekeningKoranImport;
use Maatwebsite\Excel\Facades\Excel;    

class RekeningKoranController extends Controller
{
    //
    public $indexed = ['','id','post_date', 'eff_date', 'cheque_no', 'description', 'debit','credit','balance','transaction','ref_no','status'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "rekening_koran";
            $title2 = "Rekening Koran";
            $indexed = $this->indexed;
            return view('admin.keuangan.rek_koran.index', compact('title', 'title2', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'post_date',
                3 => 'eff_date',
                4 => 'cheque_no',
                5 => 'description',
                6 => 'debit',
                7 => 'credit',
                8 => 'balance   ',
                9 => 'transaction',
                10 => 'ref_no',
                11 => 'status',
            ];

            $totalData = RekeningKoranTemp::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $rekening = RekeningKoranTemp::where('status',0)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $rekening = RekeningKoranTemp::where('status',0)
                    ->where(fn($query) =>
                        $query ->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhere('nim', 'LIKE', "%{$search}%")
                    )
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = RekeningKoranTemp::where('status',0)
                    ->where(fn($query) =>
                        $query ->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhere('nim', 'LIKE', "%{$search}%")
                    )
                    ->count();
            }

            $data = [];
            if (!empty($rekening)) {
                foreach ($rekening as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['post_date'] = date('d-m-Y H:i', strtotime($row->post_date)) ?? "";
                    $nestedData['eff_date'] = date('d-m-Y H:i', strtotime($row->eff_date)) ?? "";
                    $nestedData['cheque_no'] = $row->cheque_no ?? "";
                    $nestedData['description'] = $row->description ?? "";
                    $nestedData['debit'] =  number_format($row->debit ?? 0,0,",",".");
                    $nestedData['credit'] =  number_format($row->credit ?? 0,0,",",".");
                    $nestedData['balance'] =  number_format($row->balance ?? 0,0,",",".");
                    $nestedData['transaction'] =  $row->transaction ?? '';
                    $nestedData['ref_no'] =  $row->ref_no ?? '';
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

        $rekening = RekeningKoranTemp::updateOrCreate(
            ['id' => $id],
            [
                'post_date' => $request->post_date,
                'eff_date' => $request->eff_date,
                'cheque_no' => $request->cheque_no,
                'description' => $request->description,
                'debit' => $request->debit,
                'credit' => $request->credit,
                'balance' => $request->balance,
                'transaction' => $request->transaction,
                'ref_no' => $request->ref_no,
                'status' => $request->status,
            ]
        );
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
    public function show(RekeningKoranTemp $RekeningKoranTemp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gedung = RekeningKoranTemp::find($id);

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
    public function update(Request $request, RekeningKoranTemp $RekeningKoranTemp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rekening = RekeningKoranTemp::where('id', $id)->delete();
    }
    public function import(Request $request){
        Excel::import(new RekeningKoranImport, $request->file('file_excel'));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Rekening Koran Berhasil ditambah/update',
        ], 200);
    }
    public function after_import(){
        $title = "rekening_koran";
        $title2 = "Proses Data Pembayaran";
        $indexed = $this->indexed;
        $rekening = RekeningKoranTemp::where('status',0)->get();
        $mhs_all = Mahasiswa::all();
        $no = 1;
        $nopen = [];
        $status = [];
        $nim = [];
        $nova = [];
        $jenis_pembayaran = [];
        foreach($rekening as $row){
            $nova[$row->id] = substr($row->description,0,16);
            $nopen[$row->id] = substr($nova[$row->id],5,9);
            $jenis_pembayaran[$row->id] = substr($nova[$row->id],-2,2);
            $nim[$row->id] = $row->nim;
            if(empty($nim[$row->id])){
                $nim[$row->id] = Mahasiswa::where('nopen',$nopen[$row->id])->first()->nim ?? '';
            }
            $status[$row->id] = (!empty($nim[$row->id]))?1:2;
        }
        return view('admin.keuangan.rek_koran.after_import', compact('title', 'title2', 'indexed','nova','jenis_pembayaran','rekening','nopen','status','no','nim','mhs_all'));
    }
    public function get_nama(Request $request){
        $nopen = $request->nopen;
        $peserta = Mahasiswa::where('nopen',$nopen)->first();
        if(empty($peserta)){
            $nim = RekeningKoranTemp::find($request->id)->nim;
            $peserta = Mahasiswa::where('nim',$nim)->first();
            if(empty($peserta)){
                $peserta  = PmbPesertaOnline::where('nopen',$nopen)->first();
            }
        }
        return json_encode($peserta);
    }
    public function update_nim(Request $request){
        $nopen = $request->nopen;
        $nim = $request->nim;
        $peserta = Mahasiswa::where('nim',$nim)->first();
        $rekening = RekeningKoranTemp::find($request->id);
        $rekening->nim = $nim;
        $rekening->save();
        if($request->boolean('simpan_nopen')){
            $new_peserta = Mahasiswa::find($peserta->id);
            $new_peserta->nopen = $nopen;
            $new_peserta->save();
        }
    }
    public function simpan_pembayaran(Request $request){
        $nim = $request->nim;
        $jumlah = $request->jumlah;
        //id rekening koran
        $nopen = $request->nopen;
        $id = $request->id;
        $status = $request->status;
        $keterangan = $request->keterangan;
        foreach($id as $key=>$value){
            $rekening = RekeningKoranTemp::find($value);
            //update nim di rekening koran
            $rekening->nim = $nim[$key];
            if($status[$key] == 1){
                $pembayaran = TbPembayaran::create(
                    [
                        'nim' => $nim[$key],
                        'jumlah' => $jumlah[$key],
                        'keterangan' => $keterangan[$key],
                        'status' => 1,
                        'tanggal_bayar' => date('Y-m-d H:i', strtotime($rekening->post_date)),
                        'id_rekening_koran' => $rekening->id,
                        'jenis_pembayaran' => $request->jenis_pembayaran[$key],
                    ]
                );
                $rekening->id_pembayaran = $pembayaran->id;
            }
            $rekening->jenis_pembayaran = $request->jenis_pembayaran[$key];
            $rekening->no_va = $request->nova[$key];
            $rekening->status = $status[$key];
            $rekening->save();
        }
        return redirect('admin/keuangan/rekening_koran');
    }
}
