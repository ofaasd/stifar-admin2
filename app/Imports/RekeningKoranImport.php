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
            'eff_date' => $row[2],
            'cheque_no' => $row[3],
            'description' => $row[4],
            'debit' => (int)$row[5],
            'credit' => (int)$row[6],
            'balance' => (int)$row[7],
            'transaction' => $row[8],
            'ref_no' => $row[9],
        ]);
    }
}
