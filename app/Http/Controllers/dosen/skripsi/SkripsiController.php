<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Http\Controllers\Controller;
use App\Models\KategoriBerkasSkripsi;
use App\Models\KoordinatorSkripsi;
use App\Models\PegawaiBiodatum;
use App\Models\RefJumlahSksSkripsi;
use App\Models\RefKategoriBerkasSkripsi;
use Illuminate\Http\Request;
use Log;

class SkripsiController extends Controller
{
    public function index()
    {
        $sks = RefJumlahSksSkripsi::firstOrCreate(['id_progdi' => 1], ['jumlah_sks' => 120]);
        $kategoriBerkas = RefKategoriBerkasSkripsi::all();
        // $nipKoordinator = KoordinatorSkripsi::where('id_progdi', $id)->pluck('nip');
        // $koordinator = PegawaiBiodatum::whereIn('npp', $nipKoordinator)->select('id','npp','nama_lengkap')->get();
    
        return view('dosen.skripsi.persyaratan.index', compact('sks', 'kategoriBerkas'));
    }
    public function updateSks(Request $request)
    {
        try {
            $request->validate([
                'jumlah_sks' => 'required|numeric'
            ]);
    
            RefJumlahSksSkripsi::updateOrCreate(
                ['id_progdi' => 1],
                ['jumlah_sks' => $request->jumlah_sks]
            );
    
            return redirect()->back()->with('success', 'Minimal SKS berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Gagal update SKS: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui SKS.');
        }
    }
    

    public function storeBerkas(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:100',
            ]);
    
            RefKategoriBerkasSkripsi::create([
                'nama' => $request->nama_kategori,
            ]);
    
            return redirect()->back()->with('success', 'Kategori berkas berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Gagal menambahkan kategori berkas: ' . $e->getMessage(), [
                'input' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan kategori.');
        }
    }
    
    

    public function updateBerkas(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);
    
        $kategori = RefKategoriBerkasSkripsi::findOrFail($id);
        $kategori->update([
            'nama' => $request->nama_kategori,
        ]);
    
        return redirect()->back()->with('success', 'Kategori berkas berhasil diperbarui.');
    }
    
    public function destroyBerkas($id)
    {
        $kategori = RefKategoriBerkasSkripsi::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berkas berhasil dihapus.');
    }
}
