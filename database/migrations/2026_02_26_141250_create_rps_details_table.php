<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // 1. Buat tabel rps_details
        Schema::create('rps_details', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel mata_kuliahs
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            // Relasi ke tabel tahun_ajarans
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            
            $table->text('file_rps');
            $table->dateTime('rps_log')->nullable();
            $table->timestamps();
        });

        // 2. Cari ID untuk kode_ta 20242
        $ta = DB::table('tahun_ajarans')->where('kode_ta', '20242')->first();

        if ($ta) {
            // 3. Pindahkan data lama dari mata_kuliahs ke rps_details
            $oldData = DB::table('mata_kuliahs')->whereNotNull('rps')->get();

            foreach ($oldData as $mk) {
                DB::table('rps_details')->insert([
                    'mata_kuliah_id' => $mk->id,
                    'tahun_ajaran_id' => $ta->id,
                    'file_rps'       => $mk->rps,
                    'rps_log'        => $mk->rps_log,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            // 4. Hapus kolom rps dan rps_log di tabel mata_kuliahs (Opsional)
            // Sebaiknya dilakukan SETELAH memastikan data pindah dengan benar
            Schema::table('mata_kuliahs', function (Blueprint $table) {
                $table->dropColumn(['rps', 'rps_log']);
            });
        }
    }

    public function down(): void {
        // Kembalikan kolom ke mata_kuliahs jika rollback
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->string('rps', 100)->nullable();
            $table->dateTime('rps_log')->nullable();
        });

        Schema::dropIfExists('rps_details');
    }
};