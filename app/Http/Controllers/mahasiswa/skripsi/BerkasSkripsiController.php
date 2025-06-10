<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Http\Controllers\Controller;
use App\Models\BerkasSkripsi;
use App\Models\KategoriBerkasSkripsi;
use Auth;
use DB;
use Illuminate\Http\Request;
use Storage;

class BerkasSkripsiController extends Controller
{
    public function index(){
        $user = Auth::user();
        $email = $user->email;
    
        $nim = explode('@', $email)[0];
        $kategori = KategoriBerkasSkripsi::all();
        $berkasSkripsi = BerkasSkripsi::select('berkas_skripsi.*')
        ->join(DB::raw('
            (
                SELECT MAX(id) as latest_id
                FROM berkas_skripsi
                WHERE nim = 1032211029
                GROUP BY kategori_id
            ) as latest_berkas
        '), 'berkas_skripsi.id', '=', 'latest_berkas.latest_id')
        ->with('kategori')
        ->orderByDesc('tanggal_upload')
        ->get();

    return view('mahasiswa.skripsi.berkas.main', compact('kategori', 'berkasSkripsi'));
    }

    public function store(Request $request)
{
    $request->validate([
        'kategori_id' => 'required|exists:kategori_berkas_skripsi,id',
        'deskripsi' => 'nullable|string',
        'file' => 'required|mimes:pdf,doc,docx,jpg,png|max:10240'
    ]);
    $user = Auth::user();
    $email = $user->email;

    $nim = explode('@', $email)[0];
    $path = $request->file('file')->store('berkas_skripsi', 'public');
    $skripsi = \App\Models\Skripsi::where('nim', $nim)->firstOrFail();

    BerkasSkripsi::create([
        'skripsi_id' => $skripsi->id, // sesuaikan relasi
        'nim' => $nim,
        'kategori_id' => $request->kategori_id,
        'deskripsi' => $request->deskripsi,
        'nama_file' => $path,
        'status' => 0, // default: belum diverifikasi
        'tanggal_upload' => now(),
    ]);

    return redirect()->back()->with('success', 'Berkas berhasil diupload.');
}

public function update(Request $request, $id)
{
    $berkas = BerkasSkripsi::findOrFail($id);

    $request->validate([
        'deskripsi' => 'nullable|string',
        'file' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:10240'
    ]);

    if ($request->hasFile('file')) {
        if ($berkas->nama_file && Storage::disk('public')->exists($berkas->nama_file)) {
            Storage::disk('public')->delete($berkas->nama_file);
        }

        $berkas->nama_file = $request->file('file')->store('berkas_skripsi', 'public');
        $berkas->status = 0; // reset status verifikasi jika diganti
    }

    $berkas->deskripsi = $request->deskripsi;
    $berkas->tanggal_upload = now();
    $berkas->save();

    return redirect()->back()->with('success', 'Berkas berhasil diperbarui.');
}
}
