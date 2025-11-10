<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Tagihan;
use App\Models\DetailTagihanKeuangan;
use App\Models\Mahasiswa;

class TagihanS1Import implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //
        foreach ($rows as $row){
            $cek_tagihan = Tagihan::where('nim',$row[2])->count();
            if($cek_tagihan == 0){
                $tagihan = Tagihan::create([
                    'gelombang' => $row[0],
                    'nim' => $row[2],
                    'total_bayar' => $row[3],
                ]);
                $mahasiswa = Mahasiswa::where('nim',$row[2])->update(['nopen' => $row[1]]);
                if($tagihan){
                    $array = ['Registrasi','DPP','UPP','UPP'];
                    foreach($array as $key=>$vaue){
                        if($key == 0){
                            $detail = DetailTagihanKeuangan::create([
                                'id_tagihan' => $tagihan->id,
                                'id_jenis' => 8,
                                'jumlah' => $row[4],
                                
                            ]);
                        }elseif($key == 1){
                            if(is_int($row[5])){
                                $detail = DetailTagihanKeuangan::create([
                                    'id_tagihan' => $tagihan->id,
                                    'id_jenis' => 1,
                                    'jumlah' => $row[5],
                                    
                                ]); 
                            }
                            
                        }elseif($key == 2){
                            if(is_int($row[6])){
                                $detail = DetailTagihanKeuangan::create([
                                    'id_tagihan' => $tagihan->id,
                                    'id_jenis' => 2,
                                    'jumlah' => $row[6],
                                    
                                ]); 
                            }
                            
                        }elseif($key == 3){
                            if(is_int($row[7])){
                                $detail = DetailTagihanKeuangan::create([
                                    'id_tagihan' => $tagihan->id,
                                        'id_jenis' => 2,
                                    'jumlah' => $row[7],
                                    
                                ]);
                            }
                        }
                    }
                }
            }else{
                $tagihan = Tagihan::where('nim',$row[2])->update([
                    'gelombang' => $row[0],
                    'total_bayar' => $row[3],
                ]);
                $new_tagihan = Tagihan::where('nim',$row[2])->first();
                $detail = DetailTagihanKeuangan::where('id_tagihan',$new_tagihan->id)->delete();
                
                $mahasiswa = Mahasiswa::where('nim',$row[2])->update(['nopen' => $row[1]]);
                if($tagihan){
                    $array = ['Registrasi','DPP','UPP','UPP'];
                    foreach($array as $key=>$vaue){
                        if($key == 0){
                            $detail = DetailTagihanKeuangan::create([
                                'id_tagihan' => $tagihan->id,
                                'id_jenis' => 8,
                                'jumlah' => $row[4],
                                
                            ]);
                        }elseif($key == 1){
                            if(is_int($row[5])){
                                $detail = DetailTagihanKeuangan::create([
                                    'id_tagihan' => $tagihan->id,
                                    'id_jenis' => 1,
                                    'jumlah' => $row[5],
                                    
                                ]); 
                            }
                            
                        }elseif($key == 2){
                            if(is_int($row[6])){
                                $detail = DetailTagihanKeuangan::create([
                                    'id_tagihan' => $tagihan->id,
                                    'id_jenis' => 2,
                                    'jumlah' => $row[6],
                                    
                                ]); 
                            }
                            
                        }elseif($key == 3){
                            if(is_int($row[7])){
                                $detail = DetailTagihanKeuangan::create([
                                    'id_tagihan' => $tagihan->id,
                                        'id_jenis' => 2,
                                    'jumlah' => $row[7],
                                    
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
