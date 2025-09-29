<?php

namespace App\Http\Controllers\admin\akademik\skripsi;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\RefPembimbing;
use App\Http\Controllers\Controller;
use App\Models\PengajuanJudulSkripsi;
use Illuminate\Support\Facades\Crypt;

class AdminPengajuanSkripsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Pengajuan Judul Skripsi',
        ];   
        return view('admin.akademik.skripsi.pengajuan.index', $data);
    }

    public function getData()
    {
        // Ambil semua pengajuan judul skripsi, join mahasiswa dan master_skripsi
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
            ])
            ->leftJoin('master_skripsi', 'pengajuan_judul_skripsi.id_master', '=', 'master_skripsi.id')
            ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
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
            $data[] = [
                'nim' => $nim,
                'nama' => $first->nama,
                'judul' => implode('<hr><br>', $judulArr),
                'pembimbing1' => $first->pembimbing1,
                'pembimbing2' => $first->pembimbing2,
                'status' => $first->status,
                'actions' => ($first->status == 0)
                    ? '<a href="' . route('show-skripsi', ['idMasterSkripsi' => $first->idMasterEnkripsi]) . '" class="btn btn-primary btn-sm text-light">Detail</a>'
                    : ($first->status == 1 ? '<span class="badge bg-success">ACC</span>'
                        : ($first->status == 2 ? '<span class="badge bg-warning text-dark">Revisi</span>'
                        : ($first->status == 3 ? '<span class="badge bg-danger">Ditolak</span>'
                        : ($first->status == 4 ? '<span class="badge bg-info text-dark">Pergantian Judul</span>'
                        : '<span class="badge bg-secondary">Tidak Diketahui</span>')))),
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $idMaster = $request->input('idMaster');
            $judulSkripsi = PengajuanJudulSkripsi::where('id_master', $idMaster)->get();
            $masterSkripsi = MasterSkripsi::find($idMaster);

            // Hitung jumlah status yang 1
            $countStatus1 = 0;
            $judulAccId = null;
            foreach ($judulSkripsi as $judul) {
                $status = $request->input('statusJudul' . $judul->id);
                if ($status == 1) {
                    $countStatus1++;
                    $judulAccId = $judul->id;
                }
            }

            if ($countStatus1 > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status ACC (1) hanya boleh satu judul saja.'
                ], 400);
            }

            if ($countStatus1 == 1) {
                // Pastikan judul lain harus status 3 (Ditolak)
                foreach ($judulSkripsi as $judul) {
                    $status = $request->input('statusJudul' . $judul->id);
                    if ($judul->id != $judulAccId && $status != 3) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Hanya 1 judul yang bisa disimpan'
                        ], 400);
                    }
                }
            }

            foreach ($judulSkripsi as $judul) {
                $catatan = $request->input('catatanJudul' . $judul->id);
                $status = $request->input('statusJudul' . $judul->id);

                if ($status == 1) {
                    $masterSkripsi->update([
                        'status' => 2
                    ]);
                }

                // Update catatan dan status judul skripsi
                $judul->catatan = $catatan;
                $judul->status = $status;
                $judul->save();
            }

            // Update pembimbing jika dipilih
            $pembimbing1 = $request->input('pembimbing1');
            $pembimbing2 = $request->input('pembimbing2');
            if ($pembimbing1 || $pembimbing2) {
                if ($pembimbing1) {
                    $masterSkripsi->pembimbing_1 = $pembimbing1;
                }
                if ($pembimbing2) {
                    $masterSkripsi->pembimbing_2 = $pembimbing2;
                }
                $masterSkripsi->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pengajuan judul skripsi berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idMasterSkripsiEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idMasterSkripsiEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $masterSkripsi = MasterSkripsi::where('id', $id)->first();
        $judulSkripsi = PengajuanJudulSkripsi::where('id_master', $id)->get();
        $mahasiswa = Mahasiswa::where('nim', $masterSkripsi->nim)->first();
        $dosen = RefPembimbing::leftJoin('pegawai_biodata as pegawai', 'pegawai.npp', '=', 'ref_pembimbing_skripsi.nip')
        ->select('pegawai.nama_lengkap AS nama', 'pegawai.npp', 'ref_pembimbing_skripsi.kuota', 'ref_pembimbing_skripsi.id_progdi')
        ->where('pegawai.id_progdi', $mahasiswa->id_program_studi)
        ->get();

        $data = [
            'title' => 'Detail Pengajuan Judul Skripsi',
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
}
