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
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matkul');
            $table->string('nama_matkul');
            $table->string('nama_matkul_eng');
            $table->integer('kel_mk');
            $table->string('tp')->nullable();
            $table->integer('ruang_teori')->nullable();
            $table->integer('ruang_praktek')->nullable();
            $table->integer('sks_teori')->nullable();
            $table->integer('sks_praktek')->nullable();
            $table->integer('semester')->nullable();
            $table->enum('status_mk', ['Wajib', 'Pilihan', 'Lainnya'])->nullable();
            $table->integer('rumpun');
            $table->enum('status', ['Aktif', 'Tidak Aktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
