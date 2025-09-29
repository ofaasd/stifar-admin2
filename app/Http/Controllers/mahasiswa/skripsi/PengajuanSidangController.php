<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\RefHariSidang;
use App\Models\SidangSkripsi;
use App\Models\RefWaktuSidang;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\GelombangSidangSkripsi;
use App\Models\MasterSkripsi;
use App\Models\PreferensiSidang;

class PengajuanSidangController extends Controller
{
    public function index(){
        $gelombang = GelombangSidangSkripsi::select([
                'gelombang_sidang_skripsi.*',
                \DB::raw('(SELECT COUNT(*) FROM sidang WHERE sidang.gelombang_id = gelombang_sidang_skripsi.id) as jumlahPeserta')
            ])
            ->whereNotNull('tanggal_mulai_daftar')
            ->whereNotNull('tanggal_selesai_daftar')
            ->get();
            
        $waktuSidang = RefWaktuSidang::all();
        $hariSidang = RefHariSidang::all();

        return view('mahasiswa.skripsi.pengajuan.sidang.index', compact('gelombang','waktuSidang', 'hariSidang'));
    }

    public function store(Request $request)
    {
        try {
            $mhs = Mahasiswa::where('user_id', Auth::id())->first();
            $nim = $mhs->nim;

            $masterSkripsi = MasterSkripsi::where('nim', $nim)->where('status', 2)->first();

            // Ambil gelombang yang dipilih
            $gelombang = GelombangSidangSkripsi::select([
                'gelombang_sidang_skripsi.*',
                \DB::raw('(SELECT COUNT(*) FROM sidang WHERE sidang.gelombang_id = gelombang_sidang_skripsi.id) as jumlahPeserta')
            ])->where('id', $request->gelombang_sidang_id)->first();

            // Cek kuota
            if ($gelombang && $gelombang->kuota <= $gelombang->jumlahPeserta) {
                return redirect()->back()->with('error', 'Kuota gelombang sidang sudah terpenuhi.');
            }

            // Path folder
            $folder = 'berkas-sidang';

            // Handle proposalFinal
            $proposalFinal = $request->file('proposalFinal');
            $namaFileProposal = $nim.'_proposal_final_'.time().'.'.$proposalFinal->getClientOriginalExtension();
            $proposalFinal->move(public_path($folder), $namaFileProposal);

            // Handle kartuBimbingan
            $kartuBimbingan = $request->file('kartuBimbingan');
            $namaFileKartu = $nim.'_kartu_bimbingan_'.time().'.'.$kartuBimbingan->getClientOriginalExtension();
            $kartuBimbingan->move(public_path($folder), $namaFileKartu);

            // Handle presentasi
            $presentasi = $request->file('presentasi');
            $namaFilePresentasi = $nim.'_presentasi_'.time().'.'.$presentasi->getClientOriginalExtension();
            $presentasi->move(public_path($folder), $namaFilePresentasi);

            // Handle pendukung (optional)
            $pendukung = $request->file('pendukung');
            $namaFilePendukung = null;
            if ($pendukung) {
                $namaFilePendukung = $nim.'_pendukung_'.time().'.'.$pendukung->getClientOriginalExtension();
                $pendukung->move(public_path($folder), $namaFilePendukung);
            }

            $createData = [
                'jenis' => $request->jenisSidang,
                'proposal'=> $namaFileProposal,
                'kartu_bimbingan'=> $namaFileKartu,
                'presentasi'=> $namaFilePresentasi,
                'pendukung'=> $namaFilePendukung,
                'gelombang_id'  => $request->gelombang_sidang_id,
                'skripsi_id' => $masterSkripsi->id,
                'status' => 0,
            ];

            $createSidang = SidangSkripsi::create($createData);

            PreferensiSidang::create([
                'id_sidang' => $createSidang->id,
                'id_hari' => $request->hari,
                'id_waktu' => $request->waktu,
                'catatan' => $request->catatanTambahan,
            ]);

            return redirect()->route('mhs.skripsi.daftar.index')->with('success', 'Pengajuan sidang berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->route('mhs.skripsi.daftar.index')->with('error', 'Pengajuan sidang gagal disimpan: '.$e->getMessage());
        }
    }
}
