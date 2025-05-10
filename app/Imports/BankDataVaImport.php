<?php

namespace App\Imports;

use App\Models\BankDataVa;
use Maatwebsite\Excel\Concerns\ToModel;

class BankDataVaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new BankDataVa([
            //
            'no_va' => $row[0],
            'keterangan' => $row[1] 
        ]);
    }
}
