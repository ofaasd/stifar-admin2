<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\anggota_mk;
use App\Models\Mahasiswa;

class PengaturanUjianController extends Controller
{
    //
    public function index(){
        $title = "Jadwal";
        $mk[] = MataKuliah::where('status', 'Aktif')->get();
        $no = 1;
        $prodi = Prodi::all();
        $nama = [];
        $id_prodi = 0;

        foreach($prodi as $row){
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        $jumlah_anggota = [];
        foreach($mk as $value){
            foreach($value as $row){
                $jumlah_anggota[$row->id] = anggota_mk::where('idmk',$row->id)->count();
            }
        }

        $angkatans = Mahasiswa::select('angkatan')
            ->orderBy('angkatan', 'asc')
            ->whereNotNull('angkatan')
            ->distinct()
            ->get();

        $angkatan = [];
        foreach ($angkatans as $item) {
            $angkatan[] = $item->angkatan;
        }

        // Mendapatkan jumlah mahasiswa untuk setiap angkatan
        $angkatan = Mahasiswa::whereIn('angkatan', $angkatan)
            ->select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->pluck('total', 'angkatan');

        $totalMahasiswa = $angkatan->sum();

        return view('admin.akademik.jadwal.index', compact('title', 'mk', 'no','prodi', 'nama', 'id_prodi' ,'jumlah_anggota', 'angkatan', 'totalMahasiswa'));
    }
}
