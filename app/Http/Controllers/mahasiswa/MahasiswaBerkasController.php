<?php

namespace App\Http\Controllers\mahasiswa;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BerkasPendukungMahasiswa;

class MahasiswaBerkasController extends Controller
{
    public function index(){
        $mhs = Mahasiswa::where('user_id', Auth::id())->first();
        $nim = $mhs->nim;
        $title = $mhs->nama;
        $mahasiswa = Mahasiswa::select('mahasiswa.*', 'mahasiswa.nim as nimMahasiswa', 'pegawai_biodata.nama_lengkap as dosenWali', 'mahasiswa_berkas_pendukung.*')
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
        ->leftJoin('mahasiswa_berkas_pendukung', 'mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
        ->where('mahasiswa.nim', $nim)
        ->first();

        $data = [
            'mahasiswa' => $mahasiswa,
            'title' => $title,
        ];

        return view('mahasiswa.berkas.index', $data);
    }

    public function store(Request $request){
        $fields = [
            'foto_ktp' => 'ktp',
            'foto_kk' => 'kk',
            'foto_akte' => 'akte',
            'foto_ijazah_depan' => 'ijazah_depan',
            'foto_ijazah_belakang' => 'ijazah_belakang',
            'foto_sistem' => 'foto_sistem',
        ];

        $validatedData = $request->validate(array_fill_keys(array_keys($fields), 'mimes:jpg,jpeg|max:5012'));

        $berkas = BerkasPendukungMahasiswa::firstOrCreate(['nim' => $request->nim]);

        $tujuan_upload = 'assets/images/mahasiswa/berkas';

        foreach ($fields as $fileInput => $dbField) {
            if ($request->hasFile($fileInput)) {
                $file = $request->file($fileInput);
                $fileName = date('YmdHi') . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $fileName);
                $file->move($tujuan_upload, $fileName);

                $berkas->update([$dbField => $fileName]);
            }
        }

        return response()->json(['message' => 'Berhasil Menyimpan Berkas']);
    }
}
