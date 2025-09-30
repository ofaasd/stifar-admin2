<?php

namespace App\Http\Controllers\admin\aset;

use App\Http\Controllers\Controller;
use App\Models\AsetBarang;
use App\Models\AsetKendaraan;
use Illuminate\Http\Request;

use App\Models\MasterJenisBarang;
use App\Models\MasterJenisKendaaran;
use App\Models\MasterRuang;

use Barryvdh\DomPDF\Facade\Pdf;

class CetakLabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "cetak-label";
        $title2 = "Cetak Label";
        $jenisBarang = MasterJenisBarang::orderBy('kode', 'asc')->get();
        $ruang = MasterRuang::orderBy('nama_ruang', 'asc')->get();
        $jenisKendaraan = MasterJenisKendaaran::orderBy('kode', 'asc')->get();
        return view('admin.aset.cetak-label.index', compact('title', 'title2', 'jenisBarang', 'jenisKendaraan', 'ruang'));
    }

    public function generatePdf(Request $request)
    {
        try {
            if($request->label == "#")
            {
                // return redirect()->back()->with("error", "Pilih jenis label terlebih dahulu");
                return response()->json("Pilih jenis label terlebih dahulu");
            }

            [$type, $value] = explode('_', $request->label, 2);

            if ($type === 'barang') {
                $title = MasterJenisBarang::where('kode', $value)->pluck('nama')->first();
                $data = AsetBarang::where('kode_jenis_barang', $value)->get();
            } elseif ($type === 'kendaraan') {
                $data = AsetKendaraan::where('kode_jenis_kendaraan', $value)->get()->map(function ($item) {
                    $item->label = $item->kode;
                    return $item;
                });
                $title = MasterJenisKendaaran::where('kode', $value)->pluck('nama')->first();
            } elseif ($type === 'ruang') {
                $title = MasterRuang::whereRaw('REPLACE(nama_ruang, " ", "") = ?', [$value])->pluck('nama_ruang')->first();
                $data = AsetBarang::whereRaw('REPLACE(kode_ruang, " ", "") = ?', [$value])->get();
            } else {
                return response()->json("tidak ditemukan");
            }

            $logo = asset('assets/images/logo/upload/logo_besar.png');

            $title = str_replace(' ', '', $title);
            $pdf = Pdf::loadView('admin.aset.cetak-label.label-pdf', compact('data', 'title', 'logo'));
            return response()->json([
                'message'   => 'Berhasil cetak',
                'pdf' => base64_encode($pdf->output()),
                'filename' => 'label-' . preg_replace('/\s+/', '', $title) . '.pdf',
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // return response()->json($e);
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
        //
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
