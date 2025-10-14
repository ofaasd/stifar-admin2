<?php

namespace App\Imports;

use App\Models\RekeningKoranTemp;
use Maatwebsite\Excel\Concerns\ToModel;
use DateTime;
class RekeningKoranImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $date_object = DateTime::createFromFormat('m/d/y H:i', $row[1]);
        $new_date = $date_object->format('Y-m-d H:i');
        $date_object = DateTime::createFromFormat('m/d/y H:i', $row[3]);
        $new_date2 = $date_object->format('Y-m-d H:i');
        return new RekeningKoranTemp([
            //
            'post_date' => $new_date,
            'eff_date' => $new_date2,
            'cheque_no' => $row[7],
            'description' => $row[8],
            'debit' => (int)str_replace(",","",$row[10]),
            'credit' => (int)str_replace(",","",$row[12]),
            'balance' => (int)str_replace(",","",$row[13]),
            'transaction' => $row[14],
            'ref_no' => $row[15],
        ]);
    }
}
