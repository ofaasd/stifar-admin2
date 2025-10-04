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

class RekeningKoranArsipController extends Controller
{
    //
    public $indexed = ['','id','post_date', 'eff_date', 'cheque_no', 'description', 'debit','credit','balance','transaction','ref_no','status'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "rekening_koran/arsip";
            $title2 = "Rekening Koran Arsip";
            $indexed = $this->indexed;
            return view('admin.keuangan.rek_koran.arsip', compact('title', 'title2', 'indexed'));
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
                $rekening = RekeningKoranTemp::whereIn('status',[1,2])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $rekening = RekeningKoranTemp::whereIn('status',[1,2])
                    ->where(fn($query) =>
                        $query ->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhere('nim', 'LIKE', "%{$search}%")
                    )
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = RekeningKoranTemp::whereIn('status',[1,2])
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
}
