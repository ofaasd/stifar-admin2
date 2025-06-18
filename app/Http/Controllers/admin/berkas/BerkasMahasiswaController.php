<?php

namespace App\Http\Controllers\admin\berkas;

use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\BerkasPendukungMahasiswa;
use App\Models\MahasiswaBerkasPendukung;

class BerkasMahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Berkas Mahasiswa";
        $ta = TahunAjaran::where("status", "Aktif")->first();
        $mhs = Mahasiswa::select('mahasiswa.*', 'mahasiswa_berkas_pendukung.*', 'mahasiswa.nim as nimMahasiswa')
        ->leftJoin('mahasiswa_berkas_pendukung', 'mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
        ->where('mahasiswa_berkas_pendukung.id_ta', $ta->id)
        ->get()
        ->map(function ($item) {
            $item->nimEnkripsi = Crypt::encryptString($item->nimMahasiswa . "stifar");
            return $item;
        });

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


    /**
     * Display the specified resource.
     */
    public function show(string $nimEnkrip)
    {
        $nimDekrip = Crypt::decryptString($nimEnkrip);
        $nim = str_replace("stifar", "", $nimDekrip);

        $mhs = Mahasiswa::select('mahasiswa.*', 'mahasiswa.nim as nimMahasiswa', 'mahasiswa_berkas_pendukung.*', 'mahasiswa_berkas_pendukung.updated_at AS timeStampBerkas')
        ->leftJoin('mahasiswa_berkas_pendukung', 'mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
        ->where('mahasiswa.nim', $nim)
        ->first();

        $berkas = BerkasPendukungMahasiswa::where("nim", $nim)->latest()->first();

        $title = 'Berkas ' . $mhs->nama;

        $data = [
            'mahasiswa' => $mhs,
            'berkas' => $berkas,
            'title' => $title,
        ];

        $ta = TahunAjaran::where("status", "Aktif")->first();
        if($berkas){
            if($berkas->id_ta != $ta->id){
                $data['updateHerregistrasi'] = true;
            }
        }

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
