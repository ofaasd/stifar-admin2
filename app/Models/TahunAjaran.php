<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $fillable = [
        'kode_ta',
        'tgl_awal',
        'tgl_awal_kuliah',
        'tgl_akhir',
        'status',
        'krs',
        'krs_start',
        'krs_end',
        'keterangan',
        'kuesioner',
    ];

    public function getPeriodeFormattedAttribute()
    {
        $semester = substr($this->kode_ta, -1) == '1' ? 'Genap' : 'Ganjil';
        $tahun_awal = date('Y', strtotime($this->tgl_awal_kuliah));
        $tahun_akhir = date('Y', strtotime($this->tgl_akhir));
        return "Semester $semester $tahun_awal/$tahun_akhir";
    }
}
