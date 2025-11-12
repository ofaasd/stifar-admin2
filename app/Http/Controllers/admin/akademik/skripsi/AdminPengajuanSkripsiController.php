<?php

namespace App\Http\Controllers\admin\akademik\skripsi;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\RefPembimbing;
use App\Http\Controllers\Controller;
use App\Models\PengajuanJudulSkripsi;
use App\Models\Prodi;
use Illuminate\Support\Facades\Crypt;

class AdminPengajuanSkripsiController extends Controller
{
    /**
     * menampilkan halaman pengajuan skripsi di sisi admin.
     *
     * Terakhir diedit: 6 November 2025
     * Editor: faiz
     */
    public function index()
    {
        $pengajuanJudul = PengajuanJudulSkripsi::all();
        $statusLabels = [
            0 => 'Pengajuan',
            1 => 'ACC',
            2 => 'Revisi',
            3 => 'Ditolak',
            4 => 'Pergantian Judul',
        ];

        $statusPengajuan = $pengajuanJudul->pluck('status')->unique()->sort()->values()
            ->mapWithKeys(function($status) use ($statusLabels) {
                return [$status => $statusLabels[$status] ?? 'Tidak Diketahui'];
            })->toArray();

        $prodi = Prodi::all();

        $data = [
            'title' => 'Pengajuan Judul',
            'statusPengajuan' => $statusPengajuan,
            'prodi' => $prodi
        ];   
        return view('admin.akademik.skripsi.pengajuan.index', $data);
    }

    /**
     * menampilkan data pengajuan skripsi di sisi admin dengan filter program studi.
     *
     * Terakhir diedit: 6 November 2025
     * Editor: faiz
     */
    public function getData(Request $request)
    {
        $prodi = $request->input('prodi') ?? null;

        // Ambil semua pengajuan judul, join mahasiswa dan master_skripsi
        $pengajuan = PengajuanJudulSkripsi::select([
                'pengajuan_judul_skripsi.id',
                'pengajuan_judul_skripsi.id_master',
                'pengajuan_judul_skripsi.judul',
                'pengajuan_judul_skripsi.judul_eng AS judulEnglish',
                'pengajuan_judul_skripsi.abstrak',
                'master_skripsi.nim',
                'mahasiswa.nama AS nama',
                'master_skripsi.pembimbing_1 AS pembimbing1',
                'master_skripsi.pembimbing_2 AS pembimbing2',
                'pengajuan_judul_skripsi.status',
                'pengajuan_judul_skripsi.created_at',
            ])
            ->leftJoin('master_skripsi', 'pengajuan_judul_skripsi.id_master', '=', 'master_skripsi.id')
            ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
            ->when($prodi !== null, function ($query) use ($prodi) {
                return $query->where('mahasiswa.id_program_studi', $prodi);
            })
            ->orderBy('master_skripsi.created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->idMasterEnkripsi = Crypt::encryptString($item->id_master . "stifar");
                return $item;
            })
            ->groupBy('nim');

        $data = [];
        foreach ($pengajuan as $nim => $rows) {
            $judulArr = [];
            foreach ($rows as $row) {
                // Tentukan icon status
                switch ($row->status) {
                    case 1:
                        $icon = '<span title="ACC" class="text-success"><i class="bi bi-check-circle-fill"></i></span>';
                        break;
                    case 2:
                        $icon = '<span title="Revisi" class="text-warning"><i class="bi bi-pencil-square"></i></span>';
                        break;
                    case 3:
                        $icon = '<span title="Ditolak" class="text-danger"><i class="bi bi-x-circle-fill"></i></span>';
                        break;
                    default:
                        $icon = '<span title="Pengajuan" class="text-secondary"><i class="bi bi-clock-fill"></i></span>';
                        break;
                }
                $judulArr[] = $icon . ' ' . $row->judul . '<br>' . $icon . ' ' . $row->judulEnglish;
            }
            
            $first = $rows->first();
            $actions = '';
            if ($first->status == 0) {
                $actions = '<a href="' . route('show-skripsi', ['idMasterSkripsi' => $first->idMasterEnkripsi]) . '" class="btn btn-primary btn-sm text-light">Detail</a>';
            } else {
                foreach ($rows as $r) {
                    switch ($r->status) {
                        case 1:
                            $badge = '<span class="badge bg-success">ACC</span>';
                            break;
                        case 2:
                            $badge = '<span class="badge bg-warning text-dark">Revisi</span>';
                            break;
                        case 3:
                            $badge = '<span class="badge bg-danger">Ditolak</span>';
                            break;
                        case 4:
                            $badge = '<span class="badge bg-info text-dark">Pergantian Judul</span>';
                            break;
                        default:
                            $badge = '<span class="badge bg-secondary">Tidak Diketahui</span>';
                            break;
                    }
                    $actions .= '<div class="mb-1">'
                        . $badge . ' '
                        . '</div>';
                }
            }

            $data[] = [
                'nim' => $nim,
                'nama' => $first->nama,
                'judul' => implode('<hr><br>', $judulArr),
                'pembimbing1' => $first->pembimbing1,
                'pembimbing2' => $first->pembimbing2,
                'status' => $first->status,
                'waktu' => $first->created_at->format('d/m/Y H:i'),
                'actions' => $actions,
            ];
        }

