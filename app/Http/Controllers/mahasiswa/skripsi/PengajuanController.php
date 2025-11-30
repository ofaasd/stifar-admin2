<?php
namespace App\Http\Controllers\mahasiswa\skripsi;

use App\helpers;

use Carbon\Carbon;
use App\Models\Mahasiswa;
use App\Models\AktorSidang;
use App\Models\MasterRuang;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\SidangSkripsi;
use App\Models\PegawaiBiodatum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanJudulSkripsi;
use Illuminate\Support\Facades\Crypt;

class PengajuanController extends Controller
{
    protected $helpers;

    public function __construct()
    {
        $this->helpers = new helpers();
    }

    /**
    * menampilkan halaman dan data pengajuan mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function index()
    {
        $user  = Auth::user();
        $email = $user->email;
        $nim   = explode('@', $email)[0];
        $mhs = Mahasiswa::where('nim', $nim)->first();

        $mhs->sksTempuh = $this->helpers->getSksTempuh($nim);
        $mhs->ipk = $this->helpers->getIPK($nim);
        
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
        ->get();

        $arrIdMaster = $dataDosbim->pluck('id')->toArray();
        
        if($dataDosbim){
            $dataJudul = PengajuanJudulSkripsi::whereIn('id_master', $arrIdMaster)
                ->orderByRaw('status = 1 DESC') // status 1 dulu
                ->latest() // created_at desc
                ->take(2)
                ->get()
                ->map(function ($item) {
                    $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
                    return $item;
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
            $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");

            // Format tanggal menjadi "12 September 2025"
            if (!empty($item->tanggal)) {
                $item->tanggal = \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y');
            }
            
            $aktorSidang = AktorSidang::where('as', 'penguji')->get();

            // Ambil nama penguji dari npp yang dipisahkan koma
        if (!empty($item->penguji)) {
                $npps = array_filter(array_map('trim', explode(',', $item->penguji)));
                $namesByNpp = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap', 'npp')->toArray();

                foreach ($npps as $i => $npp) {
                    $nama = $namesByNpp[$npp] ?? '-';
                    $isAcc = $aktorSidang->contains(function($v) use ($item, $npp) {
                        return isset($v->sidang_id, $v->npp) && $v->sidang_id == $item->id && $v->npp == $npp;
                    });
                    // tambahkan icon checklist jika ada
                    $item->{'namaPenguji' . ($i + 1)} = $isAcc ? $nama . ' ✅ ' : $nama;
                }
                $item->jmlPenguji = count($npps);
            } else {
                $item->namaPenguji1 = '-';
                $item->namaPenguji2 = '-';
            }
                

            return $item;
        });

        $judulMahasiswa = MasterSkripsi::select([
            'mahasiswa.nama',
            'mahasiswa.nim',
            'pengajuan_judul_skripsi.judul',
            'pembimbing1.nama_lengkap as namaPembimbing1',
            'pembimbing2.nama_lengkap as namaPembimbing2',
        ])
        ->leftJoin('mahasiswa', 'mahasiswa.nim', '=', 'master_skripsi.nim')
        ->leftJoin('pengajuan_judul_skripsi', 'pengajuan_judul_skripsi.id_master', '=', 'master_skripsi.id')
        ->leftJoin('pegawai_biodata AS pembimbing1', 'master_skripsi.pembimbing_1', '=', 'pembimbing1.npp')
        ->leftJoin('pegawai_biodata AS pembimbing2', 'master_skripsi.pembimbing_2', '=', 'pembimbing2.npp')
        ->where('master_skripsi.status', 1)
        ->get();

        $isAllowSidang = MasterSkripsi::where('nim', $nim)->where('status', 2)->where('acc_1', 1)->where('acc_2', 1)->exists();

        $ruang = MasterRuang::all();

        return view('mahasiswa.skripsi.pengajuan.index', [
            'dataDosbim' => $dataDosbim,
            'dataJudul' => $dataJudul ?? [],
            'sidang' => $sidang,
            'mhs' => $mhs,
            'isAllowSidang' => $isAllowSidang,
            'ruang' => $ruang,
            'judulMahasiswa' => $judulMahasiswa,
        ]);
    }

    /**
    * mengambil data pengajuan judul mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
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

    /**
    * mengupdate judul skripsi.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
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

    /**
    * menampilkan spesifik data pengajuan judul.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function show($idEnkripsi, $isEdit = 0)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);
        
        $judul = PengajuanJudulSkripsi::select(
            'pengajuan_judul_skripsi.*',
            'master_bidang_minat.nama AS bidangMinat'
        )
        ->where('pengajuan_judul_skripsi.id',$id)
        ->leftJoin('master_bidang_minat', 'pengajuan_judul_skripsi.id_bidang_minat', '=', 'master_bidang_minat.id')
        ->first();
        
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

    /**
    * mengisikan waktu sidang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function inputWaktuSidang(Request $request, $idEnkripsi)
    {
        try {
            $id = $this->helpers->decryptId($idEnkripsi);
            $sidang = SidangSkripsi::find($id);

            if (!$sidang) {
                return response()->json(['status' => false, 'message' => 'Sidang not found'], 404);
            }

            $masterSkripsi = MasterSkripsi::find($sidang->skripsi_id);
            $mhs = Mahasiswa::where('nim', $masterSkripsi->nim)->first();

            $cekBentrok = SidangSkripsi::where('id', '!=', $sidang->id)
                ->where('ruang_id', $request->idRuang)
                ->where('tanggal', $request->tanggal)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('waktu_mulai', [$request->waktuMulai, $request->waktuSelesai])
                          ->orWhereBetween('waktu_selesai', [$request->waktuMulai, $request->waktuSelesai])
                          ->orWhere(function ($q) use ($request) {
                              $q->where('waktu_mulai', '<=', $request->waktuMulai)
                                ->where('waktu_selesai', '>=', $request->waktuSelesai);
                          });
                })
                ->exists();

            if ($cekBentrok) {
                return redirect()->back()->with('error', 'Waktu sidang bentrok dengan jadwal lain di ruang yang sama.');
            }

            $sidang->update([
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktuMulai,
                'waktu_selesai' => $request->waktuSelesai,
                'ruang_id' => $request->idRuang,
            ]);

            $formattedTanggal = Carbon::parse($request->tanggal)->translatedFormat('d/m/Y');
            $formattedWaktuMulai = date('H:i', strtotime($request->waktuMulai));
            $formattedWaktuSelesai = date('H:i', strtotime($request->waktuSelesai));
            $ruang = MasterRuang::find($request->idRuang);

            $dataWa['no_wa'] = $mhs->hp ?? '';
            $dataWa['pesan'] = "Yth. " . $mhs->nama . ",\n\n" .
                "Waktu sidang skripsi Anda telah dijadwalkan pada:\n" .
                "Tanggal: " . $formattedTanggal . "\n" .
                "Waktu: " . $formattedWaktuMulai . " - " . $formattedWaktuSelesai . "\n" .
                "Ruang: " . ($ruang->nama_ruang ?? '-') . "\n\n" .
                "Harap hadir tepat waktu.\n\n" .
                "Terima kasih.";
            $pesan = helpers::send_wa($dataWa);

            return redirect()->back()->with('success', 'Waktu sidang updated successfully');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
    * mencetak pengantar sidang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
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
            ->where('sidang.id', $idSidang)
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

            $jenisSidang = $sidang->jenis == 1 ? 'terbuka' : 'tertutup';
            // Generate PDF dengan mPDF
            $filename = $sidang->nim . '_sidang_' . $jenisSidang . '.pdf';

            $mpdf = new \Mpdf\Mpdf();
            $html = view('mahasiswa.skripsi.pengajuan.print-sidang', compact('sidang', 'logo', 'filename'))->render();
            $mpdf->WriteHTML($html);

            // Ubah nama file output menjadi nim_sidang_jenis.pdf
            return response($mpdf->Output($filename, 'S'))->header('Content-Type', 'application/pdf');

        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
    * mencetak persetujuan proposal.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
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
            ->where('sidang.id', $idSidang)
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

    public function printBeritaAcara(Request $request)
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
                $sidang->hari = Carbon::parse($sidang->tanggal)->translatedFormat('l') ?? '-';
                $sidang->date = Carbon::parse($sidang->tanggal)->format('d');
                $sidang->bulan = Carbon::parse($sidang->tanggal)->translatedFormat('F') ?? '-';
                $sidang->tahun = Carbon::parse($sidang->tanggal)->format('Y');

                $npps = explode(',', $sidang->penguji);
                $nomorAktor = 0;
                $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
                foreach ($names as $i => $nama) {
                    $sidang->{'namaPenguji' . ($i + 1)} = $nama;
                    $nomorAktor += $i + 1;
                }

                $sidang->{'namaPenguji' . ($nomorAktor + 1)} = $sidang->namaPembimbing1 ?? '-';
                $sidang->{'namaPenguji' . ($nomorAktor + 2)} = $sidang->namaPembimbing2 ?? '-';
            }

            $logo = asset('assets/images/logo/upload/logo_besar.png');

            $teks = $sidang->jenis == 1 ? 'sempro' : 'hasil';
            $formattedSidang = Carbon::parse($sidang->tanggal)->translatedFormat('d F Y');

            // Generate PDF dengan mPDF
            $mpdf = new \Mpdf\Mpdf();
            $html = '';
            if($sidang->jenis == 1) {
                $html = view('mahasiswa.skripsi.pengajuan.sempro.pdf-berita-acara', compact('sidang', 'logo', 'formattedSidang'))->render();
            } else {
                $html = view('mahasiswa.skripsi.pengajuan.hasil.pdf-berita-acara', compact('sidang', 'logo', 'formattedSidang'))->render();
            }
            $mpdf->WriteHTML($html);

            $filename = $sidang->nim . '_berita_acara_'.  $teks . '.pdf';
            return response($mpdf->Output($filename, 'S'))->header('Content-Type', 'application/pdf');

        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function printLembarNilaiPenguji(Request $request)
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
            ->where('sidang.id', $idSidang)
            ->first();

            if($sidang)
            {
                $npps = explode(',', $sidang->penguji);
                $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
                foreach ($names as $i => $nama) {
                    $sidang->{'namaPenguji' . ($i + 1)} = $nama;
                }

                $sidang->jmlPenguji = count($npps);
            }
            
            $logo = asset('assets/images/logo/upload/logo_besar.png');

            $teks = $sidang->jenis == 1 ? 'sempro' : 'hasil';
            $formattedSidang = Carbon::parse($sidang->tanggal)->translatedFormat('d F Y');

            // Generate PDF dengan mPDF
            $mpdf = new \Mpdf\Mpdf();
            $html = '';
            if($sidang->jenis == 1) {
                $html = view('mahasiswa.skripsi.pengajuan.sempro.pdf-nilai-penguji', compact('sidang', 'logo', 'formattedSidang'))->render();
            } else {
                $html = view('mahasiswa.skripsi.pengajuan.hasil.pdf-lembar-nilai', compact('sidang', 'logo', 'formattedSidang'))->render();
            }
            $mpdf->WriteHTML($html);

            $filename = $sidang->nim . '_lembar_nilai_'.  $teks . '.pdf';
            return response($mpdf->Output($filename, 'S'))->header('Content-Type', 'application/pdf');

        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function printLembarRekapNilai(Request $request)
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
            ->where('sidang.id', $idSidang)
            ->first();

            if($sidang)
            {
                $npps = explode(',', $sidang->penguji);
                $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
                foreach ($names as $i => $nama) {
                    $sidang->{'namaPenguji' . ($i + 1)} = $nama;
                }

                $sidang->jmlPenguji = count($npps);
            }
            
            $logo = asset('assets/images/logo/upload/logo_besar.png');

            $teks = $sidang->jenis == 1 ? 'sempro' : 'hasil';
            $formattedSidang = Carbon::parse($sidang->tanggal)->translatedFormat('d F Y');

            // Generate PDF dengan mPDF
            $mpdf = new \Mpdf\Mpdf();
            $html = '';
            if($sidang->jenis == 1) {
                $html = view('mahasiswa.skripsi.pengajuan.sempro.pdf-rekap-nilai', compact('sidang', 'logo', 'formattedSidang'))->render();
            } else {
                $html = view('mahasiswa.skripsi.pengajuan.hasil.pdf-lembar-rekap', compact('sidang', 'logo', 'formattedSidang'))->render();
            }
            $mpdf->WriteHTML($html);

            $filename = $sidang->nim . '_rekap_nilai_'.  $teks . '.pdf';
            return response($mpdf->Output($filename, 'S'))->header('Content-Type', 'application/pdf');

        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function printPenilaianPembimbing(Request $request)
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
            ->where('sidang.id', $idSidang)
            ->first();

            if($sidang)
            {
                $npps = explode(',', $sidang->penguji);
                $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
                foreach ($names as $i => $nama) {
                    $sidang->{'namaPenguji' . ($i + 1)} = $nama;
                }

                $sidang->jmlPenguji = count($npps);
            }
            
            $logo = asset('assets/images/logo/upload/logo_besar.png');

            $teks = $sidang->jenis == 1 ? 'sempro' : 'hasil';
            $formattedSidang = Carbon::parse($sidang->tanggal)->translatedFormat('d F Y');

            // Generate PDF dengan mPDF
            $mpdf = new \Mpdf\Mpdf();
            $html = '';
            $html = view('mahasiswa.skripsi.pengajuan.sempro.pdf-nilai-pembimbing', compact('sidang', 'logo', 'formattedSidang'))->render();
            $mpdf->WriteHTML($html);

            $filename = $sidang->nim . '_penilaian_pembimbing_'.  $teks . '.pdf';
            return response($mpdf->Output($filename, 'S'))->header('Content-Type', 'application/pdf');

        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
