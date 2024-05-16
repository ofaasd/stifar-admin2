<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbPesertaOnline;
use App\Models\PmbNilaiRapor;
use App\Models\PmbNilaiTambahan;

class PeringkatPmdpController extends Controller
{
    //
    public function index(Request $request)
    {
        //
            $title = "Peringkat";
            $title2 = "Peringkat PMDP";
            $peserta = PmbPesertaOnline::whereRaw('nopen <> ""')
                    ->where('jalur_pendaftaran',1)
                    ->where('angkatan',date('Y'))
                    ->get();
            $data = [];
            $ids = 0;
            foreach ($peserta as $row) {
                $nilai = PmbNilaiRapor::where('id_peserta',$row->id)->first();
                $mapel = ['mtk'=>'Matematika','bing'=>'B. Inggris','kimia'=>'Kimia','biologi'=>'Biologi','fisika'=>'Fisika'];
                $total_nilai = 0;
                $jumlah_mapel = 0;
                for($i=1; $i<=5; $i++){
                    foreach($mapel as $key=>$value){
                        $new_mapel = 'nilai_' . $key . '_smt' . $i;
                        if($nilai[$new_mapel] > 0 ){
                            $jumlah_mapel++;
                        }

                        $total_nilai += $nilai[$new_mapel];
                    }
                }
                $ntambahan = PmbNilaiTambahan::where('id_peserta',$row->id);
                $nilai_tambahan = 0;
                if($ntambahan->count() > 0){
                    $nilai_tambahan = $ntambahan->sum('nilai');
                }
                $nestedData['id'] = $row->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['nama'] = $row->nama;
                $nestedData['nopen'] = $row->nopen;
                $nestedData['nrata'] = $total_nilai / $jumlah_mapel;
                $nestedData['ntambahan'] = $nilai_tambahan;
                $nestedData['nakhir'] = $total_nilai / $jumlah_mapel;
                $data[] = $nestedData;
            }
            return view('admin.admisi.peringkat.index', compact('title','title2','data'));

    }
    public function table()
    {
        //
        $peserta = PmbPesertaOnline::whereRaw('nopen <> ""')
                ->where('jalur_pendaftaran',1)
                ->where('angkatan',date('Y'))
                ->get();
        $data = [];
        $ids = 0;
        foreach ($peserta as $row) {
            $nilai = PmbNilaiRapor::where('id_peserta',$row->id)->first();
            $mapel = ['mtk'=>'Matematika','bing'=>'B. Inggris','kimia'=>'Kimia','biologi'=>'Biologi','fisika'=>'Fisika'];
            $total_nilai = 0;
            $jumlah_mapel = 0;
            for($i=1; $i<=5; $i++){
                foreach($mapel as $key=>$value){
                    $new_mapel = 'nilai_' . $key . '_smt' . $i;
                    if($nilai[$new_mapel] > 0 ){
                        $jumlah_mapel++;
                    }

                    $total_nilai += $nilai[$new_mapel];
                }
            }
            $ntambahan = PmbNilaiTambahan::where('id_peserta',$row->id);
            $nilai_tambahan = 0;
            if($ntambahan->count() > 0){
                $nilai_tambahan = $ntambahan->sum('nilai');
            }
            $nestedData['id'] = $row->id;
            $nestedData['fake_id'] = ++$ids;
            $nestedData['nama'] = $row->nama;
            $nestedData['nopen'] = $row->nopen;
            $nestedData['nrata'] = $total_nilai / $jumlah_mapel;
            $nestedData['ntambahan'] = $nilai_tambahan;
            $nestedData['nakhir'] = $total_nilai / $jumlah_mapel;
            $data[] = $nestedData;
        }
        return view('admin.admisi.peringkat.table', compact('data'));
    }
    public function add_nilai_tambahan(Request $request){
        $data = [
            'id_peserta' => $request->id_peserta,
            'keterangan' => $request->keterangan,
            'nilai' => $request->nilai,
        ];
        $pmb_nilai_tambahan = PmbNilaiTambahan::create($data);
        return response()->json('Created');
    }
}
