<?php

namespace App\Http\Controllers\admin\akademik;

use Carbon\Carbon;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Models\PenyerahanIjazah;
use App\Http\Controllers\Controller;

class PenyerahanIjazahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'mahasiswa', 'dicetak_pada', 'diserahkan_pada'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Penyerahan Ijazah";
            $title2 = "penyerahan-ijazah"; 

            $prodi = Prodi::all();
            $indexed = $this->indexed;
            return view('admin.akademik.penyerahan-ijazah.index', compact('title', 'title2','indexed', 'prodi'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'mahasiswa',
                3 => 'dicetak_pada',
                4 => 'diserahkan_pada',
            ];

            $search = [];

            $totalData = PenyerahanIjazah::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = 'penyerahan_ijazah.created_at';
            $dir = $request->input('order.0.dir') ?? 'desc';

            $query = PenyerahanIjazah::select([
                'penyerahan_ijazah.id',
                'penyerahan_ijazah.created_at',
                'penyerahan_ijazah.printed_at',
                'penyerahan_ijazah.gived_at',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.id_program_studi',
                'mahasiswa.foto_yudisium AS fotoMhs'
            ])
            ->leftJoin('mahasiswa', 'penyerahan_ijazah.nim', '=', 'mahasiswa.nim');

            $filterProdi = $request->input('filterprodi') ?? null;

            if (empty($request->input('search.value'))  || !empty($filterProdi)) {

                if ($filterProdi != '') {
                    $query->where('mahasiswa.id_program_studi', $filterProdi);
                }

                $pendaftar = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            } else {
                $search = $request->input('search.value');

                $pendaftar = $query->where('mahasiswa.nim', 'LIKE', "%{$search}%")
                    ->orWhere('mahasiswa.nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();


                $totalFiltered = $query->where('mahasiswa.nim', 'LIKE', "%{$search}%")
                    ->orWhere('mahasiswa.nama', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];

            if (!empty($pendaftar)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($pendaftar as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['mahasiswa'] = $row->nim . ' - ' . $row->nama;
                    $nestedData['dicetak_pada'] = $row->printed_at
                        ? Carbon::parse($row->printed_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss')
                        : null;
                    $nestedData['diserahkan_pada'] = $row->gived_at
                        ? Carbon::parse($row->gived_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss')
                        : null;
                    $data[] = $nestedData;
                }
            }
            if ($data) {
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
        $id = $request->id;

        try {
            if ($id) {
                $save = PenyerahanIjazah::updateOrCreate(
                    ['id' => $id],
                    [
                        'gived_at' => $request->gived_at,
                        'by_id' => auth()->user()->id,
                    ]
                );

                // user updated
                return response()->json('Updated', 200);
            } else {
                $save = PenyerahanIjazah::updateOrCreate(
                    ['id' => $id],
                    [
                        'gived_at' => $request->gived_at,
                        'by_id' => auth()->user()->id,
                    ]
                );

            if ($save) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Daftar Wisudawan');
            }
        }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
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
        //
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
        //
    }
}
