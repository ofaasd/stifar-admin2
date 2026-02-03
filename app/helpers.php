<?php

namespace App;

use App\Models\ActivityLog;
use Auth;
use App\Models\MasterSkripsi;
use App\Models\master_nilai;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class helpers
{
    // public static $number_key = '3EYdFkP7uhk5RX6D';
    // public static $wa_api = 'X2Y7UZOZT0WVQVTG';

    public static function getNilaiHuruf(float $nilai)
    {
        if($nilai >= 76){
            return 'A';
        }elseif($nilai >= 71){ // Anda tidak perlu mengecek && $nilai <= 75 lagi
            return 'AB';
        }elseif($nilai >= 66){
            return 'B';
        }elseif($nilai >= 61){
            return 'BC';
        }elseif($nilai >= 56){
            return 'C';
        }elseif($nilai >= 51){
            return 'CD';
        }elseif($nilai >= 41){
            return 'D';
        }else{
            return 'E';
        }
    }

    public static function getDaftarNilaiMhs($nim)
    {
        return master_nilai::select(
                'master_nilai.*',
                'a.hari',
                'a.kel',
                'b.kode_matkul',
                'b.nama_matkul',
                'b.nama_matkul_eng',
                'b.sks_teori',
                'b.sks_praktek',
                'b.kode_matkul'
            )
            ->leftJoin('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
            ->join('mata_kuliahs as b', function($join) {
                $join->on('a.id_mk', '=', 'b.id')
                        ->orOn('master_nilai.id_matkul', '=', 'b.id');
            })
            ->where('nim', $nim)
            ->whereNotNull('master_nilai.nakhir')
            ->get();
    }

    public function getIPK($nim)
    {
        $getNilai = self::getDaftarNilaiMhs($nim);
        $totalSks = 0;
        $totalIps = 0;
        foreach ($getNilai as $row) {
            $sks = ($row->sks_teori + $row->sks_praktek);
            $totalSks += $sks;
            if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1)
            {
                $totalIps +=  ($row->sks_teori+$row->sks_praktek) * $this->getKualitas($row->nhuruf);
            }
        }
        return $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;
    }

    public function getAccByPengajuanSkripsi($idMaster)
    {
        $log = ActivityLog::where('description', 'acc-judul')
            ->where('properties->id_master', $idMaster)
            ->latest()
            ->first();
        if ($log) {
            $accByUser = \App\Models\User::find($log->causer_id);
            return $accByUser ? $accByUser->name : '-';
        }
        return null;
    }

    public function getSksTempuh($nim)
    {
        $getNilai = self::getDaftarNilaiMhs($nim);
        $totalSks = 0;
        foreach ($getNilai as $row) {
            $sks = ($row->sks_teori + $row->sks_praktek);
            $totalSks += $sks;
        }
        return $totalSks;
    }

    public static function getKualitas(string $huruf)
    {
        if($huruf == 'A'){
            return 4;
        }elseif($huruf == 'AB'){
            return 3.5;
        }elseif($huruf == 'B'){
            return 3;
        }elseif($huruf == 'BC'){
            return 2.5;
        }elseif($huruf == 'C'){
            return 2;
        }elseif($huruf == 'CD'){
            return 1.5;
        }elseif($huruf == 'D'){
            return 1;
        }else{
            return 0;
        }
    }

    public function getIdMasterSkripsi()
    {
        $user  = Auth::user();
        $email = $user->email;
        $nim   = explode('@', $email)[0];
        $master = MasterSkripsi::where('nim', $nim)->first();
        return $master ? $master->id : null;
    }

    public static function send_wa($data)
    {
        $wa_setting = DB::table('wa_api')->where('status',1)->first();
        $number_key = $wa_setting->number_key;
        $wa_api = $wa_setting->wa_api;

        $curl = curl_init();

        $dataSending = [];
        $dataSending['api_key'] = $wa_api;
        $dataSending['number_key'] = $number_key;

        $prefix = substr($data['no_wa'], 0, 2);
        if ($prefix == '62') {
        $new_prefix = 0;
        $no_wa = $new_prefix . substr($data['no_wa'], 2);
        } else {
        $no_wa = $data['no_wa'];
        }
        $dataSending['phone_no'] = $no_wa;
        $dataSending['message'] = $data['pesan'];

        curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.watzap.id/v1/send_message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($dataSending),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function encryptId($string)
    {
        return Crypt::encryptString($string . "stifar");
    }

    public static function decryptId($string)
    {
        $decrypted = Crypt::decryptString($string);
        return str_replace("stifar", "", $decrypted);
    }
}
