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
        // 1. Cek Validasi Awal: Jika kolom tanggal kosong, abaikan baris ini
        if (empty($row[1]) || empty($row[3])) {
            return null;
        }

        // 2. Tentukan Format (Sesuaikan dengan data Excel Anda)
        // Gunakan 'd/m/y H:i' jika tanggal duluan (Indonesia/UK)
        // Gunakan 'm/d/y H:i' jika bulan duluan (US)
        $format_asal = 'm/d/y H:i'; 

        // --- PROSES TANGGAL 1 ($row[1]) ---
        $date_object1 = DateTime::createFromFormat($format_asal, $row[1]);
        
        // Cek apakah parsing BERHASIL?
        if ($date_object1 === false) {
            // Jika gagal (misal ini baris Header), return null agar tidak error
            return null; 
        }
        $new_date = $date_object1->format('Y-m-d H:i:s');


        // --- PROSES TANGGAL 2 ($row[3]) ---
        $date_object2 = DateTime::createFromFormat($format_asal, $row[3]);
        
        // Cek apakah parsing BERHASIL?
        if ($date_object2 === false) {
            return null; 
        }
        $new_date2 = $date_object2->format('Y-m-d H:i:s');


        // 3. Return Model
        return new RekeningKoranTemp([
            'post_date'   => $new_date,
            'eff_date'    => $new_date2,
            'cheque_no'   => $row[7],
            'description' => $row[8],
            // Tips: Pakai floatval untuk uang jika ada desimal, atau intval jika bulat
            'debit'       => (int)str_replace(",", "", $row[10]),
            'credit'      => (int)str_replace(",", "", $row[12]),
            'balance'     => (int)str_replace(",", "", $row[13]),
            'transaction' => $row[14],
            'ref_no'      => $row[15],
        ]);
    }
}
