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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sesi_id')->nullable();
            $table->unsignedBigInteger('siswa_id')->nullable();
            $table->enum('tipe', ['absen_foto', 'surat_izin']);
            $table->string('foto', 255);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['hadir', 'telat', 'izin', 'perlu_verifikasi', 'alpha']);
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