        // Jika data kosong, kirim response dengan pesan khusus
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
                return $row['nim'] . ' - ' . $row['nama'];
            })
            ->rawColumns(['actions', 'judul'])
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * menyetujui pengajuan skripsi mahasiswa.
     *
     * Terakhir diedit: 6 November 2025
     * Editor: faiz
     */
    public function store(Request $request)
    {
        try {
            $idMaster = $request->input('idMaster');
            $masterSkripsi = MasterSkripsi::where('id', $idMaster)->first();
            $nim = $masterSkripsi->nim;
            $masterSkripsi2Id = MasterSkripsi::where('nim', $nim)
                ->where('status', 0)
                ->where('id', '!=', $idMaster)
                ->value('id');

            // Gabungkan idMaster dengan masterSkripsi2Id
            $idMasterArray = [$idMaster];
            if ($masterSkripsi2Id) {
                $idMasterArray[] = $masterSkripsi2Id;
            }

            $judulSkripsi = PengajuanJudulSkripsi::whereIn('id_master', $idMasterArray)->get();

            // Hitung jumlah status yang 1 (ACC)
            $countStatus1 = 0;
            $judulAccId = null;
            foreach ($judulSkripsi as $judul) {
                $status = $request->input('statusJudul' . $judul->id);
                if ($status == 1) {
                    $countStatus1++;
                    $judulAccId = $judul->id;
                }
            }

            // Validasi: hanya boleh 1 judul yang ACC
            if ($countStatus1 > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status ACC (1) hanya boleh satu judul saja.'
                ], 400);
            }

            // Jika ada 1 judul ACC, pastikan judul lain ditolak (status 3)
            if ($countStatus1 == 1) {
                foreach ($judulSkripsi as $judul) {
                    $status = $request->input('statusJudul' . $judul->id);
                    if ($judul->id != $judulAccId && $status != 3) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Jika 1 judul diterima (ACC), judul lainnya harus ditolak.'
                        ], 400);
                    }
                }
            }

            // Update setiap judul skripsi
            foreach ($judulSkripsi as $judul) {
                $catatan = $request->input('catatanJudul' . $judul->id);
                $status = $request->input('statusJudul' . $judul->id);

                // Update catatan dan status
                $judul->catatan = $catatan;
                $judul->status = $status;
                $judul->save();

                // Jika judul ini yang ACC (status 1), update master skripsi
                if ($status == 1) {
                    $pembimbing1 = $request->input('pembimbing1_' . $judul->id);
                    $pembimbing2 = $request->input('pembimbing2_' . $judul->id);

                    $kuotaPembimbing1 = RefPembimbing::where('nip', $pembimbing1)->value('kuota') ?? 0;
                    $kuotaPembimbing2 = RefPembimbing::where('nip', $pembimbing2)->value('kuota') ?? 0;

                    $pembimbing1Count = MasterSkripsi::where(function($q) use ($pembimbing1) {
                        $q->where('pembimbing_1', $pembimbing1)
                          ->orWhere('pembimbing_2', $pembimbing1);
                    })->where('status', '!=', 1)->count();

                    $pembimbing2Count = MasterSkripsi::where(function($q) use ($pembimbing2) {
                        $q->where('pembimbing_1', $pembimbing2)
                          ->orWhere('pembimbing_2', $pembimbing2);
                    })->where('status', '!=', 1)->count();

                    if ($kuotaPembimbing1 <= $pembimbing1Count) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Kuota pembimbing 1 telah penuh.'
                        ], 400);
                    }

                    if ($kuotaPembimbing2 <= $pembimbing2Count) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Kuota pembimbing 2 telah penuh.'
                        ], 400);
                    }
                    
                    MasterSkripsi::where('id', $judul->id_master)->update([
                        'status' => 2, // Status master berubah menjadi ACC
                        'pembimbing_1' => $pembimbing1,
                        'pembimbing_2' => $pembimbing2,
                    ]);
                    PengajuanJudulSkripsi::where('id', $judul->id)->update([
                        'status' => 1,
                    ]);
                }else{
                    MasterSkripsi::where('id', $judul->id_master)->update([
                        'status' => 3, // Status master berubah menjadi Ditolak selain yang acc
                    ]);
                    
                    PengajuanJudulSkripsi::where('id', $judul->id)->update([
                        'status' => 3,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pengajuan judul berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * mengambil data spesifik pada pengajuan skripsi.
     *
     * Terakhir diedit: 6 November 2025
     * Editor: faiz
     */
    public function show(string $idMasterSkripsiEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idMasterSkripsiEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $masterSkripsi = MasterSkripsi::where('id', $id)->first();
        $mahasiswa = Mahasiswa::where('nim', $masterSkripsi->nim)->first();
        $nim = $masterSkripsi->nim;
        $masterSkripsi2 = MasterSkripsi::where('nim', $nim)
            ->where('status', 0)
            ->where('id', '!=', $id)
            ->first();

        // Ambil id_master dari kedua masterSkripsi
        $idMasterArray = [$masterSkripsi->id];
        if ($masterSkripsi2) {
            $idMasterArray[] = $masterSkripsi2->id;
        }

        $judulSkripsi = PengajuanJudulSkripsi::select([
            'pengajuan_judul_skripsi.*',
            'master_skripsi.pembimbing_1',
            'master_skripsi.pembimbing_2',
            'master_bidang_minat.nama AS nama_bidang_minat',
        ])
        ->leftJoin('master_skripsi', 'pengajuan_judul_skripsi.id_master', '=', 'master_skripsi.id')
        ->leftJoin('master_bidang_minat', 'pengajuan_judul_skripsi.id_bidang_minat', '=', 'master_bidang_minat.id')
        ->whereIn('pengajuan_judul_skripsi.id_master', $idMasterArray)
        ->get();

        $arrBidangMinat = $judulSkripsi->pluck('id_bidang_minat')->unique()->values()->all();

        $dosen = RefPembimbing::leftJoin('pegawai_biodata as pegawai', 'pegawai.npp', '=', 'ref_pembimbing_skripsi.nip')
        ->select('pegawai.nama_lengkap AS nama', 'pegawai.npp', 'ref_pembimbing_skripsi.kuota', 'ref_pembimbing_skripsi.id_progdi', 'ref_pembimbing_skripsi.id_bidang_minat')
        ->whereIn('ref_pembimbing_skripsi.id_bidang_minat', $arrBidangMinat)
        ->get();

        $data = [
            'title' => 'Detail Pengajuan Judul',
            'masterSkripsi' => $masterSkripsi,
            'judulSkripsi' => $judulSkripsi,
            'mahasiswa' => $mahasiswa,
            'dosen' => $dosen,
        ];

        return view('admin.akademik.skripsi.pengajuan.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * mencetak daftar mahasiswa pengajuan skripsi menggunakan filter status dan tanggal.
     *
     * Terakhir diedit: 6 November 2025
     * Editor: faiz
     */
    public function print(Request $request)
    {
        $status = $request->status;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        if($toDate < $fromDate)
        {
            return redirect()->back()->with('error', 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.');
        }
        
        $queryPengajuan = PengajuanJudulSkripsi::select([
            'pengajuan_judul_skripsi.id',
            'pengajuan_judul_skripsi.id_master',
            'pengajuan_judul_skripsi.judul',
            'pengajuan_judul_skripsi.judul_eng AS judulEnglish',
            'pengajuan_judul_skripsi.abstrak',
            'master_skripsi.nim',
            'mahasiswa.nama AS nama',
            'pengajuan_judul_skripsi.status',
            'pb1.nama_lengkap AS pembimbing1',
            'pb2.nama_lengkap AS pembimbing2',  
        ])
        ->leftJoin('master_skripsi', 'pengajuan_judul_skripsi.id_master', '=', 'master_skripsi.id')
        ->leftJoin('pegawai_biodata as pb1', 'pb1.npp', '=', 'master_skripsi.pembimbing_1')
        ->leftJoin('pegawai_biodata as pb2', 'pb2.npp', '=', 'master_skripsi.pembimbing_2')
        ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
        ->orderBy('master_skripsi.created_at', 'desc');

        if ($status && $status != 'all') {
            $queryPengajuan->where('pengajuan_judul_skripsi.status', $status);
        }
        if ($fromDate) {
            $queryPengajuan->whereDate('pengajuan_judul_skripsi.created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $queryPengajuan->whereDate('pengajuan_judul_skripsi.created_at', '<=', $toDate);
        }

        $pengajuan = $queryPengajuan->get()
        ->map(function ($item) {
            $item->idMasterEnkripsi = Crypt::encryptString($item->id_master . "stifar");
            return $item;
        })
        ->groupBy('nim');

        $data = [];
        foreach ($pengajuan as $nim => $rows) {
            $judulArr = [];
            foreach ($rows as $row) {
                // Tentukan icon status
                switch ($row->status) {
                    case 1:
                        $icon = '<span title="ACC" class="text-success"><i class="bi bi-check-circle-fill"></i></span>';
                        break;
                    case 2:
                        $icon = '<span title="Revisi" class="text-warning"><i class="bi bi-pencil-square"></i></span>';
                        break;
                    case 3:
                        $icon = '<span title="Ditolak" class="text-danger"><i class="bi bi-x-circle-fill"></i></span>';
                        break;
                    default:
                        $icon = '<span title="Pengajuan" class="text-secondary"><i class="bi bi-clock-fill"></i></span>';
                        break;
                }
                $judulArr[] = $icon . ' ' . $row->judul . '<br>' . $icon . ' ' . $row->judulEnglish;
            }
            $first = $rows->first();
            $data[] = [
                'nim' => $nim,
                'nama' => $first->nama,
                'judul' => implode('<hr><br>', $judulArr),
                'pembimbing1' => $first->pembimbing1,
                'pembimbing2' => $first->pembimbing2,
            ];
        }

        $logo = asset('assets/images/logo/upload/logo_besar.png');

        $title = "Laporan Pengajuan Judul";

        // Tambahkan filter ke title jika ada
        $parts = [];

        $statusLabels = [
            0 => 'Pengajuan',
            1 => 'ACC',
            2 => 'Revisi',
            3 => 'Ditolak',
            4 => 'Pergantian Judul',
        ];

        if (!empty($status) && $status !== 'all') {
            $statusText = $statusLabels[(int)$status] ?? 'Tidak Diketahui';
            $parts[] = "Status: " . $statusText;
        }
        if (!empty($fromDate) || !empty($toDate)) {
            $from = $fromDate ? date('d/m/Y', strtotime($fromDate)) : '';
            $to = $toDate ? date('d/m/Y', strtotime($toDate)) : '';
            $periode = trim($from . ($from && $to ? " s/d " : "") . $to);
            $parts[] = "Periode: " . ($periode ?: '-');
        }
        if (!empty($parts)) {
            $title .= " - " . implode(" | ", $parts);
        }

        // Generate PDF dengan mPDF
        $mpdf = new \Mpdf\Mpdf();
        $html = view('admin.akademik.skripsi.pengajuan.print', compact('pengajuan', 'logo', 'title'))->render();
        $mpdf->WriteHTML($html);

        // Buat nama file yang unik dan sertakan header Content-Disposition agar browser menyimpan dengan nama yang benar
        $filename = $title . '.pdf';
        $pdfContent = $mpdf->Output($filename, 'S');

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
