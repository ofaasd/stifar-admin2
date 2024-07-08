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
        Schema::create('mahasiswa_models', function (Blueprint $table) {
          $table->id();
          $table->integer('nim');
          $table->string('nama');
          $table->string('no_ktp');
          $table->integer('jk');
          $table->integer('agama');
          $table->string('tempat_lahir');
          $table->date('tgl_lahir');
          $table->string('nama_ibu');
          $table->string('nama_ayah');
          $table->string('hp_ortu');
          $table->string('alamat');
          $table->string('alamat_semarang');
          $table->string('rt');
          $table->string('rw');
          $table->string('kelurahan');
          $table->string('kecamatan');
          $table->string('kokab');
          $table->string('provinsi');
          $table->string('telp');
          $table->string('hp');
          $table->string('email');
          $table->string('paswd');
          $table->string('status');
          $table->string('foto_mhs');
          $table->string('kelas');
          $table->string('angkatan');
          $table->string('ta');
          $table->string('id_prodi');
          $table->string('id_dsn_wali');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_models');
    }
};
