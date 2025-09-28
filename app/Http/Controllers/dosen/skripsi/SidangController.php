<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Http\Controllers\Controller;
use App\Models\GelombangSidangSkripsi;
use App\Models\MasterRuang;
use App\Models\MasterSkripsi;
use App\Models\PegawaiBiodatum;
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
        ->where('master_skripsi.status', 1)
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
                    if ($row->namaPembimbing1 && $row->namaPembimbing2) {
                        return $row->namaPembimbing1 . ' & ' . $row->namaPembimbing2;
                    } elseif ($row->namaPembimbing1) {
                        return $row->namaPembimbing1;
                    } elseif ($row->namaPembimbing2) {
                        return $row->namaPembimbing2;
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
                    return implode(' & ', $names);
                })
                ->addColumn('waktu', function($row) {
                    return $row->waktuMulai . ' - ' . $row->waktuSelesai;
                })
                ->addColumn('actions', function($row) {
                    return '<button type="button" class="btn btn-sm btn-warning" onclick="showEditJadwalModal('.$row->id.', this)">
                        <i class="bi bi-pencil-square"></i>
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
                'mahasiswa.nim',
                'mahasiswa.nama',
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
            'kuota' => 'required|kuota',
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

    public function updateStatusJadwal(Request $request, $id)
    {
        try {
            $sidang = SidangSkripsi::findOrFail($id);
            $sidang->update([
                'status' => $request->status,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Status sidang berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(GelombangSidangSkripsi $gelombang)
    {
        $gelombang->delete();
        return redirect()->route('sidang.index')->with('success', 'Gelombang berhasil dihapus');
    }
}
