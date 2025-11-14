<?php

namespace App\Http\Controllers\dosen\akademik\skripsi;

use App\Models\Mahasiswa;
use App\Models\AktorSidang;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\PegawaiBiodatum;
use App\Models\BimbinganSkripsi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanJudulSkripsi;
use Illuminate\Support\Facades\Crypt;

class DosenBimbinganController extends Controller
{
    /**
    * menampilkan halaman bimbingan (dosen).
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function index()
    {
        $data = [
            'title' => 'Bimbingan',
        ];
        return view('dosen.akademik.skripsi.bimbingan.index', $data);
    }
    
    /**
    * mengambil data bimbingan spesifik.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function getData()
    {
        $cekUser = PegawaiBiodatum::where('user_id', Auth::id())->first();

        if(!$cekUser){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }

        $dosen = PegawaiBiodatum::where('pegawai_biodata.id', $cekUser->id)
        ->first();

        if(!$dosen){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }

        $nppDosen = $dosen->npp;

        $data = MasterSkripsi::select([
            'master_skripsi.id',
            'mahasiswa.nim',
            'mahasiswa.nama',
            'pengajuan_judul_skripsi.judul',
        ])
        ->where(function($query) use ($nppDosen) {
            $query->where('master_skripsi.pembimbing_1', $nppDosen)
                ->orWhere('master_skripsi.pembimbing_2', $nppDosen);
        })
        ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
        ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
        ->where('master_skripsi.status', '=', 2)
        ->where('pengajuan_judul_skripsi.status', '=', 1)
        ->get()
        ->map(function ($item) {
            $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
            return $item;
        });

        if (empty($data)) {
            return response()->json([
                'draw' => request('draw'), // draw dari DataTables request
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        return \DataTables::of($data)
            ->addColumn('mahasiswa', function($row) {
                return $row->nim . ' - ' . $row->nama;
            })
            ->addColumn('actions', function($row) {
                $btn = '<a href="' . route('akademik.skripsi.dosen.bimbingan.show', $row->idEnkripsi) . '" class="btn btn-sm btn-primary" id="btn-bimbingan">Lihat Bimbingan</a>';
                return $btn;
            })
            ->rawColumns(['actions', 'mahasiswa'])
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->make(true);

    }

    /**
    * menambilkan data spesifik bimbingan.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function show($idEnkripsi)
    {
        try {
            $idDekrip = Crypt::decryptString($idEnkripsi);
            $id = str_replace("stifar", "", $idDekrip);
            $masterSkripsi = MasterSkripsi::find($id);

            if (!$masterSkripsi) {
                return redirect()->back()->with('error', 'Data skripsi tidak ditemukan.');
            }

            if ($masterSkripsi) {
                $masterSkripsi->idEnkripsi = Crypt::encryptString($masterSkripsi->id . "stifar");
            }

            $judulSkripsi = PengajuanJudulSkripsi::select([
                'pengajuan_judul_skripsi.*',
                'master_bidang_minat.nama AS bidang_minat',
            ])
            ->where('id_master', $masterSkripsi->id)
            ->where('status', 1)
            ->leftJoin('master_bidang_minat', 'pengajuan_judul_skripsi.id_bidang_minat', '=', 'master_bidang_minat.id')
            ->first();

            $cekUser = PegawaiBiodatum::where('user_id', Auth::id())->first();
            if(!$cekUser){
                return response()->json(["message"  => "Data tidak ditemukan"]);
            }

            $dosen = PegawaiBiodatum::where('pegawai_biodata.id', $cekUser->id)
            ->first();

            if(!$dosen){
                return response()->json(["message"  => "Data tidak ditemukan"]);
            }

            $pembimbingKe = null;
            if ($masterSkripsi->pembimbing_1 == $dosen->npp) {
                $pembimbingKe = 1;
            } elseif ($masterSkripsi->pembimbing_2 == $dosen->npp) {
                $pembimbingKe = 2;
            }

            if($dosen)
            {
                $dosen->pembimbingKe = $pembimbingKe;
            }

            // Ambil semua data bimbingan mahasiswa dengan relasi
            $bimbingan = BimbinganSkripsi::where('id_master', $masterSkripsi->id)
            ->orderBy('tanggal_waktu', 'desc')
            ->get();

            $bimbingan = $bimbingan->map(function ($item) {
                $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
           
                // Parse waktu jika tersimpan dalam format tertentu
                if ($item->created_at) {
                    $datetime = \Carbon\Carbon::parse($item->created_at);
                    $item->tanggal_formatted = $datetime->format('d F Y');
                    $item->waktu_formatted = $datetime->format('H:i');
                }

                $item->bimbinganKe = PegawaiBiodatum::where('npp', $item->bimbingan_to)->value('nama_lengkap');
                
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
                    case 4:
                        $item->status_label = 'Ditolak';
                        break;
                    default:
                        $item->status_label = 'Unknown';
                }
                
                return $item;
            });

            // kalo sudah ada 6 bimbingan dengan file_dosen tidak null, maka bisa sidang
            $isSidang = $bimbingan->whereNotNull('solusi_permasalahan')
                ->count() >= 6 ? true : false;

            $mahasiswa = Mahasiswa::where('nim', $masterSkripsi->nim)->first();

            $data = [
                'title' => 'Detail Bimbingan ' . $mahasiswa->nama . ' (' . $mahasiswa->nim . ')',
                'bimbingan' => $bimbingan,
                'judulSkripsi' => $judulSkripsi,
                'mahasiswa' => $mahasiswa,
                'dosen' => $dosen,
                'isSidang' => $isSidang,
                'masterSkripsi' => $masterSkripsi,
            ];

            return view('dosen.akademik.skripsi.bimbingan.show', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
    * update data solusi permasalahan.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function update(Request $request, $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $bimbingan = BimbinganSkripsi::find($id);
        if (!$bimbingan) {
            return redirect()->back()->with('error', 'Data bimbingan tidak ditemukan.');
        }

        try {
            $request->validate([
                'solusiPermasalahan' => 'nullable|string',
            ]);

            $bimbingan->solusi_permasalahan = $request->input('solusiPermasalahan', $bimbingan->solusi_permasalahan);
            $bimbingan->save();

            return redirect()->back()->with('success', 'Data bimbingan berhasil diperbarui.');
        } catch (\Exception $e) { 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
    * update status bimbingan.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function updateStatus(Request $request, $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $bimbingan = BimbinganSkripsi::find($id);
        if (!$bimbingan) {
            return response()->json(['error' => 'Data bimbingan tidak ditemukan.'], 404);
        }

        $cekUser = PegawaiBiodatum::where('user_id', Auth::id())->first();

        if(!$cekUser){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }

        $dosen = PegawaiBiodatum::where('pegawai_biodata.id', $cekUser->id)
        ->first();

        if(!$dosen){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }

        $nppDosen = $dosen->npp;

        try {
            $request->validate([
            'status' => 'nullable|string',
            ]);

            $bimbingan->status = $request->input('status', $bimbingan->status);
            $bimbingan->bimbingan_to = $nppDosen;

            $bimbingan->save();

            return response()->json(['success' => true, 'message' => 'Data bimbingan berhasil diperbarui.']);
        } catch (\Exception $e) { 
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
    * persetujuan sidang untuk mahasiswa bimbingan.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function accSidang(Request $request, $idEnkripsi)
    {
        $request->validate([
            'pembimbingKe' => 'required|in:1,2',
        ]);

        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $masterSkripsi = MasterSkripsi::find($id);
        if (!$masterSkripsi) {
            return redirect()->back()->with('error', 'Data Skripsi tidak ditemukan.');
        }

        try {
            $masterSkripsi->update([
                'acc_' . $request->pembimbingKe => 1,
                'acc_' . $request->pembimbingKe . '_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Berhasil menyetujui Mahasiswa untuk sidang.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function penilaian(Request $request, $idEnkripsi)
    {
        try {
            $idDekrip = Crypt::decryptString($idEnkripsi);
            $id = str_replace("stifar", "", $idDekrip);

            $cekUser = PegawaiBiodatum::where('user_id', Auth::id())->first();
            if(!$cekUser){
                return response()->json(["message"  => "Data tidak ditemukan"]);
            }

            $dosen = PegawaiBiodatum::where('pegawai_biodata.id', $cekUser->id)
            ->first();

            if(!$dosen){
                return response()->json(["message"  => "Data tidak ditemukan"]);
            }

            $penilaian = AktorSidang::updateOrCreate(
                [
                    'sidang_id' => $id,
                    'npp' => $dosen->npp,
                    'as' => 'pembimbing'
                ],
                [
                    'konsistensi_penulisan' => $request->konsistensiPenulisan ?? 0,
                    'penelusuran' => $request->penelusuran ?? 0,
                    'kontribusi' => $request->kontribusi ?? 0,
                    'ketekunan' => $request->ketekunan ?? 0,
                    'penguasaan' => $request->penguasaan ?? 0,
                    'menemukan_relevansi' => $request->menemukanRelevansi ?? 0,
                    'jumlah_nillai' => $request->jumlahNilai ?? 0,
                    'nilai_akhir' => $request->nilaiAkhir ?? 0,
                    'status' => 0,
                ]
            );

            return response()->json(['success' => true, 'message' => 'Penilaian berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function validasiNilai(Request $request, $idEnkripsi)
    {
        try {
            $idDekrip = Crypt::decryptString($idEnkripsi);
            $id = str_replace("stifar", "", $idDekrip);

            $cekUser = PegawaiBiodatum::where('user_id', Auth::id())->first();
            if(!$cekUser){
                return response()->json(["message"  => "Data tidak ditemukan"]);
            }

            $dosen = PegawaiBiodatum::where('pegawai_biodata.id', $cekUser->id)
            ->first();

            if(!$dosen){
                return response()->json(["message"  => "Data tidak ditemukan"]);
            }

            $updated = AktorSidang::where([
                'sidang_id' => $id,
                'npp' => $dosen->npp,
                'as' => 'pembimbing'
            ])->update([
                'status' => 1,
            ]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Nilai berhasil divalidasi.']);
            } else {
                return response()->json(['error' => 'Data penilaian tidak ditemukan atau sudah divalidasi.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
    * upload file hasil bimbingan.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function uploadRevisi(Request $request, $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $bimbingan = BimbinganSkripsi::find($id);
        if (!$bimbingan) {
            return redirect()->back()->with('error', 'Data bimbingan tidak ditemukan.');
        }

        try {
            $request->validate([
                'fileRevisi' => 'required|file|mimes:pdf,doc,docx|max:2048',
            ]);

            if ($request->hasFile('fileRevisi')) {
                $file = $request->file('fileRevisi');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('bimbingan_revisi', $fileName, 'public');

                $bimbingan->file_dosen = $filePath;
                $bimbingan->save();
            }

            return redirect()->back()->with('success', 'File revisi berhasil diunggah.');
        } catch (\Exception $e) { 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
