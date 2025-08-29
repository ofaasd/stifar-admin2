<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Http\Controllers\Controller;
use App\Models\BerkasSkripsi;
use App\Models\MasterSkripsi;
use App\Models\PengajuanBerkasSkripsi;
use App\Models\RefPembimbing;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;
use Validator;

class PengajuanPembimbingController extends Controller
{
    public function index(){
        $pembimbing = RefPembimbing::where('kuota', '!=', 0)
        ->join('pegawai_biodata AS pegawai', 'pegawai.npp', '=', 'ref_pembimbing_skripsi.nip') 
        ->select('pegawai.nama_lengkap', 'pegawai.npp', 'ref_pembimbing_skripsi.kuota')
        ->get();
        return view('mahasiswa.skripsi.pengajuan.dosbim.index',compact('pembimbing'));
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pembimbing1' => 'required|different:pembimbing2',
                'pembimbing2' => 'required',
                'alasan_pemilihan' => 'required|string',
                'transkrip' => 'required|file|mimes:pdf|max:5120',
                'krs' => 'required|file|mimes:pdf|max:5120',
                'tunggakan' => 'nullable|file|mimes:pdf|max:5120',
            ]);
    
            if ($validator->fails()) {
                Log::warning('Validasi gagal saat pengajuan skripsi', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all(),
                ]);
    
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
    
    
        DB::beginTransaction();
    
            $user = Auth::user();
            $email = $user->email;
            $nim = explode('@', $email)[0];
    


            // Simpan pembimbing
            $master = MasterSkripsi::updateOrCreate(
                ['nim',$nim],
                [
                'nim'           => $nim,
                'pembimbing_1'  => $request->pembimbing1,
                'pembimbing_2'  => $request->pembimbing2,
                'status'        => 0,
            ]);
    
            Log::info('Pembimbing skripsi berhasil disimpan', [
                'nim' => $nim,
                'pembimbing1' => $request->pembimbing1,
                'pembimbing2' => $request->pembimbing2,
            ]);
    
            // Upload file
            $folder = "berkas_skripsi/{$nim}";
            $filePaths = [];
    
            foreach (['transkrip', 'krs', 'tunggakan'] as $kategori) {
                if ($request->hasFile($kategori)) {
                    $file = $request->file($kategori);
                    $path = $file->store($folder, 'public');
                    $filePaths[$kategori] = $path;
    
                    Log::info("File $kategori berhasil diupload", [
                        'nim' => $nim,
                        'path' => $path,
                        'size_kb' => $file->getSize() / 1024,
                    ]);
                }
            }
    
            // Simpan ke tabel pengajuan berkas
            foreach ($filePaths as $kategori => $path) {
                $insert = PengajuanBerkasSkripsi::create([
                    'id_master' => $master->id,
                    'nama_file' => $path,
                    'kategori' => strtoupper($kategori),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
    
                Log::info('Berkas pengajuan skripsi disimpan', [
                    'nim' => $nim,
                    'kategori' => strtoupper($kategori),
                    'file' => $path,
                    'status' => 'success'
                ]);
            }
    
            DB::commit();
    
            return redirect()->route('mahasiswa.dashboard')->with('success', 'Pengajuan berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pengajuan skripsi', [
                'nim' => $nim ?? 'N/A',
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

}
