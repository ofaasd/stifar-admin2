<?php
namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\SidangSkripsi;
use App\Http\Controllers\Controller;
use App\Models\PegawaiBiodatum;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanJudulSkripsi;
use Illuminate\Support\Facades\Crypt;
use App\Models\PengajuanBerkasSkripsi;

class PengajuanController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $email = $user->email;
        $nim   = explode('@', $email)[0];
        $dataDosbim = MasterSkripsi::where('nim', $nim)
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
        if($dataDosbim){
            $dataJudul = PengajuanJudulSkripsi::where('id_master', $dataDosbim->id)
                ->orderByRaw('status = 1 DESC') // status 1 dulu
                ->latest() // created_at desc
                ->take(2)
                ->get()
                ->map(function ($item) {
                    $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
                    return $item;
                });
        }
    
        if ($dataDosbim) {
            // ambil semua berkas terkait
            $berkas = PengajuanBerkasSkripsi::where('id_master', $dataDosbim->id)->get();
        
            // masukkan ke array dalam 1 row
            $dataDosbim->berkas = $berkas->mapWithKeys(function ($b) {
                return [
                    strtolower($b->kategori) => $b->nama_file
                ];
            });
        }

        $sidang = SidangSkripsi::select([
                'sidang.id',
                'sidang.tanggal',
                'sidang.waktu_mulai AS waktuMulai',
                'sidang.waktu_selesai AS waktuSelesai',
                'sidang.penguji',
                'sidang.jenis',
                'master_ruang.nama_ruang AS ruangan',
                'sidang.status',
                'sidang.acc_pembimbing1 AS accPembimbing1',
                'sidang.acc_pembimbing2 AS accPembimbing2',
                'gelombang_sidang_skripsi.nama AS namaGelombang',
                'gelombang_sidang_skripsi.periode',
                'pembimbing1.nama_lengkap AS namaPembimbing1',
                'pembimbing2.nama_lengkap AS namaPembimbing2',
                'pengajuan_judul_skripsi.judul',
                'master_skripsi.pembimbing_1',
                'master_skripsi.pembimbing_2',
                'mahasiswa.nama',
                'mahasiswa.nim'
            ])
            ->leftJoin('master_skripsi', 'sidang.skripsi_id', '=', 'master_skripsi.id')
            ->leftJoin('gelombang_sidang_skripsi', 'sidang.gelombang_id', '=', 'gelombang_sidang_skripsi.id')
            ->leftJoin('master_ruang', 'sidang.ruang_id', '=', 'master_ruang.id')
            ->leftJoin('pegawai_biodata AS pembimbing1', 'master_skripsi.pembimbing_1', '=', 'pembimbing1.npp')
            ->leftJoin('pegawai_biodata AS pembimbing2', 'master_skripsi.pembimbing_2', '=', 'pembimbing2.npp')
            ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
            ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
            ->where('pengajuan_judul_skripsi.status', 1)
            ->where('master_skripsi.nim', $nim)
            ->orderBy('sidang.created_at', 'desc')
            ->get()
            ->map(function($item) {
                // Format tanggal menjadi "12 September 2025"
                if (!empty($item->tanggal)) {
                    $item->tanggal = \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y');
                }

                // Ambil nama penguji dari npp yang dipisahkan koma
                if (!empty($item->penguji)) {
                    $npps = explode(',', $item->penguji);
                    $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
                    // Simpan nama penguji ke properti baru agar bisa dipakai di view
                    foreach ($names as $i => $nama) {
                        $item->{'namaPenguji' . ($i + 1)} = $nama;
                    }
                    $item->jmlPenguji = count($names);
                } else {
                    $item->namaPenguji1 = '-';
                    $item->namaPenguji2 = '-';
                }
                    

                return $item;
            });

        return view('mahasiswa.skripsi.pengajuan.index', [
            'dataDosbim' => $dataDosbim,
            'dataJudul' => $dataJudul ?? [],
            'sidang' => $sidang,
        ]);
    }

    public function getDataPengajuanJudul($id)
    {
        $data = PengajuanJudulSkripsi::find($id);
        $masterSkripsi = MasterSkripsi::select([
            'mahasiswa.nama',
            'mahasiswa.nim'
            ])
        ->leftJoin('mahasiswa', 'mahasiswa.nim', '=', 'master_skripsi.nim')
        ->where('master_skripsi.id', $data->id_master)
        ->first();

        $data->nama = $masterSkripsi->nama;
        $data->nim = $masterSkripsi->nim;
        return response()->json($data);
    }

    // update revisi pengajuan judul
    public function update($id, Request $request)
    {
        try {
            $data = PengajuanJudulSkripsi::find($id);
            $masterSkripsi = MasterSkripsi::find($data->id_master);

            if (!$data) {
                return response()->json(['status' => false, 'message' => 'Data not found'], 404);
            }

            $data->update([
                'judul' => $request->judul,
                'judul_eng' => $request->judulEnglish,
                'abstrak' => $request->abstrak,
                'catatan' => null,
                'status' => 0,
            ]);
            $masterSkripsi->update([
                'status' => 0
            ]);

            return response()->json(['status' => true, 'message' => 'Update successful']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($idEnkripsi, $isEdit = 0)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);
        
        $judul = PengajuanJudulSkripsi::find($id);
        $masterSkripsi = MasterSkripsi::where('master_skripsi.id', $judul->id_master)
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
        $mahasiswa = Mahasiswa::where('nim', $masterSkripsi->nim)->first();

        $data = [
            'title' => 'Detail Judul Skripsi',
            'judul'=> $judul,
            'masterSkripsi' => $masterSkripsi,
            'mahasiswa' => $mahasiswa,
            'isEdit' => $isEdit,
        ];

        return view('mahasiswa.skripsi.pengajuan.show', $data);
    }

    public function melengkapiJudul(Request $request)
    {
        try {
            $judul = PengajuanJudulSkripsi::find($request->idJudul);

            if (!$judul) {
                return response()->json(['status' => false, 'message' => 'Judul not found'], 404);
            }

            $judul->update([
                'abstrak' => $request->abstrak,
                'latar_belakang' => $request->latarBelakang,
                'rumusan_masalah' => $request->rumusanMasalah,
                'tujuan' => $request->tujuan,
                'metodologi' => $request->metodologi,
                'jenis_penelitian' => $request->jenisPenelitian,
            ]);

            return response()->json(['status' => true, 'message' => 'Update successful']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function printSidang(Request $request)
    {
        try {
            $idSidang = $request->id;

            $sidang = SidangSkripsi::select([
                'sidang.id',
                'sidang.tanggal',
                'sidang.waktu_mulai AS waktuMulai',
                'sidang.waktu_selesai AS waktuSelesai',
                'sidang.penguji',
                'sidang.jenis',
                'master_ruang.nama_ruang AS ruangan',
                'sidang.status',
                'gelombang_sidang_skripsi.nama AS namaGelombang',
                'gelombang_sidang_skripsi.periode',
                'pembimbing1.nama_lengkap AS namaPembimbing1',
                'pembimbing2.nama_lengkap AS namaPembimbing2',
                'pengajuan_judul_skripsi.judul',
                'master_skripsi.pembimbing_1',
                'master_skripsi.pembimbing_2',
                'mahasiswa.nama',
                'mahasiswa.nim'
            ])
            ->leftJoin('master_skripsi', 'sidang.skripsi_id', '=', 'master_skripsi.id')
            ->leftJoin('gelombang_sidang_skripsi', 'sidang.gelombang_id', '=', 'gelombang_sidang_skripsi.id')
            ->leftJoin('master_ruang', 'sidang.ruang_id', '=', 'master_ruang.id')
            ->leftJoin('pegawai_biodata AS pembimbing1', 'master_skripsi.pembimbing_1', '=', 'pembimbing1.npp')
            ->leftJoin('pegawai_biodata AS pembimbing2', 'master_skripsi.pembimbing_2', '=', 'pembimbing2.npp')
            ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
            ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
            ->where('pengajuan_judul_skripsi.status', 1)
            ->where('sidang.id', $idSidang)
            ->orderBy('sidang.created_at', 'desc')
            ->first();

            if($sidang)
            {
                $sidang->tanggal = \Carbon\Carbon::parse($sidang->tanggal)->translatedFormat('d/m/Y');
                $npps = explode(',', $sidang->penguji);
                $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
                foreach ($names as $i => $nama) {
                    $sidang->{'namaPenguji' . ($i + 1)} = $nama;
                }
            }

            $logo = asset('assets/images/logo/upload/logo_besar.png');

            // Generate PDF dengan mPDF
            $mpdf = new \Mpdf\Mpdf();
            $html = view('mahasiswa.skripsi.pengajuan.print-sidang', compact('sidang', 'logo'))->render();
            $mpdf->WriteHTML($html);

            // Ubah nama file output menjadi nim_sidang_jenis.pdf
            $jenisSidang = $sidang->jenis == 1 ? 'terbuka' : 'tertutup';
            $filename = $sidang->nim . '_sidang_' . $jenisSidang . '.pdf';
            return response($mpdf->Output($filename, 'S'))->header('Content-Type', 'application/pdf');

        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function printPersetujuanProposal(Request $request)
    {
        try {
            $idSidang = $request->id;

            $sidang = SidangSkripsi::select([
                'sidang.id',
                'sidang.tanggal',
                'sidang.waktu_mulai AS waktuMulai',
                'sidang.waktu_selesai AS waktuSelesai',
                'sidang.penguji',
                'sidang.jenis',
                'master_ruang.nama_ruang AS ruangan',
                'sidang.status',
                'gelombang_sidang_skripsi.nama AS namaGelombang',
                'gelombang_sidang_skripsi.periode',
                'pembimbing1.nama_lengkap AS namaPembimbing1',
                'pembimbing2.nama_lengkap AS namaPembimbing2',
                'pengajuan_judul_skripsi.judul',
                'master_skripsi.pembimbing_1',
                'master_skripsi.pembimbing_2',
                'mahasiswa.nama',
                'mahasiswa.nim'
            ])
            ->leftJoin('master_skripsi', 'sidang.skripsi_id', '=', 'master_skripsi.id')
            ->leftJoin('gelombang_sidang_skripsi', 'sidang.gelombang_id', '=', 'gelombang_sidang_skripsi.id')
            ->leftJoin('master_ruang', 'sidang.ruang_id', '=', 'master_ruang.id')
            ->leftJoin('pegawai_biodata AS pembimbing1', 'master_skripsi.pembimbing_1', '=', 'pembimbing1.npp')
            ->leftJoin('pegawai_biodata AS pembimbing2', 'master_skripsi.pembimbing_2', '=', 'pembimbing2.npp')
            ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
            ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
            ->where('pengajuan_judul_skripsi.status', 1)
            ->where('sidang.id', $idSidang)
            ->orderBy('sidang.created_at', 'desc')
            ->first();

            if($sidang)
            {
                $sidang->tanggal = \Carbon\Carbon::parse($sidang->tanggal)->translatedFormat('d/m/Y');
                $npps = explode(',', $sidang->penguji);
                $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
                foreach ($names as $i => $nama) {
                    $sidang->{'namaPenguji' . ($i + 1)} = $nama;
                }
            }

            $logo = asset('assets/images/logo/upload/logo_besar.png');

            // Generate PDF dengan mPDF
            $mpdf = new \Mpdf\Mpdf();
            $html = view('mahasiswa.skripsi.pengajuan.print-persetujuan-sempro', compact('sidang', 'logo'))->render();
            $mpdf->WriteHTML($html);

            $filename = $sidang->nim . '_persetujuan_sempro.pdf';
            return response($mpdf->Output($filename, 'S'))->header('Content-Type', 'application/pdf');

        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
