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
        Schema::create('kontrak_kuliah_models', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jadwal');
            $table->integer('tugas');
            $table->integer('uts');
            $table->integer('uas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak_kuliah_models');
    }
};
