<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('attendance_fingerprints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id')->index(); // Index untuk performa join
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->decimal('terlambat', 8, 2)->default(0); // Simpan angka desimalnya
            $table->string('status_kehadiran', 50)->nullable(); // Hadir, Alpha, dll
            
            // Kolom audit standar
            $table->timestamps();

            // Constraint Foreign Key
            $table->foreign('pegawai_id')
                  ->references('id') // Referensi ke PK 'id' bukan 'id_pegawai'
                  ->on('pegawai_biodata')
                  ->onDelete('cascade');

            // Unique Constraint untuk mencegah duplikasi data jika seeder dijalankan 2x
            $table->unique(['pegawai_id', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
