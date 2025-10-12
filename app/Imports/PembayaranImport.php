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
                'keterangan' => 'SIsa tunggakan terakhir hari Senin 6 Oktober 2025',
                'status' => 1,
                'tanggal_bayar' => date('Y-m-d'),
            ]);
        }
    }
}
