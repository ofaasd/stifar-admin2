<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Http\Controllers\Controller;
use App\Models\AktorSidang;
use App\Models\GelombangSidangSkripsi;
use App\Models\Mahasiswa;
use App\Models\master_nilai;
use App\Models\MasterRuang;
use App\Models\MasterSkripsi;
use App\Models\PegawaiBiodatum;
use App\Models\PenontonSidang;
use App\Models\PreferensiSidang;
use App\Models\SidangSkripsi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class SidangController extends Controller
{
    public function index()
    {   
        $gelombang = GelombangSidangSkripsi::select([
            'gelombang_sidang_skripsi.*',
            \DB::raw('(SELECT COUNT(*) FROM sidang WHERE sidang.gelombang_id = gelombang_sidang_skripsi.id) as jumlahPeserta')
        ])->get();

        $tahunAjaran = TahunAjaran::all();
        $ruang = MasterRuang::all();
        $pegawai = PegawaiBiodatum::all();
        
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

        return view('dosen.skripsi.sidang.index', compact('gelombang', 'tahunAjaran', 'ruang', 'pegawai', 'mahasiswaSkripsi'));
    }
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

    public function getDataPeserta($idGelombang = null)
    {
        try {
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
            ->when($idGelombang !== null, function ($query) use ($idGelombang) {
                $query->where('gelombang_sidang_skripsi.id', $idGelombang);
            })
            ->where('pengajuan_judul_skripsi.status', 1)
            ->where('sidang.acc_pembimbing1', 1)
            ->where('sidang.acc_pembimbing2', 1)
            ->orderBy('sidang.created_at', 'desc')
            ->get()
            ->map(function($item) {
                // Format tanggal menjadi "12 September 2025"
                if (!empty($item->tanggal)) {
                    $carbonTanggal = \Carbon\Carbon::parse($item->tanggal);
                    $item->tanggal = $carbonTanggal->translatedFormat('d/m/Y');
                    if (($carbonTanggal->isToday() || $carbonTanggal->isPast()) && $item->status != 1) {
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
                    if ($row->namaPembimbing1) {
                        $pembimbings[] = $row->namaPembimbing1;
                    }
                    if ($row->namaPembimbing2) {
                        $pembimbings[] = $row->namaPembimbing2;
                    }
                    if (count($pembimbings) > 0) {
                        foreach ($pembimbings as $i => $name) {
                            $list .= '<strong>' . ($i + 1) . '</strong>. ' . $name . '<br>';
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
                    $npps = explode(',', $row->penguji);
                    $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
                    $list = '';
                    foreach ($names as $i => $name) {
                        $list .= '<strong>'.($i + 1) . '</strong>. ' . $name . '<br>';
                    }
                    return $list;
                })
                ->addColumn('waktu', function($row) {
                    return $row->waktuMulai . ' - ' . $row->waktuSelesai;
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

    public function getDetail($id)
    {
        try {
            $sidang = SidangSkripsi::select([
                'sidang.id',
                'sidang.gelombang_id AS gelombangId',
                'sidang.tanggal',
                'sidang.waktu_mulai AS waktuMulai',
                'sidang.waktu_selesai AS waktuSelesai',
                'sidang.ruang_id AS ruangId',
                'sidang.penguji',
                'sidang.status',
                'sidang.jenis',
                'sidang.proposal',
                'sidang.kartu_bimbingan AS kartuBimbingan',
                'sidang.presentasi',
                'sidang.pendukung',
                'sidang.nilai_akhir AS nilaiAkhir',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'master_skripsi.pembimbing_1 AS nppPembimbing1',
                'master_skripsi.pembimbing_2 AS nppPembimbing2',
                'pembimbing1.nama_lengkap AS namaPembimbing1',
                'pembimbing2.nama_lengkap AS namaPembimbing2',
            ])
            ->leftJoin('master_skripsi', 'sidang.skripsi_id', '=', 'master_skripsi.id')
            ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
            ->leftJoin('pegawai_biodata AS pembimbing1', 'master_skripsi.pembimbing_1', '=', 'pembimbing1.npp')
            ->leftJoin('pegawai_biodata AS pembimbing2', 'master_skripsi.pembimbing_2', '=', 'pembimbing2.npp')
            ->where('sidang.id', $id)
            ->first();

            $preferensiSidang = PreferensiSidang::select([
                'preferensi_sidang.catatan',
                'ref_hari_sidang.nama AS hari',
                'ref_waktu_sidang.nama AS waktu',
            ])
            ->leftJoin('ref_hari_sidang', 'ref_hari_sidang.id','=', 'preferensi_sidang.id_hari')
            ->leftJoin('ref_waktu_sidang', 'ref_waktu_sidang.id','=', 'preferensi_sidang.id_waktu')
            ->where('preferensi_sidang.id_sidang', $sidang->id)
            ->first();

            // Gabungkan data preferensi ke $sidang
            if ($sidang && $preferensiSidang) {
                $sidang->catatan = $preferensiSidang->catatan;
                $sidang->hari = $preferensiSidang->hari;
                $sidang->waktu = $preferensiSidang->waktu;
            }

            // Tambahkan pengujiIds (array NPP penguji)
            if ($sidang && !empty($sidang->penguji)) {
                $sidang->pengujiIds = explode(',', $sidang->penguji);
            } else {
                $sidang->pengujiIds = [];
            }

            $nilaiPembimbing1 = null;
            $nilaiPembimbing2 = null;
            
            $aktorSidang = AktorSidang::where('sidang_id', $sidang->id)->get();
            foreach ($aktorSidang as $aktor) {
                if ($aktor->npp == $sidang->nppPembimbing1) {
                    $nilaiPembimbing1 = $aktor->nilai_akhir ?? 0;
                }
                if ($aktor->npp == $sidang->nppPembimbing2) {
                    $nilaiPembimbing2 = $aktor->nilai_akhir ?? 0;
                }
            }

            // Ambil nilai untuk setiap penguji (dinamis)
            $nilaiPenguji = [];
            if (!empty($sidang->pengujiIds)) {
                foreach ($sidang->pengujiIds as $pengujiNpp) {
                    $nilai = $aktorSidang->where('npp', $pengujiNpp)->first();
                    $nilaiPenguji[$pengujiNpp] = $nilai ? $nilai->nilai_akhir : 0;
                }
            }
            $sidang->nilaiPenguji = $nilaiPenguji;

            $sidang->nilaiPembimbing1 = $nilaiPembimbing1;
            $sidang->nilaiPembimbing2 = $nilaiPembimbing2;

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

    public function updateJadwal(Request $request, $id)
    {
        try {
            $sidang = SidangSkripsi::findOrFail($id);
            $sidang->update([
                'gelombang_id'       => $request->gelombangId,
                'tanggal'       => $request->tanggal,
                'waktu_mulai'   => $request->waktuMulai,
                'waktu_selesai' => $request->waktuSelesai,
                'ruang_id'      => $request->ruangId,
                'penguji'       => isset($request->penguji) ? implode(',', $request->penguji) : null,
                'status'        => 2,
            ]);
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

            // Update sidang
            SidangSkripsi::where('id', $sidang->id)->update([
                'status' => $request->status,
                'nilai_akhir' => $nilaiAkhir,
            ]);
            
            MasterSkripsi::where('id', $sidang->skripsi_id)->update([
                'status' => 1, // Update status skripsi menjadi 3 (selesai sidang)
            ]);

            $nhuruf = \App\helpers::getNilaiHuruf($nilaiAkhir);
            $tahunAjaran = TahunAjaran::where('status', 'Aktif')->first();
            $mhs = Mahasiswa::where('nim', $sidang->nim)->first();

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

    public function destroy(GelombangSidangSkripsi $gelombang)
    {
        $gelombang->delete();
        return redirect()->route('sidang.index')->with('success', 'Gelombang berhasil dihapus');
    }
}
