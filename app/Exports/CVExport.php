<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\PegawaiGolongan;
use App\Models\PegawaiJeni as PegawaiJenis;
use App\Models\PegawaiJabatanStruktural;
use App\Models\PegawaiJabatanFungsional;
use App\Models\PegawaiPendidikan;
use App\Models\PegawaiOrganisasi;
use App\Models\PegawaiPekerjaan;
use App\Models\PegawaiMengajar;
use App\Models\PegawaiPosisi;
use App\Models\PegawaiPenelitian;
use App\Models\Wilayah;
use App\Models\ModelHasRole;
use App\Models\JabatanFungsional;
use App\Models\JabatanStruktural;
use App\Models\PegawaiBiodatum;
use Illuminate\Support\Facades\Auth;

class CVExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        //
        $id = Auth::user()->id;
        $pegawai = PegawaiBiodatum::where('user_id',$id)->first();
        $jabatan_struktural = JabatanStruktural::where('id_pegawai',$pegawai->id)->first()->jabatan ?? '';
        $jabatan_fungsional = '';
        if(!empty($pegawai->id_jabfung)){
            $jabatan_fungsional = JabatanFungsional::where('id',$pegawai->id_jabfung)->first()->jabatan ?? '';
        }
        $pegawai_pendidikan = PegawaiPendidikan::where('id_pegawai',$pegawai->id)->get();
        $pegawai_organisasi = PegawaiOrganisasi::where('id_pegawai',$pegawai->id)->get();
        $pegawai_pekerjaan = PegawaiPekerjaan::where('id_pegawai',$pegawai->id)->get();
        $pegawai_mengajar = PegawaiMengajar::where('id_pegawai',$pegawai->id)->get();
        $pegawai_penelitian = PegawaiPenelitian::where('id_pegawai',$pegawai->id)->get();
        $bulan = array(
            1=>"Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        );
        $data = [
            'logo' => public_path('/assets/images/logo/logo-icon.png'),
            'pegawai' => $pegawai,
            'bulan' => $bulan,
            'jabatan_struktural' => $jabatan_struktural,
            'jabatan_fungsional' => $jabatan_fungsional,
            'pegawai_pendidikan' => $pegawai_pendidikan,
            'pegawai_organisasi' => $pegawai_organisasi,
            'pegawai_pekerjaan' => $pegawai_pekerjaan,
            'pegawai_mengajar' => $pegawai_mengajar,
            'pegawai_penelitian' => $pegawai_penelitian,
        ];
        return view('pegawai.profile.cetak_cv_excel',$data);
    }
}
