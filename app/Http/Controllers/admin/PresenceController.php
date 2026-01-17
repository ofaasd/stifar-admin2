<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\User;
use App\Models\WorkingHour;
use App\Models\PegawaiBiodatum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use DateTime;
use DateInterval;
use DatePeriod;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FingerprintImport;

class PresenceController extends Controller
{
    //
    public function index(Request $request)
    {
        // 1. Tentukan Default Tanggal (Awal Bulan ini s/d Hari Ini)
        $defaultStartDate = Carbon::now()->startOfMonth()->toDateString();
        $defaultEndDate   = Carbon::now()->toDateString();

        // 2. Ambil dari Request jika ada filter, jika tidak pakai default
        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate   = $request->input('end_date', $defaultEndDate);

        $pegawai = PegawaiBiodatum::select('nama_lengkap','pegawai_biodata.id','pegawai_posisi.nama')->join('pegawai_posisi','pegawai_posisi.id','=','pegawai_biodata.id_posisi_pegawai')->get();

        // 3. Query Data
        if(empty($request->pegawai_id) || $request->pegawai_id == 0){
            $presences = Presence::with('pegawai') // Eager load relasi pegawai
            ->whereBetween('day', [$startDate, $endDate])
            ->orderBy('day', 'desc')
            ->orderBy('start', 'asc')
            ->paginate(10); // Pagination 10 baris per halaman
        } else {
            $get_no_absensi = PegawaiBiodatum::where('id',$request->pegawai_id)->first()->no_absensi;
            if($get_no_absensi == null){
                return redirect()->back()->with('error', 'Pegawai yang dipilih tidak memiliki nomor absensi di sistem fingerprint. Silakan perbarui data pegawai tersebut terlebih dahulu melalui link berikut.')->with('link', url('admin/kepegawaian/pegawai/'.$request->pegawai_id.'/edit'));
            }else{
                $presences = Presence::with('pegawai') // Eager load relasi pegawai
                ->whereHas('pegawai', function($query) use ($get_no_absensi) {
                    $query->where('no_absensi', $get_no_absensi);
                })
                ->whereBetween('day', [$startDate, $endDate])
                ->orderBy('day', 'desc')
                ->orderBy('start', 'asc')
                ->paginate(10); // Pagination 10 baris per halaman
            }
        }
        // 4. Return View
        $title = "Kehadiran Pegawai";
        return view('admin.presence.index', compact('presences', 'startDate', 'endDate', 'title', 'pegawai'));
    }
    public function importExcel(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        try {
            // Proses Import
            Excel::import(new FingerprintImport, $request->file('file'));
            
            return redirect()->back()
                ->with('success', 'Data fingerprint berhasil diimport!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}
