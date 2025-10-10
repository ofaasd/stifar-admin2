<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Tagihan;
use App\Models\DetailTagihanKeuangan;

class TagihanImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row){
            $tagihan = Tagihan::create([
                'gelombang' => $row[0],
                'nim' => $row[1],
                'total_bayar' => $row[2],
            ]);
            if($tagihan){
                $array = ['Registrasi','UPP','UPP'];
                foreach($array as $key=>$vaue){
                    if($key == 0){
                        $detail = DetailTagihanKeuangan::create([
                            'id_tagihan' => $tagihan->id,
                            'id_jenis' => 8,
                            'jumlah' => $row[3],
                            
                        ]);
                    }elseif($key == 1){
                        if(is_int($row[4])){
                            $detail = DetailTagihanKeuangan::create([
                                'id_tagihan' => $tagihan->id,
                                'id_jenis' => 2,
                                'jumlah' => $row[4],
                                
                            ]); 
                        }
                        
                    }elseif($key == 2){
                        if(is_int($row[5])){
                            $detail = DetailTagihanKeuangan::create([
                                'id_tagihan' => $tagihan->id,
                                'id_jenis' => 2,
                                'jumlah' => $row[5],
                                
                            ]);
                        }
                    }
                }
            }
        }
        
    }
}
