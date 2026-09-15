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
        Schema::create('sesi_presensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->date('tanggal');
            $table->dateTime('buka_pada');
            $table->dateTime('tutup_pada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_presensi');
    }
};
