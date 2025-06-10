<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Http\Controllers\Controller;
use App\Models\BerkasBimbingan;
use App\Models\BimbinganSkripsi;
use App\Models\Skripsi;
use Auth;
use Illuminate\Http\Request;

class BimbinganSkripsiController extends Controller
{
    public function index()
    {
            $user = Auth::user();
            $email = $user->email;
            $nim = explode('@', $email)[0];

            // Ambil data skripsi mahasiswa
            $skripsi = Skripsi::where('nim', $nim)->first();
            
            if (!$skripsi) {
                return view('mahasiswa.skripsi.bimbingan.main', [
                    'bimbingan' => collect(),
                    'message' => 'Anda belum terdaftar dalam sistem skripsi.'
                ]);
            }

            // Ambil semua data bimbingan mahasiswa dengan relasi
            $bimbingan = BimbinganSkripsi::with([
                'berkas',
                'skripsi:id,nim,judul'
            ])
            ->where('skripsi_id', $skripsi->id)
            ->orderBy('tanggal_waktu', 'desc')
            ->get();

            // Format data untuk tampilan
            $bimbingan = $bimbingan->map(function ($item) {
                // Parse waktu jika tersimpan dalam format tertentu
                if ($item->tanggal_waktu) {
                    $datetime = \Carbon\Carbon::parse($item->tanggal_waktu);
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

            return view('mahasiswa.skripsi.bimbingan.main', compact('bimbingan', 'skripsi'));
      
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
    
        $user = Auth::user();
        $email = $user->email;

        $nim = explode('@', $email)[0];
        // Pastikan mahasiswa memiliki skripsi
        $skripsi = \App\Models\Skripsi::where('nim', $nim)->firstOrFail();
    
        // Simpan data bimbingan
        $bimbingan = BimbinganSkripsi::create([
            'skripsi_id' => $skripsi->id,
            'nip' => $skripsi->pembimbing->first()->nip ?? null, // asumsi pembimbing_1 aktif
            'tanggal_waktu' => $request->tanggal,
            'topik' => $request->topik,
            'metode' => $request->metode,
            'status' => 0,
            'catatan_mahasiswa' => $request->catatan,
            'catatan_dosen' => null,
            'tempat' => null,
        ]);
    
        // Simpan semua file yang diunggah
        if ($request->hasFile('filePendukung')) {
            foreach ($request->file('filePendukung') as $file) {
                $path = $file->store('berkas_bimbingan', 'public');
    
                BerkasBimbingan::create([
                    'id_bimbingan' => $bimbingan->id,
                    'file' => $path,
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Jadwal bimbingan berhasil diajukan.');
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
