<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankDataVa;
use App\Imports\BankDataVaImport;
use Maatwebsite\Excel\Facades\Excel;

class VaController extends Controller
{
    //
    public $indexed = ['', 'id', 'no_va', 'keterangan', 'nopen', 'status'];
    public function index(Request $request){
        if (empty($request->input('length'))) {
            $title = 'Bank Data VA';
            $title2 = 'bank_data_va';
            $indexed = $this->indexed;
            $va = BankDataVa::all();
            $no = 0;
            return view('admin/keuangan/va/index',compact('title','va','no','title2','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'no_va',
                3 => 'keterangan',
                4 => 'nopen',
                5 => 'status',
            ];

            $search = [];

            $totalData = BankDataVa::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $BankDataVa = BankDataVa::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $BankDataVa = BankDataVa::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('no_va', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%")
                    ->orWhere('nopen', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = BankDataVa::where('id', 'LIKE', "%{$search}%")
                ->orWhere('no_va', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%")
                ->orWhere('nopen', 'LIKE', "%{$search}%")
                ->count();

            }

            $data = [];

            if (!empty($BankDataVa)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($BankDataVa as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = $row->id;
                    $nestedData['no_va'] = $row->no_va;
                    $nestedData['keterangan'] = $row->keterangan;
                    $nestedData['nopen'] = $row->nopen;
                    $nestedData['status'] = ($row->status == 1)?"Aktif":"Belum Aktif";
                    $data[] = $nestedData;
                }
            }
            if ($BankDataVa) {
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

    public function store(Request $request) 
    {
        Excel::import(new BankDataVaImport, $request->file('file_excel'));
        
        return redirect('/admin/keuangan/bank_data_va')->with('success', 'All good!');
    }

    public function destroy(string $id)
    {
        //
        $bankData = BankDataVa::where('id', $id)->delete();

    }

}
