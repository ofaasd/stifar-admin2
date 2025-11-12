<?php

namespace App\Http\Controllers\mahasiswa\akademik;

use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\DaftarWisudawan;
use App\Models\TbGelombangWisuda;
use App\Http\Controllers\Controller;
use App\Models\PengajuanJudulSkripsi;
use Illuminate\Support\Facades\Crypt;
use App\Models\BerkasPendukungMahasiswa;
use App\Models\MahasiswaBerkasPendukung;
use App\Models\TbPembayaranWisuda;

class DaftarWisudaController extends Controller
{
    /**
    * menampilkan halamanm, data gelombang wisuda dan pendaftaran wisuda.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function index()
    {
        $gelombangWisuda = TbGelombangWisuda::select([
                'tb_gelombang_wisuda.*',
                \DB::raw('(SELECT COUNT(*) FROM tb_daftar_wisudawan WHERE tb_daftar_wisudawan.id_gelombang_wisuda = tb_gelombang_wisuda.id AND tb_daftar_wisudawan.status = 1) as jml_peserta'),
            ])
            ->whereDate('mulai_pendaftaran', '<=', now()->toDateString())
            ->whereDate('selesai_pendaftaran', '>=', now()->toDateString())
            ->get();

        $title = 'Daftar Wisuda';

        $mhs = Mahasiswa::select([
            'mahasiswa.nim',
            'mahasiswa.nama',
            'mahasiswa.foto_yudisium AS fotoMhs',
            'mahasiswa.tempat_lahir AS tempatLahir',
            'mahasiswa.tgl_lahir AS tanggalLahir',
            'mahasiswa.no_ktp AS noKtp',
            'gelombang_yudisium.nama AS namaGelombangYudisium',
            'pengajuan_judul_skripsi.judul',
            'pengajuan_judul_skripsi.judul_eng AS judulEng',
        ])
        ->where('user_id', auth()->user()->id)
        ->where('pengajuan_judul_skripsi.status', 1)
        ->leftJoin('master_skripsi', 'mahasiswa.nim', '=', 'master_skripsi.nim')
        ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
        ->leftJoin('tb_yudisium', 'tb_yudisium.nim', '=', 'mahasiswa.nim')
        ->leftJoin('gelombang_yudisium', 'gelombang_yudisium.id', '=', 'tb_yudisium.id_gelombang_yudisium')
        ->first();

        if(!$mhs){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }

        $berkas = MahasiswaBerkasPendukung::select()
        ->where("nim", $mhs->nim)->latest()->first();

        $registered = DaftarWisudawan::select([
            'tb_gelombang_wisuda.nama',
            'tb_gelombang_wisuda.tempat',
            'tb_gelombang_wisuda.waktu_pelaksanaan',
            'tb_gelombang_wisuda.tanggal_pemberkasan',
            'tb_gelombang_wisuda.tanggal_gladi',
            'tb_gelombang_wisuda.tarif_wisuda',
            'tb_daftar_wisudawan.status',
            'tb_daftar_wisudawan.nim',
            'tb_pembayaran_wisuda.status AS statusPembayaran',
            'tb_pembayaran_wisuda.bukti AS buktiPembayaran',
            'tb_pembayaran_wisuda.tanggal_bayar AS tanggalPembayaran'
        ])
        ->leftJoin('tb_gelombang_wisuda', 'tb_gelombang_wisuda.id', '=', 'tb_daftar_wisudawan.id_gelombang_wisuda')
        ->leftJoin('tb_pembayaran_wisuda', 'tb_daftar_wisudawan.nim', '=', 'tb_pembayaran_wisuda.nim')
        ->where('tb_daftar_wisudawan.nim', $mhs->nim)
        ->first();

        $data = [
            'gelombangWisuda' => $gelombangWisuda,
            'title' => $title,
            'mhs' => $mhs,
            'berkas' => $berkas,
            'registered' => $registered,
        ];

        $ta = TahunAjaran::where("status", "Aktif")->first();
        if($berkas){
            if($berkas->id_ta != $ta->id){
                $data['updateHerregistrasi'] = true;
            }
        }

        return view('mahasiswa.akademik.daftar-wisuda.index', $data);
    }

    /**
    * menyimpan data pendaftaran wisuda.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'gelombang_id' => 'required|exists:tb_gelombang_wisuda,id',
            'nim' => 'required|exists:mahasiswa,nim',
            'judul_skripsi' => 'required',
            'judul_skripsi_eng' => 'required',
            'no_ktp' => 'required',
            'tempat_lahir' => 'required',
            'nama_lengkap' => 'required',
            'tanggal_lahir' => 'required',
            'ktp' => 'required|file|mimes:jpg,jpeg|max:5012',
            'kk' => 'required|file|mimes:jpg,jpeg|max:5012',
            'akte' => 'required|file|mimes:jpg,jpeg|max:5012',
        ]);

        $fields = [
            'kk' => 'kk',
            'ktp' => 'ktp',
            'akte' => 'akte',
        ];

        // cek mahasiswa
        $mhs = Mahasiswa::where('nim', $request->nim)->first();
        if(!$mhs) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        // update data diri mahasiswa
        $mhs->update([
            'nama'  => $request->nama_lengkap,
            'no_ktp'    => $request->no_ktp,
            'tempat_lahir'  => $request->tempat_lahir,
            'tgl_lahir'  => $request->tanggal_lahir,
        ]);

        // Daftar Wisuda
        $savePendaftaranWisuda = DaftarWisudawan::create([
            'nim'   => $mhs->nim,
            'id_gelombang_wisuda'  => $request->gelombang_id,
            'status'=> 0
        ]);

        // Ambil master skripsi
        $masterSkripsi = MasterSkripsi::where('nim', $mhs->nim)->first();
        if (!$masterSkripsi) {
            return response()->json(['message' => 'Skripsi tidak ditemukan'], 404);
        }

        // Ambil judul skripsi aktif
        $skripsi = PengajuanJudulSkripsi::where('id_master', $masterSkripsi->id)
            ->where('status', 1)
            ->first();
        if (!$skripsi) {
            return response()->json(['message' => 'Judul Skripsi tidak ditemukan'], 404);
        }
        
        // jika judul skripsi berubah
        if($skripsi->judul != $request->judul_skripsi)
        {
            // membuat pengajuan judul
            $judulBaru = PengajuanJudulSkripsi::create([
                'id_master' => $masterSkripsi->id,
                'judul' => $request->judul_skripsi,
                'judul_eng' => $request->judul_skripsi_eng,
                'abstrak' => $skripsi->abstrak,
                'latar_belakang' => $skripsi->latar_belakang,
                'rumusan_masalah' => $skripsi->rumusan_masalah,
                'tujuan'    => $skripsi->tujuan,
                'metodologi' => $skripsi->metodologi,
                'catatan' => $skripsi->catatan,
                'jenis_penelitian' => $skripsi->jenis_penelitian,
                'status'    => 1
            ]);

            // ubah pengajuan terakhir jadi "pergantian judul"
            $skripsi->update([
                'status'    => 4,
            ]);
        }

        // update heregistrasi

        $ta = TahunAjaran::where("status", "Aktif")->first();

        // Cek apakah request ingin update_herregistrasi
        if ($request->has('update_herregistrasi') && $request->update_herregistrasi) 
        {
            // Ambil TA sebelumnya
            $taSebelumnya = TahunAjaran::where("created_at", '<', $ta->created_at)
                ->orderBy('created_at', 'desc')
                ->first();

            // Ambil berkas lama
            $berkasLama = MahasiswaBerkasPendukung::where("nim", $request->nim)
                ->where("id_ta", $taSebelumnya?->id) // safe navigation in case $taSebelumnya null
                ->first();

            // Siapkan data baru dengan isian dari request, jika tidak ada ambil dari lama
            $dataBerkas = ['nim' => $request->nim, 'id_ta' => $ta->id];

            foreach ($fields as $fileInput => $dbField) {
                if ($request->hasFile($fileInput)) {
                    $file = $request->file($fileInput);
                    $fileName = date('YmdHi') . $request->nim . $file->getClientOriginalName();
                    $fileName = str_replace(' ', '-', $fileName);

                    $tujuan_upload = 'assets/file/berkas/mahasiswa/' . $dbField;

                    if (!file_exists($tujuan_upload)) {
                        mkdir($tujuan_upload, 0777, true);
                    }

                    $file->move($tujuan_upload, $fileName);

                    $dataBerkas[$dbField] = $fileName;
                } else {
                    // Ambil dari berkas lama jika tidak dikirim
                    if ($berkasLama) {
                        $dataBerkas[$dbField] = $berkasLama->$dbField;
                    }
                }
            }

            // Simpan sebagai baris baru
            MahasiswaBerkasPendukung::create($dataBerkas);

        } else {
            // Jika tidak herregistrasi → update data jika sudah ada
            $berkas = MahasiswaBerkasPendukung::firstOrCreate(
                [
                    'nim' => $request->nim,
                    'id_ta' => $ta->id
                ],
                [
                    'nim' => $request->nim
                ]
            );

            foreach ($fields as $fileInput => $dbField) {
                if ($request->hasFile($fileInput)) {
                    $file = $request->file($fileInput);
                    $fileName = date('YmdHi') . $request->nim . $file->getClientOriginalName();
                    $fileName = str_replace(' ', '-', $fileName);

                    $tujuan_upload = 'assets/file/berkas/mahasiswa/' . $dbField;

                    if (!file_exists($tujuan_upload)) {
                        mkdir($tujuan_upload, 0777, true);
                    }

                    $file->move($tujuan_upload, $fileName);

                    $berkas->update([$dbField => $fileName]);
                }
            }
        }

        activity()
        ->performedOn($savePendaftaranWisuda)
        ->causedBy(auth()->user())
        ->withProperties([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            ])
        ->log('mendaftar-wisuda');

        return response()->json(['message' => 'Berhasil Menyimpan Berkas']);
    }

    /**
    * mengupload bukti bayar wisuda.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function uploadBuktiBayar(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png|max:5120', // Maksimal 5MB
                'nim' => 'required|exists:mahasiswa,nim',
                'atas_nama' => 'required|string|max:255',
                'bank' => 'required|string|max:255',
                'nominal' => 'required|numeric|min:1000',
                'tanggal_bayar' => 'required|date',
            ]);

            $mhs = DaftarWisudawan::where('nim', $request->nim)->first();
            if (!$mhs) {
                return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
            }

            // Simpan bukti bayar
            $file = $request->file('bukti_bayar');
            $fileName = date('YmdHi') . $mhs->nim . $file->getClientOriginalName();
            $fileName = str_replace(' ', '-', $fileName);

            $tujuan_upload = 'assets/upload/mahasiswa/wisuda/bukti-bayar';

            if (!file_exists($tujuan_upload)) {
                mkdir($tujuan_upload, 0777, true);
            }

            $file->move($tujuan_upload, $fileName);

            TbPembayaranWisuda::create([
                'nim' => $mhs->nim,
                'nominal' => $request->nominal,
                'tanggal_bayar' => $request->tanggal_bayar,
                'bukti' => $fileName,
                'atas_nama' => $request->atas_nama,
                'bank_pengirim' => $request->bank,
                'status' => 0
            ]);

            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('upload-bukti-bayar-wisuda');

            return response()->json(['message' => 'Upload bukti bayar berhasil.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
