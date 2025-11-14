<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use Log;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\BerkasBimbingan;
use App\Models\BimbinganSkripsi;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanJudulSkripsi;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\HelperSkripsi\SkripsiHelper;

class BimbinganSkripsiController extends Controller
{
    public function index()
    {
        // Ambil data skripsi mahasiswa
        $idMaster = SkripsiHelper::getIdMasterSkripsi();

        $title = "Bimbingan";

        if (!$idMaster) {
            $bimbingan = collect();
            $judulSkripsi = null;
            $masterSkripsi = null;

            return view('mahasiswa.skripsi.bimbingan.main', [
                'bimbingan' => $bimbingan,
                'message' => 'Anda belum terdaftar dalam sistem skripsi.',
                'judulSkripsi' => $judulSkripsi,
                'masterSkripsi' => $masterSkripsi,
                'title' => $title
            ]);
        }

        $judulSkripsi = PengajuanJudulSkripsi::where('id_master', $idMaster)->first();

        // Ambil semua data bimbingan mahasiswa dengan relasi
        $bimbingan = BimbinganSkripsi::select([
            'bimbingan_skripsi.*',
            DB::raw('(SELECT COUNT(*) FROM berkas_bimbingan WHERE berkas_bimbingan.id_bimbingan = bimbingan_skripsi.id) as jumlah_berkas'),
            'pegawai_biodata.nama_lengkap as bimbinganKe'
        ])
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.npp', '=', 'bimbingan_skripsi.bimbingan_to')
        ->where('bimbingan_skripsi.id_master', $idMaster)
        ->orderBy('bimbingan_skripsi.tanggal_waktu', 'desc')
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
                case 4:
                    $item->status_label = 'Ditolak';
                    break;
                default:
                    $item->status_label = 'Unknown';
            }
            
            return $item;
        });

        $masterSkripsi = MasterSkripsi::where('master_skripsi.id', $idMaster)
        ->leftJoin('pegawai_biodata AS pegawai1', 'pegawai1.npp', '=', 'master_skripsi.pembimbing_1')
        ->leftJoin('pegawai_biodata AS pegawai2', 'pegawai2.npp', '=', 'master_skripsi.pembimbing_2')
        ->select(
            'master_skripsi.*',
            'pegawai1.nama_lengkap as nama_pembimbing1',
            'pegawai1.npp as npp_pembimbing1',
            'pegawai1.email1 as email_pembimbing1',
            'pegawai2.nama_lengkap as nama_pembimbing2',
            'pegawai2.npp as npp_pembimbing2',
            'pegawai2.email1 as email_pembimbing2'
        )
        ->latest()
        ->first();

        // Enkripsi NIM jika masterSkripsi ditemukan
        if ($masterSkripsi && isset($masterSkripsi->nim)) {
            $masterSkripsi->nimEnkripsi = Crypt::encryptString($masterSkripsi->nim . "stifar");
        }

        return view('mahasiswa.skripsi.bimbingan.main', compact('bimbingan', 'judulSkripsi', 'masterSkripsi', 'title'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'pembimbing' => 'required',
                'catatan' => 'required|string',
                'permasalahan' => 'required|string',
                'metode' => 'required|string',
                'filePendukung.*' => 'nullable|file|max:2048|mimes:pdf,docx,doc,zip,rar,jpg,png',
            ]);
            $idMaster = SkripsiHelper::getIdMasterSkripsi();

            DB::beginTransaction();

            // Simpan data bimbingan
            $bimbingan = BimbinganSkripsi::create([
                'id_master' => $idMaster,
                'tanggal_waktu' => $request->tanggal,
                'permasalahan' => $request->permasalahan,
                'metode' => $request->metode,
                'bimbingan_to' => $request->pembimbing,
                'solusi_permasalahan' => null,
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
                    try {
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
                    } catch (\Exception $e) {
                        Log::error('Gagal upload file bimbingan', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        DB::rollBack();
                        return back()->with('error', 'Gagal upload file pendukung.');
                    }
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
            dd($e->getMessage());
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

    public function edit($id)
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
            'permasalahan' => 'required|string',
            'metode' => 'required|string',
            'filePendukung.*' => 'nullable|file|max:2048|mimes:pdf,docx,doc,zip,rar,jpg,png',
        ]);

        try {
            DB::beginTransaction();

            // Ambil data bimbingan yang akan diupdate
            $bimbingan = BimbinganSkripsi::findOrFail($id);

            // Update data bimbingan
            $bimbingan->update([
                'tanggal_waktu' => $request->tanggal,
                'permasalahan' => $request->permasalahan,
                'metode' => $request->metode,
                'catatan_mahasiswa' => $request->catatan,
            ]);

            Log::info('Bimbingan skripsi diperbarui', [
                'id_bimbingan' => $bimbingan->id,
                'id_master' => $bimbingan->id_master,
                'nim' => Auth::user()->email,
                'tanggal' => $request->tanggal,
                'permasalahan' => $request->permasalahan
            ]);

            // Simpan file baru jika ada
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

            return redirect()->back()->with('success', 'Jadwal bimbingan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui bimbingan skripsi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function logBook($nimEnkripsi)
    {
        try {

            $nimDekrip = Crypt::decryptString($nimEnkripsi);
            $nim = str_replace("stifar", "", $nimDekrip);

            $masterSkripsi = MasterSkripsi::select([
                'master_skripsi.*',
                'pb1.nama_lengkap as namaPembimbing1',
                'pb2.nama_lengkap as namaPembimbing2',
            ])
            ->leftJoin('pegawai_biodata as pb1', 'pb1.npp', '=', 'master_skripsi.pembimbing_1')
             ->leftJoin('pegawai_biodata as pb2', 'pb2.npp', '=', 'master_skripsi.pembimbing_2')
            ->where('nim', $nim)
            ->whereIn('status', [1, 2])
            ->first();

            if (!$masterSkripsi) {
                return back()->with('error', 'Data skripsi tidak ditemukan.');
            }

            $logBook = BimbinganSkripsi::where('id_master', $masterSkripsi->id)
                ->whereNotNull('solusi_permasalahan')
                ->get();

            $mhs = Mahasiswa::select([
                'mahasiswa.*',
                'program_studi.nama_prodi AS namaProdi',
            ])
            ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
            ->where('nim', $nim)
            ->first();
            if (!$mhs) {
                return back()->with('error', 'Data mahasiswa tidak ditemukan.');
            }

            $logo = asset('assets/images/logo/upload/logo_besar.png');

            // Generate PDF dengan mPDF
            $mpdf = new \Mpdf\Mpdf();
            $html = view('mahasiswa.skripsi.berkas.template_logbook', compact('logBook', 'mhs', 'masterSkripsi', 'logo'))->render();
            $mpdf->WriteHTML($html);

            return response($mpdf->Output('logbook.pdf', 'S'))->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            \Log::error('Gagal generate logbook PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            dd($e->getMessage());
            return back()->with('error', 'Gagal membuat logbook PDF.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $bimbingan = BimbinganSkripsi::findOrFail($id);
            $berkas = BerkasBimbingan::where('id_bimbingan', $bimbingan->id)->get();

            // Hapus file fisik jika ada
            foreach ($berkas as $file) {
                if ($file->file && \Storage::disk('public')->exists($file->file)) {
                    \Storage::disk('public')->delete($file->file);
                }
                $file->delete();
            }

            $bimbingan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data bimbingan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal menghapus bimbingan skripsi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data bimbingan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
