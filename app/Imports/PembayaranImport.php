<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\TbPembayaran;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PembayaranImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //
        foreach ($rows as $row){
            $tanggalBayar = $row[3] ?? null;
            if ($tanggalBayar) {
                $tanggalBayar = Date::excelToDateTimeObject($tanggalBayar)->format('Y-m-d');
            } else {
                $tanggalBayar = date('Y-m-d');
            }
            
            $pembayaran = TbPembayaran::create([
                'nim' => $row[0],
                'jumlah' => $row[1],
                'keterangan' => $row[2] ?? '',
                'status' => 1,
                'tanggal_bayar' => $tanggalBayar,
            ]);
        }
    }
}
