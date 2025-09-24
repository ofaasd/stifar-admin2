<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporPembayaran;

class AdminLaporPembayaranContoller extends Controller
{
    //
    public $indexed = ['', 'nim', 'atas_nama', 'tanggal_lapor', 'status', 'bukti_bayar'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "lapor_bayar";
            $title2 = "Lapor Pembayaran";
            $indexed = $this->indexed;
            return view('admin.keuangan.lapor_bayar.index', compact('title', 'title2', 'indexed'));
        } else {
            $columns = [
                1 => 'nim',
                2 => 'atas_nama',
                3 => 'tanggal_lapor',
                4 => 'status',
                5 => 'bukti_bayar',
            ];

            $totalData = LaporPembayaran::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $gedung = LaporPembayaran::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $gedung = LaporPembayaran::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nim_mahasiswa', 'LIKE', "%{$search}%")
                    ->orWhere('atas_nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = LaporPembayaran::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nim_mahasiswa', 'LIKE', "%{$search}%")
                    ->orWhere('atas_nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($gedung)) {
                foreach ($gedung as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['nim'] = $row->nim_mahasiswa ?? "";
                    $nestedData['atas_nama'] = $row->atas_nama ?? "";
                    $nestedData['tanggal_lapor'] = date('d-m-Y', strtotime($row->tanggal_bayar)) ?? "";
                    $nestedData['status'] = $row->status ?? "";
                    $nestedData['bukti_bayar'] =  $row->bukti_bayar ?? '';
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

            $save = LaporPembayaran::updateOrCreate(
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
    public function show(LaporPembayaran $LaporPembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gedung = LaporPembayaran::find($id);

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
    public function update(Request $request, LaporPembayaran $LaporPembayaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gedung = LaporPembayaran::where('id', $id)->delete();
    }
}
