<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\helpers;
use Carbon\Carbon;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\AktorSidang;
use App\Models\MasterRuang;
use App\Models\TahunAjaran;
use App\Models\master_nilai;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\SidangSkripsi;
use App\Models\PenontonSidang;
use App\Models\PegawaiBiodatum;
use App\Models\PreferensiSidang;
use App\Http\Controllers\Controller;
use App\Models\GelombangSidangSkripsi;

class SidangController extends Controller
{
    /**
    * menampilkan halaman dan data sidang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function index()
    {   
        $tahunAjaran = TahunAjaran::all();
        $ruang = MasterRuang::all();
        $pegawai = PegawaiBiodatum::all();
        $prodi = Prodi::all();
        
        $mahasiswaSkripsi = MasterSkripsi::select([
            'mahasiswa.nim',
            'mahasiswa.nama',
            'pengajuan_judul_skripsi.judul',
            'master_skripsi.id AS idMasterSkripsi'
        ])
        ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
        ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
        ->where('master_skripsi.status', 2)
        ->where('pengajuan_judul_skripsi.status', 1)
        ->whereNotIn('master_skripsi.id', function($query) {
            $query->select('skripsi_id')->from('sidang');
        })
        ->get();

        $sidang = SidangSkripsi::all();
        $statusSidang = $sidang->pluck('status')->unique()->mapWithKeys(function ($item) {
            $labels = [
                0 => 'Pengajuan',
                1 => 'Selesai',
                2 => 'Diterima',
            ];
            return [$item => $labels[$item] ?? 'Unknown'];
        });

        return view('dosen.skripsi.sidang.index', compact('tahunAjaran', 'ruang', 'pegawai', 'mahasiswaSkripsi', 'statusSidang', 'prodi'));
    }

    /**
    * menyimpan data sidang mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function store(Request $request)
    {
        try {
            $sidang = SidangSkripsi::create([
                'skripsi_id'    => $request->masterSkripsiId,
                'gelombang_id'  => $request->gelombangId,
                'tanggal'       => $request->tanggal,
                'waktu_mulai'   => $request->waktuMulai,
                'waktu_selesai' => $request->waktuSelesai,
                'ruang_id'       => $request->ruangId,
                'penguji'       => isset($request->penguji) ? implode(',', $request->penguji) : null,
                'jenis'        => $request->jenisSidang,
                'status'        => 2,
            ]);

            return redirect()->back()->with('success', 'Jadwal sidang berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
    * mengambil data sidang mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function getDataPeserta(Request $request)
    {
        try {
            $prodi = $request->input('prodi') ?? null;

            $data = SidangSkripsi::select([
                'sidang.id',
                'sidang.tanggal',
                'sidang.waktu_mulai AS waktuMulai',
                'sidang.waktu_selesai AS waktuSelesai',
                'sidang.penguji',
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
            ->when($prodi !== null, function ($query) use ($prodi) {
                return $query->where('mahasiswa.id_program_studi', $prodi);
            })
            ->orderBy('sidang.created_at', 'desc')
            ->get()
            ->map(function($item) {
                // Format tanggal menjadi "12 September 2025"
                if (!empty($item->tanggal)) {
                    $carbonTanggal = \Carbon\Carbon::parse($item->tanggal);
                    $item->tanggal = $carbonTanggal->translatedFormat('d/m/Y');
                    if ($carbonTanggal->isToday()) {
                        $item->tanggal .= ' <span class="badge badge-info">Sidang hari ini</span>';
                    }else if ($carbonTanggal->isPast() && $item->status != 1) {
                        $item->tanggal .= ' <span class="badge badge-success">Waktu sudah terlewati</span>';
                    }
                }

                return $item;
            });

            if (empty($data)) {
                return response()->json([
                    'draw' => request('draw'),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ]);
            }

            return \DataTables::of($data)
                ->addColumn('mahasiswa', function($row) {
                    return $row->nim . ' - ' . $row->nama;
                })
                ->addColumn('pembimbing', function($row) {
                    $list = '';
                    $pembimbings = [];
                    $npps = [];

                    if ($row->namaPembimbing1) {
                        $pembimbings[] = $row->namaPembimbing1;
                        $npps[] = $row->pembimbing_1 ?? '-';
                    }
                    if ($row->namaPembimbing2) {
                        $pembimbings[] = $row->namaPembimbing2;
                        $npps[] = $row->pembimbing_2 ?? '-';
                    }

                    if (count($pembimbings) > 0) {
                        foreach ($pembimbings as $i => $name) {
                            $npp = isset($npps[$i]) ? $npps[$i] : '-';
                            $list .= '<strong>' . ($i + 1) . '</strong>. ' . $name . ' <small>(' . $npp . ')</small><br>';
                        }
                        return $list;
                    } else {
                        return '-';
                    }
                })
                ->editColumn('penguji', function($row) {
                    if (empty($row->penguji)) {
                        return '-';
                    }
                    $npps = array_filter(array_map('trim', explode(',', $row->penguji)));
                    $pegawais = PegawaiBiodatum::whereIn('npp', $npps)->get()->keyBy('npp');
                    $list = '';
                    foreach ($npps as $i => $npp) {
                        $pegawai = $pegawais->has($npp) ? $pegawais->get($npp) : null;
                        $name = $pegawai ? $pegawai->nama_lengkap : '-';
                        $list .= '<strong>' . ($i + 1) . '</strong>. ' . $name . ' <small>(' . $npp . ')</small><br>';
                    }
                    return $list;
                })
                ->addColumn('waktu', function($row) {
                    return $row->ruangan . ' (' . $row->waktuMulai . ' - ' . $row->waktuSelesai . ')';
                })
                ->addColumn('actions', function($row) {
                    return '<button type="button" class="btn btn-sm btn-warning" onclick="showEditJadwalModal('.$row->id.', this)">
                        <i class="bi bi-eye"></i>
                    </button>';
                })
                ->editColumn('status', function($row) {
                    $statusLabels = [
                        0 => '<span class="badge badge-secondary">Pengajuan</span>',
                        1 => '<span class="badge badge-success">Selesai</span>',
                        2 => '<span class="badge badge-primary">Diterima</span>',
                    ];
                    return $statusLabels[$row->status] ?? '<span class="badge badge-dark">Unknown</span>';
                })
                ->rawColumns(['actions', 'judul', 'pembimbing', 'waktu', 'status', 'mahasiswa', 'penguji', 'tanggal'])
                ->addIndexColumn()
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'draw' => request('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ], 500);
        }
    }

    /**
    * menampilkan spesifik data sidang mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function getDetail($id)
    {
        try {
            $sidang = SidangSkripsi::select([
                'sidang.id',
                'sidang.tanggal',
                'sidang.waktu_mulai AS waktuMulai',
                'sidang.waktu_selesai AS waktuSelesai',
                'sidang.ruang_id AS ruangId',
                'sidang.penguji',
                'sidang.status',
                'sidang.jenis',
                'sidang.proposal',
                'sidang.presentasi',
                'sidang.pendukung',
                'sidang.berita_acara AS beritaAcara',
                'sidang.nilai_akhir AS nilaiAkhir',
                'master_ruang.nama_ruang AS ruangan',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'master_skripsi.pembimbing_1 AS nppPembimbing1',
                'master_skripsi.pembimbing_2 AS nppPembimbing2',
                'pembimbing1.nama_lengkap AS namaPembimbing1',
                'pembimbing2.nama_lengkap AS namaPembimbing2',
            ])
            ->leftJoin('master_skripsi', 'sidang.skripsi_id', '=', 'master_skripsi.id')
            ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
            ->leftJoin('master_ruang', 'sidang.ruang_id', '=', 'master_ruang.id')
            ->leftJoin('pegawai_biodata AS pembimbing1', 'master_skripsi.pembimbing_1', '=', 'pembimbing1.npp')
            ->leftJoin('pegawai_biodata AS pembimbing2', 'master_skripsi.pembimbing_2', '=', 'pembimbing2.npp')
            ->where('sidang.id', $id)
            ->first();

            // Tambahkan pengujiIds (array NPP penguji)
            if ($sidang && !empty($sidang->penguji)) {
                $sidang->pengujiIds = explode(',', $sidang->penguji);
            } else {
                $sidang->pengujiIds = [];
            }

            $aktorSidang = AktorSidang::where('sidang_id', $sidang->id)->get();

            // Ambil nilai untuk setiap penguji (dinamis)
            $nilaiPenguji = [];
            if (!empty($sidang->pengujiIds)) {
                foreach ($sidang->pengujiIds as $pengujiNpp) {
                    $nilai = $aktorSidang->where('npp', $pengujiNpp)->first();
                    $nilaiPenguji[$pengujiNpp] = $nilai ? $nilai->nilai_akhir : 0;
                }
            }
            $sidang->nilaiPenguji = $nilaiPenguji;

            return response()->json([
                'success' => true, 
                'data' => $sidang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
    * mengupdate jadwal data sidang mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function updateJadwal(Request $request, $id)
    {
        try {
            $sidang = SidangSkripsi::findOrFail($id);
            $sidang->update([
                'tanggal'       => $request->tanggal,
                'waktu_mulai'   => $request->waktuMulai,
                'waktu_selesai' => $request->waktuSelesai,
                'ruang_id'      => $request->ruangId,
                'penguji'       => isset($request->penguji) ? implode(',', $request->penguji) : null,
                'status'        => 2,
                'acc_at'       => now(),
            ]);

            $pegawai = PegawaiBiodatum::whereIn('npp', $sidang->penguji)->get();

            $teksNamaPenguji = '';
            foreach ($pegawai as $index => $p) {
                $teksNamaPenguji .= ($index + 1) . '. ' . $p->nama_lengkap . ' (' . $p->npp . ')' . "\n";
            }

            // Pastikan semua input ada sebelum di-convert, berikan fallback jika kosong/tidak valid
            $formattedTanggal = '-';
            if (!empty($request->tanggal)) {
                try {
                    $formattedTanggal = Carbon::parse($request->tanggal)->translatedFormat('d/m/Y');
                } catch (\Exception $e) {
                    // jika parsing gagal, simpan nilai mentah sebagai fallback
                    $formattedTanggal = $request->tanggal;
                }
            }

            $formattedWaktuMulai = '-';
            if (!empty($request->waktuMulai) && strtotime(trim($request->waktuMulai)) !== false) {
                $formattedWaktuMulai = date('H:i', strtotime(trim($request->waktuMulai)));
            } elseif (!empty($request->waktuMulai)) {
                $formattedWaktuMulai = $request->waktuMulai;
            }

            $formattedWaktuSelesai = '-';
            if (!empty($request->waktuSelesai) && strtotime(trim($request->waktuSelesai)) !== false) {
                $formattedWaktuSelesai = date('H:i', strtotime(trim($request->waktuSelesai)));
            } elseif (!empty($request->waktuSelesai)) {
                $formattedWaktuSelesai = $request->waktuSelesai;
            }

            $ruang = MasterRuang::find($request->ruangId);
            
            $mhs = MasterSkripsi::where('id', $sidang->skripsi_id)->first();
            $dataWa['no_wa'] = $mhs->hp ?? '';
            $dataWa['pesan'] ="*MYSTIFAR - Pengajuan Sidang*\n\n"
                . "Halo, " . ($mhs->nama ?? '-') . ",\n\n"
                ."NIM: ".$mhs->nim."\n\n"
                ."Jadwal sidang skripsi Anda telah distujui dengan rincian sebagai berikut:\n"
                ."Penguji: \n"
                .$teksNamaPenguji;

            if (!empty($formattedTanggal) && $formattedTanggal != '-') {
                $dataWa['pesan'] .= "Tanggal: " . $formattedTanggal . "\n";
            }

            if ((!empty($formattedWaktuMulai) && $formattedTanggal != '-') && (!empty($formattedWaktuSelesai) && $formattedWaktuSelesai != '-')) {
                $dataWa['pesan'] .= "Waktu: " . $formattedWaktuMulai . " - " . $formattedWaktuSelesai . "\n";
            }

            if (isset($ruang) && !empty($ruang) && !empty($ruang->nama_ruang)) {
                $dataWa['pesan'] .= "Ruang: " . $ruang->nama_ruang . "\n\n";
            }

            $dataWa['pesan'] .= "Harap hadir tepat waktu dan persiapkan diri Anda dengan baik.\n\n"
                ."Terima kasih.\n"
                ."- Admin Mystifar";

            $pesan = helpers::send_wa($dataWa);
            
            return redirect()->back()->with('success', 'Jadwal sidang berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function update(Request $request, GelombangSidangSkripsi $gelombang)
    {
        $request->validate([
            'nama' => 'required|string',
            'id_tahun_ajaran' => 'required|exists:tahun_ajarans,id',
            'kuota' => 'required|integer|min:1',
            'tanggal_mulai_daftar' => 'required|date',
            'tanggal_selesai_daftar' => 'required|date',
            'tanggal_mulai_pelaksanaan' => 'required|date',
            'tanggal_selesai_pelaksanaan' => 'required|date',
        ]);

        $ta = TahunAjaran::findOrFail($request->id_tahun_ajaran);
        $periode = $ta->periode_formatted;

        $gelombang->update([
            'nama' => $request->nama,
            'periode' => $periode,
            'kuota' => $request->kuota,
            'tanggal_mulai_daftar' => $request->tanggal_mulai_daftar,
            'tanggal_selesai_daftar' => $request->tanggal_selesai_daftar,
            'tanggal_mulai_pelaksanaan' => $request->tanggal_mulai_pelaksanaan,
            'tanggal_selesai_pelaksanaan' => $request->tanggal_selesai_pelaksanaan,
            'id_tahun_ajaran' => $request->id_tahun_ajaran
        ]);

        return redirect()->route('sidang.index')->with('success', 'Gelombang berhasil diperbarui');
    }

    /**
    * penilaian sidang mahasiswa.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function penilaianSidang(Request $request, $id)
    {
        try {
            $sidang = SidangSkripsi::select([
                    'sidang.id',
                    'sidang.jenis',
                    'sidang.skripsi_id',
                    'sidang.nilai_akhir',
                    'master_skripsi.nim',
                ])
                ->leftJoin('master_skripsi', 'sidang.skripsi_id', '=', 'master_skripsi.id')
                ->where('sidang.id', $id)
                ->first();

            if (!$sidang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sidang tidak ditemukan.'
                ], 404);
            }

            $nilaiAkhir = $request->nilai ?? null;

            if($sidang->nilai_akhir)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Nilai sidang sudah diinput sebelumnya.'
                ], 400);
            }

            $folder = 'berkas-sidang/berita-acara';

            $beritaAcaraFile = $request->file('beritaAcara');
            $namaFileBeritaAcara = null;
            if ($beritaAcaraFile) {
                $targetDir = public_path($folder);
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $namaFileBeritaAcara = $sidang->nim.'_berita_acara_'.time().'.'.$beritaAcaraFile->getClientOriginalExtension();
                $beritaAcaraFile->move($targetDir, $namaFileBeritaAcara);
            }

            // Update sidang
            SidangSkripsi::where('id', $sidang->id)->update([
                'status' => $request->status,
                'nilai_akhir' => $nilaiAkhir,
                'berita_acara' => $namaFileBeritaAcara,
            ]);
            
            MasterSkripsi::where('id', $sidang->skripsi_id)->update([
                'status' => 1, // Update status skripsi menjadi 3 (selesai sidang)
            ]);

            $nhuruf = \App\helpers::getNilaiHuruf($nilaiAkhir);
            $tahunAjaran = TahunAjaran::where('status', 'Aktif')->first();
            $mhs = Mahasiswa::where('nim', $sidang->nim)->first();
            $teksJenisSidang = $sidang->jenis == 1 ? 'Seminar Proposal' : 'Seminar Hasil';

            $dataWa['no_wa'] = $mhs->hp ?? '';
            $dataWa['pesan'] = "*MYSTIFAR - Nilai Sidang*\n\n"
                . "Halo, " . ($mhs->nama ?? '-') . ",\n\n"
                ."Nilai ". $teksJenisSidang ." Anda telah diumumkan dengan rincian sebagai berikut:\n"
                ."Nilai Akhir: " . $nilaiAkhir . "\n"
                ."Nilai Huruf: " . $nhuruf . "\n\n"
                ."Selamat atas pencapaian Anda!\n\n"
                ."Terima kasih.\n"
                ."- Admin Mystifar";

            $pesan = helpers::send_wa($dataWa);

            if ($sidang->jenis == 1) {
                master_nilai::create([
                    'nim' => $sidang->nim,
                    'id_matkul' => 35,
                    'id_tahun' => $tahunAjaran ? $tahunAjaran->id : null,
                    'id_mhs' => $mhs->user_id,
                    'nakhir' => $nilaiAkhir,
                    'nhuruf' => $nhuruf,
                    'publish_tugas' => 1,
                    'publish_uts' => 1,
                    'publish_uas' => 1,
                    'validasi_tugas' => 1,
                    'validasi_uts' => 1,
                    'validasi_uas' => 1,
                ]);
            } elseif ($sidang->jenis == 2) {
                master_nilai::create([
                    'nim' => $sidang->nim,
                    'id_matkul' => 83,
                    'id_tahun' => $tahunAjaran ? $tahunAjaran->id : null,
                    'id_mhs' => $mhs->user_id,
                    'nakhir' => $nilaiAkhir,
                    'nhuruf' => $nhuruf,
                    'publish_tugas' => 1,
                    'publish_uts' => 1,
                    'publish_uas' => 1,
                    'validasi_tugas' => 1,
                    'validasi_uts' => 1,
                    'validasi_uas' => 1,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Nilai Sidang berhasil disimpan.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
    * mencetak lembar hadir sidang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function printLembarHadir(Request $request)
    {
        try {
            $idSidang = $request->id;

            $sidang = SidangSkripsi::select([
                'sidang.id',
                'sidang.penguji',
                'pengajuan_judul_skripsi.judul',
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

            $penonton = [];
            if($sidang)
            {
                $sidang->tanggal = \Carbon\Carbon::parse($sidang->tanggal)->translatedFormat('d/m/Y');
                $npps = explode(',', $sidang->penguji);
                $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
                foreach ($names as $i => $nama) {
                    $sidang->{'namaPenguji' . ($i + 1)} = $nama;
                }

                $ta = TahunAjaran::where('status', 'Aktif')->first();
                if ($ta) {
                    $tahunAwal = \Carbon\Carbon::parse($ta->tgl_awal)->format('Y');
                    $tahunAkhir = \Carbon\Carbon::parse($ta->tgl_akhir)->format('Y');
                    $sidang->ta = $tahunAwal . '/' . $tahunAkhir;
                } else {
                    $sidang->ta = '';
                }

                $penonton = PenontonSidang::select([
                    'mahasiswa.nim',
                    'mahasiswa.nama',
                ])
                ->leftJoin('mahasiswa', 'penonton_sidang.nim', '=', 'mahasiswa.nim')
                ->where('id_sidang', $sidang->id)
                ->get();
            }

            $logo = asset('assets/images/logo/upload/logo_besar.png');

            // Generate PDF dengan mPDF
            $mpdf = new \Mpdf\Mpdf();
            $html = view('mahasiswa.skripsi.pengajuan.print-daftar-hadir', compact('sidang', 'logo', 'penonton'))->render();
            $mpdf->WriteHTML($html);

            $filename = $sidang->nim . '_daftar_hadir.pdf';
            return response($mpdf->Output($filename, 'S'))->header('Content-Type', 'application/pdf');

        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
    * mencetak peserta sidang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function printPeserta(Request $request)
    {
        try {
            $status = $request->status;
            $jenis = $request->jenis;
            $fromDate = $request->fromDate;
            $toDate = $request->toDate;

            if($toDate < $fromDate)
            {
                return redirect()->back()->with('error', 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.');
            }

            $querySidang = SidangSkripsi::select([
                'sidang.id',
                'sidang.tanggal',
                'sidang.waktu_mulai AS waktuMulai',
                'sidang.waktu_selesai AS waktuSelesai',
                'sidang.penguji',
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
            ->orderBy('sidang.created_at', 'desc');

            if ($status && $status != 'all') {
                $querySidang->where('sidang.status', $status);
            }
            if ($jenis && $jenis != 'all') {
                $querySidang->where('sidang.jenis', $jenis);
            }
            if ($fromDate) {
                $querySidang->whereDate('sidang.tanggal', '>=', $fromDate);
            }
            if ($toDate) {
                $querySidang->whereDate('sidang.tanggal', '<=', $toDate);
            }

            $sidang = $querySidang->get()
            ->map(function($item) {
                // Format tanggal menjadi "12 September 2025"
                // if (!empty($item->tanggal)) {
                //     $carbonTanggal = \Carbon\Carbon::parse($item->tanggal);
                //     $item->tanggal = $carbonTanggal->translatedFormat('d/m/Y');
                // }

                return $item;
            });

            $logo = asset('assets/images/logo/upload/logo_besar.png');

            $title = "Daftar Peserta Sidang";
            $parts = [];

            $parts[] = 'Peserta Sidang';
            if (!empty($status) && $status !== 'all') {
                $statusLabels = [
                    0 => 'Pengajuan',
                    1 => 'Selesai',
                    2 => 'Diterima',
                ];
                $parts[] = 'Status: ' . ($statusLabels[$status] ?? $status);
            }

            if (!empty($jenis) && $jenis !== 'all') {
                $jenisLabel = [
                    1 => 'Seminar Proposal',
                    2 => 'Seminar Proposal',
                ];
                $parts[] = ($jenisLabel[$jenis] ?? $jenis);
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $parts[] = 'Periode: ' . \Carbon\Carbon::parse($fromDate)->translatedFormat('d/m/Y') . ' - ' . \Carbon\Carbon::parse($toDate)->translatedFormat('d/m/Y');
            } elseif (!empty($fromDate)) {
                $parts[] = 'Mulai: ' . \Carbon\Carbon::parse($fromDate)->translatedFormat('d/m/Y');
            } elseif (!empty($toDate)) {
                $parts[] = 'Sampai: ' . \Carbon\Carbon::parse($toDate)->translatedFormat('d/m/Y');
            }

            if (!empty($parts)) {
                $filterTitle = implode(' | ', $parts);
            }
            // Generate PDF dengan mPDF
            $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']); // landscape
            $html = view('dosen.skripsi.sidang.print', compact('sidang', 'logo', 'title', 'filterTitle'))->render();
            $mpdf->WriteHTML($html);

            $filename = $title . '.pdf';
            return response($mpdf->Output($filename, 'S'))->header('Content-Type', 'application/pdf');

        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(GelombangSidangSkripsi $gelombang)
    {
        $gelombang->delete();
        return redirect()->route('sidang.index')->with('success', 'Gelombang berhasil dihapus');
    }
}
