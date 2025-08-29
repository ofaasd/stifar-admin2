<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Helpers\HelperSkripsi\SkripsiHelper;
use App\Http\Controllers\Controller;
use App\Models\BerkasBimbingan;
use App\Models\BimbinganSkripsi;
use App\Models\Skripsi;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;

class BimbinganSkripsiController extends Controller
{
    public function index()
    {
         
            // Ambil data skripsi mahasiswa
            $idMaster = SkripsiHelper::getIdMasterSkripsi();
            
            if (!$idMaster) {
                return view('mahasiswa.skripsi.bimbingan.main', [
                    'bimbingan' => collect(),
                    'message' => 'Anda belum terdaftar dalam sistem skripsi.'
                ]);
            }

            // Ambil semua data bimbingan mahasiswa dengan relasi
            $bimbingan = BimbinganSkripsi::where('id_master', $idMaster)
            ->orderBy('tanggal_waktu', 'desc')
            ->get();

            // Format data untuk tampilan
            $bimbingan = $bimbingan->map(function ($item) {
                // Parse waktu jika tersimpan dalam format tertentu
                if ($item->created_at) {
                    $datetime = \Carbon\Carbon::parse($item->created_at);
                    $item->tanggal_formatted = $datetime->format('d F Y');
                    $item->waktu_formatted = $datetime->format('H:i');
                }
                
                // Status label untuk referensi
                switch ($item->status) {
                    case 0:
                        $item->status_label = 'Menunggu';
                        break;
                    case 1:
                        $item->status_label = 'ACC';
                        break;
                    case 2:
                        $item->status_label = 'Disetujui';
                        break;
                    case 3:
                        $item->status_label = 'Revisi';
                        break;
                    default:
                        $item->status_label = 'Unknown';
                }
                
                return $item;
            });
            return view('mahasiswa.skripsi.bimbingan.main', compact('bimbingan'));
      
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
            'topik' => 'required|string',
            'metode' => 'required|string',
            'filePendukung.*' => 'nullable|file|max:2048|mimes:pdf,docx,doc,zip,rar,jpg,png',
        ]);
        $idMaster = SkripsiHelper::getIdMasterSkripsi();
    
        try {
            DB::beginTransaction();
    
            // Simpan data bimbingan
            $bimbingan = BimbinganSkripsi::create([
                'id_master' => $idMaster,
                'tanggal_waktu' => $request->tanggal,
                'topik' => $request->topik,
                'metode' => $request->metode,
                'status' => 0,
                'catatan_mahasiswa' => $request->catatan,
                'catatan_dosen' => null,
                'tempat' => null,
            ]);
    
            Log::info('Bimbingan skripsi dibuat', [
                'id_bimbingan' => $bimbingan->id,
                'id_master' => $idMaster,
                'nim' => Auth::user()->email,
                'tanggal' => $request->tanggal,
                'topik' => $request->topik
            ]);
    
            // Simpan file
            if ($request->hasFile('filePendukung')) {
                foreach ($request->file('filePendukung') as $file) {
                    $path = $file->store('berkas_bimbingan', 'public');
    
                    BerkasBimbingan::create([
                        'id_bimbingan' => $bimbingan->id,
                        'file' => $path,
                    ]);
    
                    Log::info('File bimbingan berhasil diunggah', [
                        'id_bimbingan' => $bimbingan->id,
                        'file' => $path,
                        'size_kb' => round($file->getSize() / 1024, 2)
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Jadwal bimbingan berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan bimbingan skripsi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
    

    public function detail($id)
{
    // Ambil data bimbingan + file terkait
    $bimbingan = BimbinganSkripsi::with('berkas')->findOrFail($id);

    // Render view sebagai HTML

    // Return sebagai respons JSON untuk AJAX
    return response()->json([
        'success' => true,
        'html' => $bimbingan
    ]);
}

}
