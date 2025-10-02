<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\TahunAjaran;
use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\MatakuliahKurikulum;
use App\Models\MasterKeuanganMh;
use App\Models\master_nilai;
use App\Models\TblNilaiKuesioner;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use PDF;

class KhsController extends Controller
{
    //
    public function index(int $idmhs=0){
        if($idmhs == 0){
            $mhs = Mahasiswa::where('user_id',Auth::id())->first();
            $id = $mhs->id ?? 0;
            $idmhs = $mhs->id ?? 0;
            if($idmhs == 0){
                dd('User not found');
            }
        }
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;


        $title = 'KHS Mahasiswa';
        $kd_prodi_mhs = Prodi::where('id',$mhs->id_program_studi)->first()->kode_prodi;
        $kurikulum = Kurikulum::where('progdi',$kd_prodi_mhs)->where('angkatan','<=',$mhs->angkatan)->where('angkatan_akhir','>=',$mhs->angkatan)->get();
        $mk = [];
        if($kurikulum){
            foreach($kurikulum as $row){
                $mk[] = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
            }
        }
        //$mk = MataKuliah::get();
        $krs_now = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul')
                    ->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('id_mhs',$idmhs)
                    ->get();
        if($tahun_ajaran->kuesioner == 1){
            $total_pertanyaan = count($krs_now);
            $nilai_kuesioner = TblNilaiKuesioner::where('id_ta',$ta)->where('nim',$mhs->nim)->get();

            if(count($nilai_kuesioner) < $total_pertanyaan){
                return redirect('/mhs/kuesioner_mhs');
            }
        }
        $nilai = [];
        $jumlah_matkul=0;
        foreach($krs_now as $row){
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = '-';
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = '-';
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = '-';
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = '-';
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = '-';
            $jumlah_matkul++;
        }
        $get_nilai = master_nilai::where(['nim'=>$mhs->nim,'id_tahun'=>$ta])->get();
        $jumlah_valid = 0;
        foreach($get_nilai as $row){
            if($row->publish_tugas == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = $row->ntugas;
                // $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = "-";
            }

            if($row->publish_uts == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = $row->nuts;
                // $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = "-";
            }
            if($row->publish_uas == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = $row->nuas;
                // $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = '-';
            }
            // if($row->publish_tugas == 1 && $row->publish_uts == 1 && $row->publish_uas == 1){
            //     $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = $row->nakhir;
            //     $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = $row->nhuruf;
            // }
            if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = $row->nakhir;
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = $row->nhuruf;
                $jumlah_valid++;
            }

        }
        $krs = $krs_now;
        $no = 1;
        $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        return view('mahasiswa.khs', compact('mhs','title', 'permission','mk', 'krs', 'no', 'ta', 'idmhs','nilai','jumlah_matkul','jumlah_valid','tahun_ajaran'));
    }
    public function riwayat(int $idmhs = 0){
        if($idmhs == 0){
            $mhs = Mahasiswa::where('user_id',Auth::id())->first();
            $id = $mhs->id ?? 0;
            $idmhs = $mhs->id ?? 0;
            if($idmhs == 0){
                dd('User not found');
            }
        }else{
            $mhs = Mahasiswa::find($id);
        }
        $angkatan_ta = (int)($mhs->angkatan . "1");

        $tahun_ajaran = TahunAjaran::where('status','Tidak Aktif')->where('kode_ta','>=',$angkatan_ta)->get();
        //$ta = $tahun_ajaran->id;


        $title = 'Riwayat KHS Mahasiswa';
        $kd_prodi_mhs = Prodi::where('id',$mhs->id_program_studi)->first()->kode_prodi;
        $kurikulum = Kurikulum::where('progdi',$kd_prodi_mhs)->where('angkatan','<=',$mhs->angkatan)->where('angkatan_akhir','>=',$mhs->angkatan)->get();
        $mk = [];
        if($kurikulum){
            foreach($kurikulum as $row){
                $mk[] = MatakuliahKurikulum::select('mata_kuliahs.*')->join('mata_kuliahs','mata_kuliahs.id','=','matakuliah_kurikulums.id_mk')->where('mata_kuliahs.status','Aktif')->where('id_kurikulum',$row->id)->get();
            }
        }
        //$mk = MataKuliah::get();
        $krs_now = [];
        $nilai = [];
        $jumlah_matkul=0;
        $jumlah_valid = 0;
        foreach($tahun_ajaran as $ta_row){
            $ta = $ta_row->id;
            $krs_now = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul')
                        ->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                        ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                        ->where('krs.id_tahun', $ta)
                        ->where('id_mhs',$idmhs)
                        ->get();
            
            foreach($krs_now as $row){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = '-';
                $jumlah_matkul++;
            }
            $get_nilai = master_nilai::where(['nim'=>$mhs->nim,'id_tahun'=>$ta])->get();
            
            foreach($get_nilai as $row){
                if($row->publish_tugas == 1){
                    //$nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = $row->ntugas;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = "-";
                }

                if($row->publish_uts == 1){
                    //$nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = $row->nuts;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = "-";
                }
                if($row->publish_uas == 1){
                    //$nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = $row->nuas;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = '-';
                }
                // if($row->publish_tugas == 1 && $row->publish_uts == 1 && $row->publish_uas == 1){
                //     $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = $row->nakhir;
                //     $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = $row->nhuruf;
                // }
                if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1){
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = $row->nakhir;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = $row->nhuruf;
                    $jumlah_valid++;
                }

            }
        }
        $krs = $krs_now;
        $no = 1;
        $permission = MasterKeuanganMh::where('id_mahasiswa',$idmhs)->first();
        return view('mahasiswa.khs_riwayat', compact('mhs','title', 'permission','mk', 'krs', 'no', 'tahun_ajaran', 'idmhs','nilai','jumlah_matkul','jumlah_valid'));
    }
    public function cetak_khs(int $idmhs = 0){
        if($idmhs == 0){
            $mhs = Mahasiswa::where('user_id',Auth::id())->first();
            $id = $mhs->id ?? 0;
            $idmhs = $mhs->id ?? 0;
            if($idmhs == 0){
                dd('User not found');
            }
        }else{
            $mhs = Mahasiswa::where('nim',$idmhs)->first();
            $id = $mhs->id ?? 0;
            $idmhs = $mhs->id ?? 0;
            if($idmhs == 0){
                dd('User not found');
            }
        }
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $mhs = Mahasiswa::select('mahasiswa.nama','mahasiswa.foto_mhs', 'mahasiswa.nim', 'pegawai_biodata.nama_lengkap as dsn_wali', 'program_studi.nama_prodi')
                          ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
                          ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
                          ->where('mahasiswa.id', $id)->first();
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $thn_awal = explode('-', $tahun_ajaran->tgl_awal);
        $thn_akhir = explode('-', $tahun_ajaran->tgl_akhir);
        $tahun_ajar = $thn_awal[0].'-'.$thn_akhir[0];
        $semester = ['', 'Ganjil', 'Ganjil', 'Antara'];
        $smt = substr($tahun_ajaran->kode_ta, 4);
        $krs_now = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul')
            ->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
            ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
            ->where('krs.id_tahun', $ta)
            ->where('id_mhs',$idmhs)
            ->get();
        $nilai = [];
        foreach($krs_now as $row){
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = '-';
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = '-';
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = '-';
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = '-';
            $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = '-';
        }
        $get_nilai = master_nilai::where(['nim'=>$mhs->nim,'id_tahun'=>$ta])->get();
        foreach($get_nilai as $row){
            if($row->publish_tugas == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = $row->ntugas;
            }

            if($row->publish_uts == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = $row->nuts;
            }
            if($row->publish_uas == 1){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = $row->nuas;
            }
            // if($row->publish_tugas == 1 && $row->publish_uts == 1 && $row->publish_uas == 1){
                if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1){
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = $row->nakhir;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = $row->nhuruf;
                }
            // }
        }
        $filename = $mhs->nim.'-krs.pdf';
        $cek_foto = (!empty($mhs->foto_mhs))?'assets/images/mahasiswa/' . $mhs->foto_mhs:'assets/images/logo/logo-icon.png';
        $data = [
            'mhs' => $mhs,
            'krs' => $krs_now,
            'no' => 1,
            'tahun_ajar' => $tahun_ajar,
            'smt' => $smt,
            'semester' => $semester,
            'logo' => public_path('/assets/images/logo/logo-icon.png'),
            'foto' => public_path('/' . $cek_foto),
            'nilai' => $nilai,
            'ta' => $ta,
        ];

    	$pdf = PDF::loadview('mahasiswa/cetak_khs',$data);
    	return $pdf->download('khs-' . $mhs->nim . '.pdf');
    }
}
