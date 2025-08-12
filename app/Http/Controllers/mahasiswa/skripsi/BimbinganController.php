<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Models\BimbinganSkripsi;
use App\Models\JudulSkripsi;
use App\Models\PegawaiBiodatum;
use Illuminate\Http\Request;
use App\Models\MasterBimbingan;
use App\Models\LogbookBimbingan;
use App\Models\MasterPembimbing;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class BimbinganController extends Controller
{
    public function index() {
        $user = Auth::user();
    
        $nim = Mahasiswa::where('id', $user->id)->value('nim');
    
        if (!$nim) {
            return $this->renderView();
        }
    
        // Mengambil data master bimbingan
        $dataMaster = MasterBimbingan::select('id', 'nip_pembimbing_1', 'nip_pembimbing_2')
            ->where('nim', $nim)
            ->first();
    
        // Jika data master bimbingan tidak ditemukan, kembalikan view dengan data null
        if (!$dataMaster) {
            return $this->renderView();
        }
      
        // Mengambil data lainnya
        $judul = JudulSkripsi::where('nim', $nim)->first();
        $dataBimbingan = BimbinganSkripsi::where('id_master_bimbingan', $dataMaster->id)->select('id')->get();
        $dataBimbinganIds = $dataBimbingan->pluck('id');
        
        // Mengambil data tahap bimbingan terakhir
        $TahapBimbingan = BimbinganSkripsi::where('id_master_bimbingan', $dataMaster->id)
            ->latest()
            ->first();
        
        // Mengambil data logbook bimbingan berdasarkan daftar ID
        $logbookBimbingan = LogbookBimbingan::whereIn('id_bimbingan', $dataBimbinganIds)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Mengambil data pembimbing
        $pembimbing1 = PegawaiBiodatum::select('nama_lengkap','npp', 'nidn', 'email1', 'nohp', 'homebase')
            ->where('npp', $dataMaster->nip_pembimbing_1)
            ->first();
        $pembimbing2 = PegawaiBiodatum::select('nama_lengkap', 'nidn', 'email1', 'nohp', 'homebase')
            ->where('npp', $dataMaster->nip_pembimbing_2)
            ->first();
    
        // Render view dengan data
        return $this->renderView($dataMaster, $dataBimbingan, $TahapBimbingan, $pembimbing1, $pembimbing2, $judul, $logbookBimbingan);
    }
    private function renderView(
        $dataMaster = null,
        $dataBimbingan = null,
        $TahapBimbingan = null,
        $pembimbing1 = null,
        $pembimbing2 = null,
        $judul = null,
        $logbookBimbingan = null
    ) {
        return view('mahasiswa.skripsi.bimbingan.index', compact(
            'dataMaster',
            'dataBimbingan',
            'TahapBimbingan',
            'pembimbing1',
            'pembimbing2',
            'judul',
            'logbookBimbingan'
        ));
    }
    
    public function getModalLogbook($id){
        $data = LogbookBimbingan::where('id', $id)
            ->select('keterangan', 'kategori', 'komentar', 'kategori_pembimbing', 'file_pembimbing', 'file_mhs', 'created_at')
            ->first();
    
        if ($data) {
            $data->formatted_created_at = \Carbon\Carbon::parse($data->created_at)->format('Y-m-d');
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
    }
    

    public function UploadBimbingan(Request $request)
{
    try {
        // Validasi input
        $request->validate([
            'id_master_bimbingan' => 'required|integer',
            'keterangan' => 'nullable|string',
            'kategori' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ], [
            'id_master_bimbingan.required' => 'ID master bimbingan harus diisi.',
            'file.mimes' => 'File harus berupa PDF atau Word.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $fileName = null;

        // Validasi urutan kategori
        $kategori = $request->kategori;
        $kategoriNumber = (int) filter_var($kategori, FILTER_SANITIZE_NUMBER_INT);  // Mengambil angka dari string "Bab 2"

        // Periksa apakah bab sebelumnya sudah diunggah
        if ($kategoriNumber > 1) {
            $previousKategori = 'Bab ' . ($kategoriNumber - 1);

            $previousBimbingan = BimbinganSkripsi::where('kategori', $previousKategori)
                ->where('id_master_bimbingan', $request->id_master_bimbingan)
                ->first();

            if (!$previousBimbingan) {
                return redirect()->back()->with('message', "Anda Belum mengunggah $previousKategori.")
                    ->with('status', 'error');
            }
        }

        // Mengunggah file jika ada
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName(); 
            $file->storeAs('bimbingan_files', $fileName, 'public');
        }

        // Mencari data bimbingan yang sesuai berdasarkan kategori dan id_master_bimbingan
        $bimbingan = BimbinganSkripsi::where('kategori', $kategori)
            ->where('id_master_bimbingan', $request->id_master_bimbingan)
            ->first();

        if ($bimbingan) {
            $bimbingan->update([
                'keterangan' => $request->keterangan,
                'file' => $fileName,
                'kategori' => $kategori,
                'status' => 0,
            ]);
        } else {
            $bimbingan = BimbinganSkripsi::create([
                'id_master_bimbingan' => $request->id_master_bimbingan,
                'file' => $fileName,
                'kategori' => $kategori,
                'status' => 0,
            ]);
        }

        // Buat Logbook Bimbingan
        LogbookBimbingan::create([
            'id_bimbingan' => $bimbingan->id,
            'keterangan' => $request->keterangan,
            'file_mhs' => $fileName,
            'kategori' => $kategori,
            'tgl_pengajuan' => now(),
            'status' => 0,
        ]);

        return redirect()->back()->with('message', 'Bimbingan berhasil diupload')->with('status', 'success');

    } catch (\Exception $e) {
        return redirect()->back()->with('message', 'Terjadi kesalahan: ' . $e->getMessage())->with('status', 'error');
    }
}

    

    
}
