<?php

namespace App\Http\Controllers\admin\aset;

use App\Models\AsetBarang;
use App\Models\MasterRuang;
use Illuminate\Http\Request;
use App\Models\AsetKendaraan;
use App\Models\MasterJenisBarang;
use App\Http\Controllers\Controller;
use App\Models\AsetGedungBangunan;
use App\Models\AsetTanah;
use App\Models\MasterJenisKendaaran;

use Barryvdh\DomPDF\Facade\Pdf;

class CetakAsetController extends Controller
{
    /**
    * menampilkan pilihan untuk mencetak data aset.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function index()
    {
        $title = "cetak";
        $title2 = "Cetak Aset";
        $jenisBarang = MasterJenisBarang::orderBy('kode', 'asc')->get();
        $ruang = MasterRuang::orderBy('nama_ruang', 'asc')->get();
        $jenisKendaraan = MasterJenisKendaaran::orderBy('kode', 'asc')->get();
        return view('admin.aset.cetak-aset.index', compact('title', 'title2', 'jenisBarang', 'jenisKendaraan', 'ruang'));
    }

    /**
    * mencetak PDF data aset berdasarkan jenis aset yang dipilih.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function generatePdf(Request $request)
    {
        try {
            if($request->aset == "#")
            {
                return response()->json("Pilih jenis Aset terlebih dahulu");
            }

            [$type, $value] = explode('_', $request->aset, 2);

            if($type === 'all'){
                $title = "Semua";
                $asetBarang = AsetBarang::all();
                $asetGedung = AsetGedungBangunan::all();
                $asetKendaraan = AsetKendaraan::all();
                $asetTanah = AsetTanah::all();

                $data = $asetBarang->concat($asetKendaraan)->concat($asetGedung)->concat($asetTanah);

            }elseif ($type === 'barang') {
                $title = MasterJenisBarang::where('kode', $value)->pluck('nama')->first();
                $data = AsetBarang::where('kode_jenis_barang', $value)->get();
            } elseif ($type === 'kendaraan') {
                $data = AsetKendaraan::where('kode_jenis_kendaraan', $value)->get();
                $title = MasterJenisKendaaran::where('kode', $value)->pluck('nama')->first();
            } elseif ($type === 'ruang') {
                $title = MasterRuang::whereRaw('REPLACE(nama_ruang, " ", "") = ?', [$value])->pluck('nama_ruang')->first();
                $data = AsetBarang::whereRaw('REPLACE(kode_ruang, " ", "") = ?', [$value])->get();
            } else {
                return response()->json("tidak ditemukan");
            }

            $logo = public_path('/assets/images/logo/logo-icon.png');

            if($type === 'all'){
                $title = "Semua";
            }else{
                $title = str_replace(' ', '', $title);
            }

            $pdf = Pdf::loadView('admin.aset.cetak.cetak-pdf', compact('data', 'title', 'logo'));
            return response()->json([
                'message'   => 'Berhasil cetak',
                'pdf' => base64_encode($pdf->output()),
                'filename' => 'aset-' . preg_replace('/\s+/', '', $title) . '.pdf',
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // return response()->json($e);
        }
    }
}
