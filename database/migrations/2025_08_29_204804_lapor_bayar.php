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
        //
        Schema::create('lapor_pembayaran', function (Blueprint $table) {
            $table->id(); // Kolom ID unik untuk setiap pembayaran

            // Kolom untuk relasi dengan tabel mahasiswa
            // Pastikan Anda sudah punya tabel 'mahasiswa' dengan kolom 'nim'
            $table->string('nim_mahasiswa');

            $table->date('tanggal_bayar'); // Tanggal ketika pembayaran dilakukan
            $table->string('atas_nama'); // Nama pemilik rekening pengirim
            $table->string('bukti_bayar'); // Path atau nama file bukti pembayaran yang diupload

            // Kolom status untuk tracking (opsional tapi sangat direkomendasikan)
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
