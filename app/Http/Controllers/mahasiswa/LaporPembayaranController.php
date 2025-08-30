<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\LaporPembayaran;
use App\Models\Mahasiswa;
use Auth;

class LaporPembayaranController extends Controller
{
    //
    public function create()
    {
        $title = "Info Tagihan Mahasiswa";

        // Mengembalikan view yang berisi form
        return view('mahasiswa.lapor_bayar', compact('title'));
    }

    /**
     * Menyimpan bukti pembayaran baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'tanggal_bayar' => 'required|date',
            'nama_rekening' => 'required|string|max:255',
            'bukti_bayar'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // max 2MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // 2. Proses Upload File
        $fileName = null;
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            // Membuat nama file yang unik berdasarkan waktu
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move('assets/bukti_pembayaran/', $fileName);
        }

        // 3. Simpan Data ke Database
        // Asumsi: Mahasiswa harus login untuk mengakses form ini.
        // Dan model User Anda memiliki kolom 'nim'. Sesuaikan jika perlu.
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $nimMahasiswa = $mhs->nim;

        LaporPembayaran::create([
            'nim_mahasiswa' => $nimMahasiswa,
            'tanggal_bayar' => $request->tanggal_bayar,
            'atas_nama' => $request->nama_rekening,
            'bukti_bayar'   => $fileName, // Simpan nama file ke database
            'status'        => 'pending', // Set status awal
        ]);

        // 4. Redirect dengan Pesan Sukses
        return redirect()->back()->with('success', 'Bukti pembayaran Anda berhasil diunggah dan sedang menunggu verifikasi.');
    }
}
