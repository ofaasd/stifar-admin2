<?php

namespace App\Http\Controllers\mahasiswa;

use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BerkasPendukungMahasiswa;
use App\Models\MahasiswaBerkasPendukung;

class MahasiswaBerkasController extends Controller
{
    public function index(){
        $mhs = Mahasiswa::where('user_id', Auth::id())->first();
        $nim = $mhs->nim;
        $title = $mhs->nama;
        $mahasiswa = Mahasiswa::select('mahasiswa.*', 'mahasiswa.nim as nimMahasiswa', 'pegawai_biodata.nama_lengkap as dosenWali')
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
        ->where('mahasiswa.nim', $nim)
        ->first();

        if(!$mahasiswa){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }

        $berkas = MahasiswaBerkasPendukung::where("nim", $mahasiswa->nim)->latest()->first();
        $curr_prodi = Prodi::where('id',$mahasiswa->id_program_studi)->first();

        $data = [
            'mahasiswa' => $mahasiswa,
            'title' => $title,
            'berkas' => $berkas,
            'curr_prodi' => $curr_prodi,
        ];
        $curr_prodi = Prodi::where('id',$mahasiswa->id_program_studi)->first();
        $ta = TahunAjaran::where("status", "Aktif")->first();
        if($berkas){
            if($berkas->id_ta != $ta->id){
                $data['updateHerregistrasi'] = true;
            }
        }

        return view('mahasiswa.berkas.index', $data);
    }

    public function store(Request $request)
    {
        $fields = [
            'kk' => 'kk',
            'ktp' => 'ktp',
            'akte' => 'akte',
            'ijazah_depan' => 'ijazah_depan',
            'ijazah_belakang' => 'ijazah_belakang',
            'file_toefl' => 'file_toefl',
        ];

        $validatedData = $request->validate(array_fill_keys(array_keys($fields), 'mimes:jpg,jpeg|max:5012'));

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

        return response()->json(['message' => 'Berhasil Menyimpan Berkas']);
    }
}
