<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class FingerprintImport implements ToCollection, WithStartRow
{
    /**
     * Tentukan baris awal data (baris 2, karena baris 1 adalah Header)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        // Cache ID Pegawai untuk validasi (biar tidak query berulang-ulang)
        $validUserIds = DB::table('pegawai_biodata')->pluck('nama_lengkap','no_absensi')->all();
        
        $i = 0;
        foreach ($rows as $row) {
            // Mapping Index Excel (0-based) berdasarkan file absensi-finger.csv
            // Index 0: Nama Staff (Kolom A)
            // Index 1: No. Staff (Kolom B)
            // Index 3: Tanggal (Kolom D)
            // Index 8: Masuk (Kolom I)
            // Index 10: Keluar (Kolom K)
            
            $noStaff = trim($row[1] ?? '');
            $nama = $row[0] ?? '';
            if($nama !== null){
                //ambilkan 2 kata tengah dari $nama untuk di cari di database 
                $namaParts = explode(' ', $nama);
                if(count($namaParts) > 2){
                    $nama = $namaParts[1] . ' ' . $namaParts[2];
                }
                //cek di database ada berapa nama yang mirip
                   
                $jml = DB::table('pegawai_biodata')
                    ->where('nama_lengkap', 'like', '%' . $nama . '%')
                    ->count();
                if($jml == 1){
                    DB::table('pegawai_biodata')
                    ->where('nama_lengkap', 'like', '%' . $nama . '%')
                    ->update(['no_absensi' => $noStaff]);
                }
                
            }
            
            // 1. Validasi: Lewati jika User ID tidak valid/kosong
            if (empty($noStaff) || !isset($validUserIds[$noStaff])) {
                continue;
            }

            
                // 2. Parse Tanggal
                // Excel kadang membaca tanggal sebagai serial number, kadang string.
                // Kode ini berasumsi formatnya string 'dd/mm/yyyy' sesuai CSV sebelumnya.
            $tanggalStr = $row[3] ?? null;
            if (!$tanggalStr) continue;

            // Bersihkan string tanggal jika ada spasi
            $tanggalStr = trim($tanggalStr);
            
            try {
                $dateObj = Carbon::createFromFormat('d/m/Y', $tanggalStr);
            } catch (\Exception $e) {
                // Fallback jika format Excel beda (misal Y-m-d)
                continue; 
            }
                
                $tanggalSql = $dateObj->format('Y-m-d');

                // 3. Logic Konversi Waktu ke UNIX Timestamp (INT)
                $jamMasukStr = $row[8] ?? null; 
                $jamKeluarStr = $row[10] ?? null;
                
                $startTimestamp = null;
                $endTimestamp = null;
                $statusKehadiran = 'Alpha';

                // Hitung Timestamp Masuk
                if ($jamMasukStr) {
                    // Pastikan format jam bersih (misal 08:47)
                    $jamMasukStr = substr(trim($jamMasukStr), 0, 5); 
                    $masukFull = Carbon::createFromFormat('Y-m-d H:i', "$tanggalSql $jamMasukStr");
                    $startTimestamp = $masukFull->timestamp;
                    $statusKehadiran = 'Hadir';
                }

                // Hitung Timestamp Keluar
                if ($jamKeluarStr) {
                    $jamKeluarStr = substr(trim($jamKeluarStr), 0, 5);
                    $keluarFull = Carbon::createFromFormat('Y-m-d H:i', "$tanggalSql $jamKeluarStr");
                    $endTimestamp = $keluarFull->timestamp;
                }

                // 4. Update or Insert ke Tabel Presences
                DB::table('presences')->updateOrInsert(
                    [
                        'user_id' => $noStaff,
                        'day'     => $tanggalSql,
                    ],
                    [
                        'is_remote'         => 0, // WFO
                        'attendance_source' => 'machine', // Penanda sumber import excel
                        
                        'start'             => $startTimestamp ?? 0,
                        'end'               => $endTimestamp,
                        
                        // Metadata default agar tidak error
                        'lat_start'         => null,
                        'ip_start'          => '127.0.0.1',
                        'browser_start'     => 'Excel Import',
                        
                        'updated_at'        => now(),
                        // created_at tidak perlu diupdate
                    ]
                );

           
        }
    }
}