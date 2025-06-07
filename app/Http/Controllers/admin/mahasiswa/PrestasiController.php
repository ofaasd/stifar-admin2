<?php

namespace App\Http\Controllers\admin\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrestasiMahasiswa;
use App\Models\Mahasiswa;

class PrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nim','nama_mahasiswa', 'nama_prestasi', 'tahun','tingkat','deskripsi'];
    public function index(Request $request)
    {
        $PrestasiMahasiswa = PrestasiMahasiswa::all();
        if (empty($request->input('length'))) {
            $title = "prestasi";
            $title2 = "Prestasi Mahasiswa";
            $indexed = $this->indexed;
            $mahasiswa = Mahasiswa::all();
            return view('admin.mahasiswa.prestasi.index', compact('title', 'title2', 'PrestasiMahasiswa', 'indexed','mahasiswa'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nama_mahasiswa',
                4 => 'nama_prestasi',
                5 => 'tahun',
                6 => 'tingkat',
                7 => 'deskripsi',
            ];

            $totalData = PrestasiMahasiswa::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if (empty($request->input('search.value'))) {
                $prestasi = PrestasiMahasiswa::select('prestasi_mahasiswa.*','mahasiswa.nama as nama_mahasiswa','mahasiswa.nim')
                    ->join('mahasiswa','mahasiswa.id','=','PrestasiMahasiswa.mahasiswa_id')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $prestasi = PrestasiMahasiswa::select('prestasi_mahasiswa.*','mahasiswa.nama as nama_mahasiswa','mahasiswa.nim')
                    ->join('mahasiswa','mahasiswa.id','=','PrestasiMahasiswa.mahasiswa_id')
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = PrestasiMahasiswa::select('prestasi_mahasiswa.*','mahasiswa.nama as nama_mahasiswa','mahasiswa.nim')
                    ->join('mahasiswa','mahasiswa.id','=','PrestasiMahasiswa.mahasiswa_id')
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($prestasi)) {
                foreach ($prestasi as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['nama_mahasiswa'] = $row->nama_mahasiswa;
                    $nestedData['nim'] = $row->nim;
                    $nestedData['nama_prestasi'] = $row->nama_prestasi;
                    $nestedData['tahun'] = $row->tahun;
                    $nestedData['tingkat'] = $row->tingkat;
                    $nestedData['deskripsi'] = $row->deskripsi;
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

            $save = PrestasiMahasiswa::updateOrCreate(
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
                'message' => 'Failed to save prestasi Aset',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prestasi = PrestasiMahasiswa::find($id);

        if ($prestasi) {
            return response()->json($prestasi); // Kembalikan objek PrestasiMahasiswa langsung
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'prestasi Aset not found',
            ], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prestasi = PrestasiMahasiswa::where('id', $id)->delete();
    }
}
