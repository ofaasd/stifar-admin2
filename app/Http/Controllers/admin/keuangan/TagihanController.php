<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagihanKeuangan;
use App\Models\TbPembayaran;
use App\Models\DetailTagihanMh as DetailTagihanKeuangan;
use App\Models\DetailTagihanKeuangan as DetailTagihanKeuanganTotal;
use App\Models\JenisKeuangan;
use App\Models\SettingKeuangan;
use App\Models\TahunAjaran;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\Tagihan;
use redirect;

class TagihanController extends Controller
{
    //
    public $indexed = ['', 'id', 'nim', 'nama', 'prodi'];
    public function index(Request $request, String $id="1")
    {
        $TagihanKeuangan = TagihanKeuangan::all();
        $prodi = Prodi::all();
        $nama = [];
        $id_tahun = TahunAjaran::where('status','Aktif')->first()->id;
        $ta_all = TahunAjaran::orderBy('id','desc')->get();
        $tahun_ajaran = $id_tahun;
        $gelombang = 1;
        $alumni = 1;

        foreach($prodi as $row){
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        if (empty($request->input('length'))) {
            $jenis = JenisKeuangan::whereIn('id',[1,2,6,9,10,11,12])->get();
            $indexed = $this->indexed;
            $angkatan = Mahasiswa::select("angkatan")->distinct()->orderBy('angkatan','desc')->get();
            $jumlah_jenis = [];
            foreach($jenis as $jen){
                $jumlah_jenis[$jen->id] = SettingKeuangan::where('id_tahun',$id_tahun)->where('id_jenis',$jen->id)->where('id_prodi',$id)->first()->jumlah ?? 0;
                $indexed[] = str_replace(' ', '', $jen->nama);
            }
            $indexed[] = 'total';
            $indexed[] = 'total_bayar';
            $indexed[] = 'status';
            $indexed[] = 'is_publish';
            $title = "tagihan";
            $title2 = "Tagihan";
            $list_bulan = array(
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
            return view('admin.keuangan.tagihan.index', compact('title', 'list_bulan','angkatan','prodi','title2', 'jenis','jumlah_jenis','TagihanKeuangan', 'indexed','id','nama','ta_all','tahun_ajaran','gelombang','alumni'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nama',
                4 => 'prodi',
            ];
            $last_id = 4;
            $jenis = JenisKeuangan::all();

            foreach($jenis as $jen  ){
                $columns[$last_id] = str_replace(' ', '', $jen->nama);
                $last_id++;
            }
             $columns[$last_id] = 'total';
             $columns[++$last_id] = 'total_bayar';
             $columns[++$last_id] = 'status';
             $columns[++$last_id] = 'is_pubish';

            $totalData = Mahasiswa::where('id_program_studi',$id)->count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $angkatan = 2024;
            //tidak bisa karena mahasiswa bulan masuk untuk 2024 blm ada datanya
            // if($id == "5"){
            //     $angkatan = 2024;
            // }

            if (empty($request->input('search.value'))) {
                $tagihan = Mahasiswa::where('id_program_studi',$id)
                    ->where('angkatan','>=',$angkatan)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $tagihan = Mahasiswa::where('id_program_studi', $id)
                            ->where('angkatan','>=',$angkatan)
                            ->where(function ($query) use ($search) {
                                $query->where('id', 'LIKE', "%{$search}%")
                                    ->orWhere('nim', 'LIKE', "%{$search}%")
                                    ->orWhere('nama', 'LIKE', "%{$search}%");
                            })
                            ->orderBy($order, $dir)
                            ->get();

                $totalFiltered = Mahasiswa::where('id_program_studi', $id)
                            ->where('angkatan','>=',$angkatan)
                            ->where(function ($query) use ($search) {
                                $query->where('id', 'LIKE', "%{$search}%")
                                    ->orWhere('nim', 'LIKE', "%{$search}%")
                                    ->orWhere('nama', 'LIKE', "%{$search}%");
                            })
                            ->orderBy($order, $dir)
                            ->count();
            }

            $data = [];
            $list_keu = [];
            $list_tagihan = [];
            $tahun = TahunAjaran::where('status','Aktif')->first();
            
            foreach($tagihan as $tag){
                
                if($id == 1|| $id == 2 || $id == 5){
                    $real_tagihan = TagihanKeuangan::where('nim',$tag->nim)->where('id_tahun',$tahun->id)->where('periode',date('m'))->where('tahun',date('Y'));
                }elseif($id == 3 || $id == 4){
                    $real_tagihan = TagihanKeuangan::where('nim',$tag->nim)->where('id_tahun',$tahun->id);
                }
                if($real_tagihan->count() > 0){
                    $list_tagihan[$tag->id] = $real_tagihan->first();
                    $id_tagihan = $list_tagihan[$tag->id]->id;
                    foreach($jenis as $jen){
                        $list_keu[$tag->id][$jen->id] = DetailTagihanKeuangan::where('id_tagihan',$id_tagihan)->where('id_jenis',$jen->id)->first()->jumlah ?? 0;
                    }
                }else{
                    foreach($jenis as $jen){
                        $list_keu[$tag->id][$jen->id] = 0;
                    }
                }
            }
            // dd($list_tagihan);
            foreach ($tagihan as $index => $row) {
                $upp_bulan = 0;
                $upp_semester = 0;
                $dpp = 0;
                // if(!empty($list_tagihan[$row->id]->total)){
                //     //ambil detail tagihan
                //     $tagihan_total = Tagihan::where('nim',$row->nim)->first();
                //     $total_bayar = $tagihan_total->pembayaran ?? 0;
                    
                    
                    
                    
                //     //jika prodi D3
                //     $status_bayar = false;
                //     $new_total_tagihan = 0;
                //     $i = 1;
                //     if(!empty($tagihan_total->id)){                        
                //         if($id == 1 || $id == 2){
                //             $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
                //             foreach($detail_tagihan as $dt){
                                
                                
                //                 if($dt->id_jenis == 8){
                //                     $total_bayar = $total_bayar - $dt->jumlah;
                //                     $new_total_tagihan += $dt->jumlah;
                                    
                //                 }elseif($dt->id_jenis == 2 && $i == 1){
                //                     $total_bayar = $total_bayar - $dt->jumlah;
                //                     $new_total_tagihan += $dt->jumlah;
                //                     $i++;
                                    
                //                 }elseif($dt->id_jenis == 2 && $i > 1){
                //                     //dipecah UPP per bulan
                //                     $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
                //                     $upp_bulan = $dt->jumlah / 30;
                //                     $bulan_mhs = $mahasiswa->bulan_awal;
                //                     $tahun_mhs = $mahasiswa->angkatan;
                //                     $tagihan_bulan = $list_tagihan[$row->id]->periode;
                //                     $tagihan_tahun = $list_tagihan[$row->id]->tahun;
                //                     $pengurangan = ($tagihan_tahun * 12 + $tagihan_bulan) - ($tahun_mhs * 12 + $bulan_mhs);
                //                     $bulanan = $upp_bulan * $pengurangan;
                //                     $new_total_tagihan += $bulanan;
                //                     $total_bayar = $total_bayar - $bulanan;
                //                     if($total_bayar >= 0){
                //                         $status_bayar = true;
                //                     }
                                    
                //                 }
                //             }
                //         }else{
                //             $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
                //             $i = 0;
                //             foreach($detail_tagihan as $dt){
                //                 if($dt->id_jenis == 2 && $i == 0){
                //                     $new_total_tagihan += $dt->jumlah;
                //                     $total_bayar = $total_bayar - $dt->jumlah;
                //                     $i++;
                //                 }elseif($dt->id_jenis == 8){
                //                     $new_total_tagihan += $dt->jumlah;
                //                     $total_bayar = $total_bayar - $dt->jumlah;
                //                     if($total_bayar >= 0){
                //                         $status_bayar = true;
                //                     }
                //                 }
                //             }
                //         }
                //     }
                //     $tagihan_update = TagihanKeuangan::find($list_tagihan[$row->id]->id);
                //     $tagihan_update->status = $status_bayar ? 1 : 0;
                //     $tagihan_update->save();
                        
                //     $nestedData = [];
                //     $nestedData['fake_id'] = $start + $index + 1;
                //     $nestedData['id'] = $row->id;
                //     $nestedData['nim'] = $row->nim;
                //     $nestedData['nama'] = $row->nama;
                //     $nestedData['prodi'] = $nama[$row->id_program_studi];
                //     foreach($jenis as $jen){
                //         $nestedData[str_replace(' ', '', $jen->nama)] = $list_keu[$row->id][$jen->id];
                //     }
                //     // $nestedData['total'] = $list_tagihan[$row->id]->total ?? 0;
                //     $nestedData['total'] = $new_total_tagihan ?? 0;
                //     // $nestedData['total_bayar'] = $list_tagihan[$row->id]->total_bayar ?? 0;
                //     $nestedData['total_bayar'] = $tagihan_total->pembayaran ?? 0;
                //     $nestedData['status'] = $list_tagihan[$row->id]->status ?? 0;
                //     $nestedData['id_tagihan'] = $list_tagihan[$row->id]->id ?? 0;
                //     $nestedData['is_publish'] = $list_tagihan[$row->id]->is_publish ?? 0;
                //     $data[] = $nestedData;
                // }else{
                    
                    $tagihan_total = Tagihan::where('nim',$row->nim)->first();
                    $total_bayar = $tagihan_total->pembayaran ?? 0;
                    //jika prodi D3
                    $status_bayar = false;
                    $new_total_tagihan = 0;
                    $i = 1;
                    $pengurangan = 0;
                    if(!empty($tagihan_total->id)){                        
                        if($id == 1 || $id == 2){
                            $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
                            foreach($detail_tagihan as $dt){
                                
                                
                                if($dt->id_jenis == 8){
                                    $total_bayar = $total_bayar - $dt->jumlah;
                                    $new_total_tagihan += $dt->jumlah;
                                    
                                }elseif($dt->id_jenis == 2 && $i == 1){
                                    $total_bayar = $total_bayar - $dt->jumlah;
                                    $new_total_tagihan += $dt->jumlah;
                                    $i++;
                                    
                                }elseif($dt->id_jenis == 2 && $i > 1){
                                    //dipecah UPP per bulan
                                    $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
                                    
                                    $upp_bulan = $dt->jumlah / 30;
                                
                                    
                                    $bulan_mhs = $mahasiswa->bulan_awal;
                                    $tahun_mhs = $mahasiswa->angkatan;
                                    $tagihan_bulan = date('m');
                                    $tagihan_tahun = date('Y');
                                    $pengurangan = ($tagihan_tahun * 12 + $tagihan_bulan) - ($tahun_mhs * 12 + $bulan_mhs) + 1;//ditambah 1 karena julidi hitung
                                    $bulanan = $upp_bulan * $pengurangan;
                                    $new_total_tagihan += $bulanan;
                                    $total_bayar = $total_bayar - $bulanan;
                                    if($total_bayar >= 0){
                                        $status_bayar = true;
                                    }
                                    
                                }
                            }
                        }elseif($id == 5){
                           
                            $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
                            foreach($detail_tagihan as $dt){
                                
                                
                                if($dt->id_jenis == 8){
                                    $total_bayar = $total_bayar - $dt->jumlah;
                                    $new_total_tagihan += $dt->jumlah;
                                    
                                }elseif($dt->id_jenis == 2 && $i == 1){
                                    $total_bayar = $total_bayar - $dt->jumlah;
                                    $new_total_tagihan += $dt->jumlah;
                                    $i++;
                                    
                                }elseif($dt->id_jenis == 2 && $i > 1){
                                    //dipecah UPP per bulan
                                    $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
                                    
                                    $upp_bulan = $dt->jumlah / 8;
                                    
                                    $bulan_mhs = $mahasiswa->bulan_awal;
                                    $tahun_mhs = $mahasiswa->angkatan;
                                    $tagihan_bulan = date('m');
                                    $tagihan_tahun = date('Y');
                                    $pengurangan = ($tagihan_tahun * 12 + $tagihan_bulan) - ($tahun_mhs * 12 + $bulan_mhs) + 1;//ditambah 1 karena julidi hitung
                                    $bulanan = $upp_bulan * $pengurangan;
                                    $new_total_tagihan += $bulanan;
                                    $total_bayar = $total_bayar - $bulanan;
                                    if($total_bayar >= 0){
                                        $status_bayar = true;
                                    }
                                    
                                }
                            }
                        }else{
                            $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
                            $i = 0;
                            foreach($detail_tagihan as $dt){
                                if($dt->id_jenis == 2 && $i == 0){
                                    $new_total_tagihan += $dt->jumlah;
                                    $total_bayar = $total_bayar - $dt->jumlah;
                                    $upp_semester = $dt->jumlah;
                                    $i++;
                                }elseif($dt->id_jenis == 8){
                                    $new_total_tagihan += $dt->jumlah;
                                    $total_bayar = $total_bayar - $dt->jumlah;
                                    if($total_bayar >= 0){
                                        $status_bayar = true;
                                    }
                                }elseif($dt->id_jenis == 1){
                                    $dpp = $dt->jumlah;
                                }
                            }
                        }
                    }
                                          
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['nim'] = $row->nim;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['prodi'] = $nama[$row->id_program_studi];
                    foreach($jenis as $jen){
                        if($jen->id == 1){
                            $nestedData[str_replace(' ', '', $jen->nama)] = "" . number_format($dpp, 0, ',', '.');
                        }elseif($jen->id == 2){
                            $nestedData[str_replace(' ', '', $jen->nama)] = "" . number_format($upp_semester, 0, ',', '.');
                        }elseif($jen->id == 6){
                            $nestedData[str_replace(' ', '', $jen->nama)] = "" . number_format($upp_bulan, 0, ',', '.');
                        }else{
                            $nestedData[str_replace(' ', '', $jen->nama)] = "" . number_format($list_keu[$row->id][$jen->id], 0, ',', '.');
                        }
                    }
                    $tagihan_total_bayar = $tagihan_total->pembayaran ?? 0;
                    $status = ($tagihan_total_bayar >= $new_total_tagihan) ? 1 : 0;
                    // $nestedData['total'] = $list_tagihan[$row->id]->total ?? 0;
                    $nestedData['total'] = $new_total_tagihan ?? 0;
                    // $nestedData['total_bayar'] = $list_tagihan[$row->id]->total_bayar ?? 0;
                    $nestedData['total_bayar'] = $tagihan_total_bayar ?? 0;
                    $nestedData['status'] = $status ?? 0;
                    $nestedData['id_tagihan'] = $row->id ?? 0;
                    $nestedData['is_publish'] = $row->is_publish_keuangan ?? 0;
                    $data[] = $nestedData;
                }
            // }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ]);
        }
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
        // Validasi data
        $angkatan = $request->angkatan;
        $id_prodi = $request->id_prodi;
        $periode = $request->periode;
        if(!empty($angkatan)){
            $mhs = Mahasiswa::where('angkatan',$angkatan)->where('id_program_studi',$id_prodi)->get();
            $ta = TahunAjaran::where('status','Aktif')->first();
            $jenis = $request->jenis;
            foreach($mhs as $row){
                $tagihan = TagihanKeuangan::where('nim',$row->nim)->where('id_tahun',$ta->id)->where('periode',$periode)->where('tahun',date('Y'));
                if($tagihan->count() > 0){
                    
                    $tagihan = $tagihan->first();
                    $new_tagihan = TagihanKeuangan::find($tagihan->id);
                    $detail_delete = DetailTagihanKeuangan::where('id_tagihan',$tagihan->id)->delete();
                    $total = 0;
                    //cek berdasarkan Jurusan
                    $prodi = Prodi::find($id_prodi);
                    if($prodi->jenjang == 'DIII'){
                        $tagihan_total = Tagihan::where('nim',$row->nim)->first();
                        //ambil data upp
                        $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->where('id_jenis',2)->orderBy('id','desc')->limit(1)->first();
                        $upp_bagi = ($detail_tagihan->jumlah ?? 0) / 30;
                        $new_detail = DetailTagihanKeuangan::create(
                            [
                                'id_tagihan' => $tagihan->id,
                                'id_jenis' => 2,
                                'jumlah' => $upp_bagi,
                            ]
                        );
                        $total += $upp_bagi;
                    }elseif($prodi->jenjang == 'S1' || $prodi->jenjang == 'S2'){
                    
                        $tagihan_total = Tagihan::where('nim',$row->nim)->first();
                        //ambil data upp
                        
                        //cek semester dulu 

                        //jika semester 1 tambhkan biaya registrasi
                        $tahun_masuk = (string)$row->angkatan . "1";
                        if($ta->kode_ta == (int)$tahun_masuk){
                            if(!empty($tagihan_total)){
                                
                                $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->orderBy('id','desc')->get();
                                
                                $biaya = 0;
                                $i = 0;
                                
                                $destroy = DetailTagihanKeuangan::where('id_tagihan',$tagihan->id)->delete();
                                foreach($detail_tagihan as $dt){
                                    if($dt->id_jenis == 8){
                                        
                                        //kurangi upp
                                        $biaya += $dt->jumlah;
                                        $new_detail = DetailTagihanKeuangan::create(
                                            [
                                                'id_tagihan' => $tagihan->id,
                                                'id_jenis' => 8,
                                                'jumlah' => $dt->jumlah,
                                            ]
                                        );
                                    }elseif($dt->id_jenis == 2 && $i == 0){
                                        $biaya += $dt->jumlah;
                                        $new_detail = DetailTagihanKeuangan::create(
                                            [
                                                'id_tagihan' => $tagihan->id,
                                                'id_jenis' => 2,
                                                'jumlah' => $dt->jumlah,
                                            ]
                                        );
                                        $i++;
                                    }
                                }
                                
                                $total += $biaya;
                                
                            }
                        }else{
                            if(!empty($tagihan_total)){
                                $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->orderBy('id','desc')->get();
                                $biaya = 0;
                                $i = 0;
                                $destroy = DetailTagihanKeuangan::where('id_tagihan',$tagihan->id)->delete();
                                foreach($detail_tagihan as $dt){
                                    if($dt->id_jenis == 2 && $i == 0){
                                        $biaya += $dt->jumlah;
                                        $new_detail = DetailTagihanKeuangan::create(
                                            [
                                                'id_tagihan' => $tagihan->id,
                                                'id_jenis' => 2,
                                                'jumlah' => $dt->jumlah,
                                            ]
                                        );
                                        $i++;
                                    }
                                }
                                
                                $total += $biaya;
                            }
                        }
                        
                        //$total += $upp_bagi;
                    }
                    
                    $new_tagihan->total = $total;
                    $new_tagihan->save();

                }else{
                    if($id_prodi == 1 || $id_prodi == 2 || $id_prodi == 5){
                       $tagihan = TagihanKeuangan::create(
                            [
                                'id_tahun' => $ta->id,
                                'angkatan' => $request->angkatan,
                                'id_prodi' => $request->id_prodi,
                                'periode' => $request->periode,
                                'tahun' => date('Y'),
                                'nim' => $row->nim,
                            ]
                        );
                    }else{
                        $tagihan = TagihanKeuangan::create(
                            [
                                'id_tahun' => $ta->id,
                                'angkatan' => $request->angkatan,
                                'id_prodi' => $request->id_prodi,
                                'tipe_bayar' => 2,
                                'nim' => $row->nim,
                            ]
                        );
                    }
                    
                    $total = 0;
                    $prodi = Prodi::find($id_prodi);
                    if($prodi->jenjang == 'DIII'){
                        $tagihan_total = Tagihan::where('nim',$row->nim)->first();
                        //ambil data upp
                        $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->where('id_jenis',2)->orderBy('id','desc')->limit(1)->first();
                        $upp_bagi = ($detail_tagihan->jumlah ?? 0) / 30;
                        $new_detail = DetailTagihanKeuangan::create(
                            [
                                'id_tagihan' => $tagihan->id,
                                'id_jenis' => 2,
                                'jumlah' => $upp_bagi,
                            ]
                        );  
                        $total += $upp_bagi;
                    }elseif($prodi->jenjang == 'S1' || $prodi->jenjang == 'S2'){
                        $tagihan_total = Tagihan::where('nim',$row->nim)->first();
                        //ambil data upp

                        //cek semester dulu 

                        //jika semester 1 tambhkan biaya registrasi
                        $tahun_masuk = (string)$row->angkatan . "1";
                        if($ta->kode_ta == (int)$tahun_masuk){
                            if(!empty($tagihan_total->id)){
                                $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->orderBy('id','desc')->get();
                                $biaya = 0;
                                $i = 0;
                                foreach($detail_tagihan as $dt){
                                    if($dt->id_jenis == 8){
                                        //kurangi upp
                                        $biaya += $dt->jumlah;
                                        $new_detail = DetailTagihanKeuangan::create(
                                            [
                                                'id_tagihan' => $tagihan->id,
                                                'id_jenis' => 8,
                                                'jumlah' => $dt->jumlah,
                                            ]
                                        );
                                    }elseif($dt->id_jenis == 2 && $i == 0){
                                        $biaya += $dt->jumlah;
                                        $new_detail = DetailTagihanKeuangan::create(
                                            [
                                                'id_tagihan' => $tagihan->id,
                                                'id_jenis' => 2,
                                                'jumlah' => $dt->jumlah,
                                            ]
                                        );
                                        $i++;
                                    }
                                }
                            }
                            $total += $biaya;
                        }else{
                            if(!empty($tagihan_total->id)){
                                $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->orderBy('id','desc')->get();
                                $biaya = 0;
                                $i = 0;
                                foreach($detail_tagihan as $dt){
                                    if($dt->id_jenis == 2 && $i == 0){
                                        $biaya += $dt->jumlah;
                                        $new_detail = DetailTagihanKeuangan::create(
                                            [
                                                'id_tagihan' => $tagihan->id,
                                                'id_jenis' => 2,
                                                'jumlah' => $dt->jumlah,
                                            ]
                                        );
                                        $i++;
                                    }
                                }
                            }
                            $total += $biaya;
                        }
                    }
                    $new_tagihan = TagihanKeuangan::find($tagihan->id);
                    $new_tagihan->tipe_bayar = 2;
                    $new_tagihan->total = $total ?? 0;
                    $new_tagihan->save();

                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Update Generate Tagihan Berhasil Dilakukan',
            ], 200);
        }elseif(!empty($request->id)){
            $id = $request->id;
            $new_tagihan = TagihanKeuangan::find($id);
            $detail_delete = DetailTagihanKeuangan::where('id_tagihan',$id)->delete();
            $total = 0;
            $jenis = $request->jenis;
            foreach($jenis as $key=>$value){
                $data = [
                        'id_tagihan' => $id,
                        'id_jenis' => $request->id_jenis[$key],

                        'jumlah' => $value,
                ];
                $new_detail = DetailTagihanKeuangan::create(
                    $data
                );
                $total += $value;
            }
            $new_tagihan->total = $total;
            $new_tagihan->is_publish = $request->is_publish;
            $new_tagihan->batas_waktu = $request->batas_waktu;
            $new_tagihan->total_bayar = $request->total_bayar;
            if($total == $request->total_bayar){
                $new_tagihan->status = 1;
            }else{
                $new_tagihan->status = $request->status_bayar;
            }
            $new_tagihan->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Update Tagihan Berhasil Dilakukan',
            ], 200);
        }else{
            if(!empty($jenis[0]) || !empty($jenis[1])){
                $tagihan = TagihanKeuangan::create(
                        [
                            'id_tahun' => $ta->id,
                            'angkatan' => $request->angkatan,
                            'batas_waktu' => $request->batas_waktu,
                            'nim' => $request->nim,
                        ]
                    );
                $total = 0;
                $jenis = $request->jenis;
                foreach($jenis as $key=>$value){

                    $new_detail = DetailTagihanKeuangan::create(
                        [
                            'id_tagihan' => $tagihan->id,
                            'id_jenis' => $request->id_jenis[$key],
                            'angkatan' => $request->angkatan,
                            'jumlah' => $value,
                        ]
                    );
                    $total += $value;
                }
                $new_tagihan = TagihanKeuangan::find($tagihan->id);
                $new_tagihan->total = $total;
                $new_tagihan->total_bayar = $request->total_bayar;
                $new_tagihan->status = $request->status_bayar;
                $new_tagihan->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Generate Tagihan Berhasil Dilakukan',
                ], 200);
             }else{
                return response()->json([
                    'status' => 'Error' ,
                    'message' => 'Data Form tidak ditemukan',
                ], 404);
             }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TagihanKeuangan $TagihanKeuangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $tagihan = TagihanKeuangan::find($id);

        if ($tagihan) {
            $detail = DetailTagihanKeuangan::where('id_tagihan',$id)->get();
            $gabung[0] = $tagihan;
            $gabung[1] = $detail;
            return response()->json($gabung);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'tagihan not found',
            ], 404);
        }
    }

    public function publish(string $id)
    {
        $new_tagihan = Mahasiswa::where('id_program_studi',$id)->update(['is_publish_keuangan'=>1]);
        return redirect()->back();
    }
    public function update_publish(Request $request)
    {
        $new_tagihan = Mahasiswa::where('nim',$request->nim)->update(['is_publish_keuangan'=>$request->is_publish]);
        return response()->json(['status' => 'success',
                'message' => 'Data berhasil diperbarui',], 200);
    }
    public function unpublish(string $id)
    {
        $new_tagihan = Mahasiswa::where('id_program_studi',$id)->update(['is_publish_keuangan'=>0]);
        return redirect()->back();
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TagihanKeuangan $TagihanKeuangan)
    {
        //
    }
    public function payment_checking(String $id_prodi){
        //ambil data dari tagihan dengan spesific prodi 
        //$tagihan = TagihanKeuangan::where('id_prodi',$id_prodi)->get();
        $mhs = Mahasiswa::where('id_program_studi',$id_prodi)->get();
        foreach($mhs as $tag){
            $pembayaran = TbPembayaran::where('nim',$tag->nim)->sum('jumlah');
            $total = Tagihan::where('nim',$tag->nim)->update(['pembayaran'=>$pembayaran]);
            // echo $pembayaran . "<br>";
            // echo $tag->nim . "<br>";
        }
        return redirect()->back()->with('success', 'Checking Pembayaran Selesai Dilakukan');
    }
    public function riwayat_pembayaran(String $nim){
        
    }
    public function cetak_tagihan(String $id){
        $tagihan = Mahasiswa::where('id_program_studi',$id)
                    ->orderBy($order, $dir)
                    ->get();
                
        // $data = [
        //     'logo' => public_path('/assets/images/logo/logo-icon.png'),
        //     'jadwal' => $jadwal,
        //     'no' => $no,
        //     'dosen' => $id_dsn,
        //     'tahun_ajar' => $tahun_ajar,
        //     'smt' => $smt,
        //     'semester' => $semester,
        //     'nama_kepala' => $nama_kepala,
        //     'jumlah_input_krs' => $jumlah_input_krs,
        // ];
        // $pdf = PDF::loadView('admin/akademik/jadwal/cetak_krm', $data)
        //             ->setPaper('a4', 'potrait');
        // return $pdf->stream('KRM-' . $id_dsn->npp . '-' . date('YmdHis'). '.pdf');
    }
}
