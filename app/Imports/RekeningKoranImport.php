<?php

namespace App\Imports;

use App\Models\RekeningKoranTemp;
use Maatwebsite\Excel\Concerns\ToModel;

class RekeningKoranImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new RekeningKoranTemp([
            //
            'post_date' => $row[1],
            'eff_date' => $row[4],
            'cheque_no' => $row[7],
            'description' => $row[8],
            'debit' => (int)$row[10],
            'credit' => (int)$row[12],
            'balance' => (int)$row[13],
            'transaction' => $row[14],
            'ref_no' => $row[15],
        ]);
    }
}
