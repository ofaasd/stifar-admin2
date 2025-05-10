<?php

namespace App\Http\Controllers\admin\berkas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BerkasPendukungMahasiswa;
use App\Models\Mahasiswa;

class BerkasMahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Berkas Mahasiswa";
        $mhs = Mahasiswa::select('mahasiswa.*', 'mahasiswa_berkas_pendukung.*', 'mahasiswa.nim as nimMahasiswa')
        ->leftJoin('mahasiswa_berkas_pendukung', 'mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
        ->get();

        $fake_id = 0;
        return view('admin.berkas.mahasiswa.index', compact('title', 'mhs', 'fake_id'));
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

        $tujuan_upload = 'assets/file/berkas/mahasiswa';

        foreach ($fields as $fileInput => $dbField) {
            if ($request->hasFile($fileInput)) {
                $file = $request->file($fileInput);
                $fileName = date('YmdHi') . $request->nim . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $fileName);

                switch ($dbField) {
                    case "foto_ktp":
                        $tujuan_upload .= "/ktp";
                        break;
                    case "foto_kk":
                        $tujuan_upload .= "/kk";
                        break;
                    case "foto_akte":
                        $tujuan_upload .= "/akte";
                        break;
                    case "foto_ijazah_depan":
                        $tujuan_upload .= "/c";
                        break;
                    case "foto_ijazah_belakang":
                        $tujuan_upload .= "/ijazah_belakang";
                        break;
                    case "foto_sistem":
                        $tujuan_upload .= "/sistem";
                        break;
                }

                // Pastikan folder ada
                if (!file_exists($tujuan_upload)) {
                    mkdir($tujuan_upload, 0777, true);
                }

                $file->move($tujuan_upload, $fileName);

                $berkas->update([$dbField => $fileName]);
            }
        }

        return response()->json(['message' => 'Berhasil Menyimpan Berkas']);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $nim)
    {
        $mhs = Mahasiswa::select('mahasiswa.*', 'mahasiswa.nim as nimMahasiswa', 'mahasiswa_berkas_pendukung.*')
        ->leftJoin('mahasiswa_berkas_pendukung', 'mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
        ->where('mahasiswa.nim', $nim)
        ->first();

        $title = 'Berkas ' . $mhs->nama;

        $data = [
            'mahasiswa' => $mhs,
            'title' => $title,
        ];

        return view('admin.berkas.mahasiswa.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

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
