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
        Schema::create('asal_sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('npsn');
            $table->string('nss');
            $table->string('jenis');
            $table->string('nama');
            $table->string('alamat');
            $table->string('telepon');
            $table->string('email');
            $table->integer('status');
            $table->string('daerah');
            $table->string('propinsi');
            $table->integer('prov_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asal_sekolahs');
    }
};
