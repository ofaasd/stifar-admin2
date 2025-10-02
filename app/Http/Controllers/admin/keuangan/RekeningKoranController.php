<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekeningKoranTemp; 
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
            $title2 = "Laporan Rekening Koran";
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
                $rekening = RekeningKoranTemp::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $rekening = RekeningKoranTemp::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nim_mahasiswa', 'LIKE', "%{$search}%")
                    ->orWhere('atas_nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = RekeningKoranTemp::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nim_mahasiswa', 'LIKE', "%{$search}%")
                    ->orWhere('atas_nama', 'LIKE', "%{$search}%")
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
                    $nestedData['debit'] =  $row->debit ?? '';
                    $nestedData['credit'] =  $row->credit ?? '';
                    $nestedData['balance'] =  $row->balance ?? '';
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

    }
}
