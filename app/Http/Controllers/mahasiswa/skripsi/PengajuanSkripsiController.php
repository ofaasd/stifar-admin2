<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\helpers;
use App\Helpers\HelperSkripsi\SkripsiHelper;
use App\Http\Controllers\Controller;
use App\Models\MasterSkripsi;
use App\Models\PengajuanJudulSkripsi;
use Auth;
use DB;
use Illuminate\Http\Request;

class PengajuanSkripsiController extends Controller
{
    public function index(){
       return view('mahasiswa.skripsi.pengajuan.skripsi.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'             => 'required|string|max:200',
            'judulEng'          => 'required|string|max:200',
            'judul2'            => 'required|string|max:200',
            'judulEng2'         => 'required|string|max:200',
            'abstrak'           => 'required|string|max:1000',
            'latar_belakang'    => 'required|string|max:2000',
            'rumusan_masalah'   => 'required|string|max:1500',
            'tujuan'            => 'required|string|max:1500',
            'metodologi'        => 'required|string|max:2000',
            'jenis_penelitian'  => 'required|in:kualitatif,kuantitatif,mixed,eksperimen,studi_kasus',
        ]);
    
        try {
            DB::beginTransaction();
    
            $user  = Auth::user();
            // $nim   = explode('@', $user->email)[0];
            $nim   = 'A11.2022.14777';
    
            // buat master skripsi
            $master = MasterSkripsi::create([
                'nim' => $nim,
                'status' => 0
            ]);
    
            // daftar judul yang akan dimasukkan
            $judulList = [
                [
                    'judul'     => $validated['judul'],
                    'judul_eng' => $validated['judulEng'],
                ],
                [
                    'judul'     => $validated['judul2'],
                    'judul_eng' => $validated['judulEng2'],
                ]
            ];
    
            foreach ($judulList as $judul) {
                PengajuanJudulSkripsi::create([
                    'id_master'        => $master->id,
                    'judul'            => $judul['judul'],
                    'judul_eng'        => $judul['judul_eng'],
                    'abstrak'          => $validated['abstrak'],
                    'latar_belakang'   => $validated['latar_belakang'],
                    'rumusan_masalah'  => $validated['rumusan_masalah'],
                    'tujuan'           => $validated['tujuan'],
                    'metodologi'       => $validated['metodologi'],
                    'jenis_penelitian' => $validated['jenis_penelitian'],
                    'status'           => 0,
                ]);
            }
    
            DB::commit();
    
            return redirect()
                ->route('mhs.pengajuan.index')
                ->with('success', 'Pengajuan judul skripsi berhasil disimpan.');
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            \Log::error('Gagal menyimpan pengajuan judul skripsi', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'user'    => Auth::id()
            ]);
    
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

}
