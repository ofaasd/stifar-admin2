<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\TbPembayaran;

class PembayaranImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //
        foreach ($rows as $row){
            $pembayaran = TbPembayaran::create([
                'nim' => $row[0],
                'jumlah' => $row[1],
                'keterangan' => $row[2] ?? '',
                'status' => 1,
                'tanggal_bayar' => $row[3] ?? date('Y-m-d'),
            ]);
        }
    }
}
