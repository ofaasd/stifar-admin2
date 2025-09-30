<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Models\Mahasiswa;
use App\Models\master_nilai;
use App\Models\MasterSkripsi;
use App\Models\SidangSkripsi;
use App\Models\PengujiSkripsi;
use App\Models\PegawaiBiodatum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class NilaiSidangController extends Controller
{
    public function index() 
    {
        try {
            $user  = Auth::user();
            $email = $user->email;
            $nim   = explode('@', $email)[0];
        
            if (!$nim) {
                return redirect()->back()->with('error','Data Mahasiswa tidak ditemukan.');
            }

            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if (!$mahasiswa) {
                return redirect()->back()->with('error','Data Mahasiswa tidak ditemukan.');
            }

            $this->updateValidasiSidang($nim, $user);

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
            ->where('master_skripsi.status', 1)
            ->orderBy('sidang.created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");

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
                } else {
                    $item->namaPenguji1 = '-';
                    $item->namaPenguji2 = '-';
                }

                return $item;
            });

            $data = [
                'title' => 'Nilai Sidang Skripsi' . $mahasiswa->nama ?? '',
                'sidang' => $sidang,
            ];
        
            return view('mahasiswa.skripsi.nilai.index', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: Data tidak ditemukan. Pastikan sudah Sidang dan nilai sudah divalidasi' );
        }
    }

    public function show($idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $sidang = SidangSkripsi::select([
            'sidang.id',
            'sidang.tanggal',
            'sidang.waktu_mulai AS waktuMulai',
            'sidang.waktu_selesai AS waktuSelesai',
            'sidang.penguji',
            'sidang.jenis',
            'master_ruang.nama_ruang AS ruangan',
            'sidang.status',
            'sidang.proposal',
            'sidang.kartu_bimbingan AS kartuBimbingan',
            'sidang.presentasi',
            'sidang.pendukung',
            'gelombang_sidang_skripsi.nama AS namaGelombang',
            'gelombang_sidang_skripsi.periode',
            'pembimbing1.nama_lengkap AS namaPembimbing1',
            'pembimbing2.nama_lengkap AS namaPembimbing2',
            'pengajuan_judul_skripsi.judul',
            'pengajuan_judul_skripsi.judul_eng AS judulEnglish',
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
        ->where('sidang.id', $id)
        ->first();

        $sidang->tanggal = \Carbon\Carbon::parse($sidang->tanggal)->translatedFormat('d/m/Y');
        $npps = explode(',', $sidang->penguji);
        $names = PegawaiBiodatum::whereIn('npp', $npps)->pluck('nama_lengkap')->toArray();
        // Simpan nama penguji ke properti baru agar bisa dipakai di view
        foreach ($names as $i => $nama) {
            $sidang->{'namaPenguji' . ($i + 1)} = $nama;
        }

        $sidang->npps = $npps;

        $data = [
            'title' => 'Detail Nilai Sidang Skripsi ' . $sidang->nama ?? '',
            'sidang' => $sidang,
            'nilaiPenguji' => PengujiSkripsi::where('sidang_id', $sidang->id)->whereIn('npp', $npps)->get(),
        ];

        return view('mahasiswa.skripsi.nilai.show', $data);
    }
    private function updateValidasiSidang($nim, $user)
    {
        $cekMasterSkripsivalidasi = MasterSkripsi::where('nim', $nim)->where('status', 2)->first();
            
            if($cekMasterSkripsivalidasi) {
                $cekSidang = SidangSkripsi::where('skripsi_id', $cekMasterSkripsivalidasi->id)->where('status', 2)->first();
                $arrPenguji = $cekSidang ? explode(',', $cekSidang->penguji) : [];
                $penguji = PengujiSkripsi::where('sidang_id', $cekSidang->id)->whereIn('npp', $arrPenguji)->get();

                // Cek apakah semua penguji sudah status 1
                if ($penguji->count() > 0 && $penguji->every(fn($p) => $p->status == 1)) {
                    // Update status masterSkripsi dan sidang menjadi 1
                    $cekMasterSkripsivalidasi->update(['status' => 1]);
                    $cekSidang->update(['status' => 1]);

                    $jumlahPenguji = $penguji->count();
                    $totalNilai = $penguji->sum('nilai');
                    $rataRataNilai = $jumlahPenguji > 0 ? $totalNilai / $jumlahPenguji : 0;

                    $nhuruf = \App\helpers::getNilaiHuruf($rataRataNilai);

                    // Ambil tahun ajaran aktif
                    $tahunAjaran = \App\Models\TahunAjaran::where('status', 'Aktif')->first();

                    switch($cekSidang->status) {
                        case 1:
                            // sidang terbuka
                            master_nilai::updateOrCreate(
                                [
                                    'nim' => $nim,
                                    'id_matkul' => 35,
                                    'id_tahun' => $tahunAjaran ? $tahunAjaran->id : null,
                                    'id_mhs' => $user->id,
                                ],
                                [
                                    'nakhir' => $rataRataNilai,
                                    'nhuruf' => $nhuruf,
                                    'publish_tugas' => 1,
                                    'publish_uts' => 1,
                                    'publish_uas' => 1,
                                    'validasi_tugas' => 1,
                                    'validasi_uts' => 1,
                                    'validasi_uas' => 1,
                                ]
                            );
                            break;
                        case 2:
                            // sidang tertutup
                            master_nilai::updateOrCreate(
                                [
                                    'nim' => $nim,
                                    'id_matkul' => 83,
                                    'id_tahun' => $tahunAjaran ? $tahunAjaran->id : null,
                                    'id_mhs' => $user->id,
                                ],
                                [
                                    'nakhir' => 0,
                                    'nhuruf' => $nhuruf,
                                    'publish_tugas' => 1,
                                    'publish_uts' => 1,
                                    'publish_uas' => 1,
                                    'validasi_tugas' => 1,
                                    'validasi_uts' => 1,
                                    'validasi_uas' => 1,
                                ]
                            );
                            break;
                        default:
                            // Handle status lainnya jika diperlukan
                            break;
                    }
                }
            }
    }
}
