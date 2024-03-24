<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gelombangs', function (Blueprint $table) {
            $table->id();
            $table->string('no_gel');
            $table->string('nama_gel');
            $table->string('nama_gel_long');
            $table->date('tgl_mulai');
            $table->date('tgl_akhir');
            $table->date('ujian');
            $table->string('jam_ujian');
            $table->string('hari_ujian');
            $table->string('pengumuman');
            $table->date('reg_mulai');
            $table->date('reg_akhir');
            $table->integer('tahun');
            $table->integer('semester');
            $table->integer('jenis');
            $table->integer('pmb_online');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gelombangs');
    }
};
