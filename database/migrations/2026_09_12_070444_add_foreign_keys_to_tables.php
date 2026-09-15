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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->nullOnDelete();
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->foreign('wali_kelas_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->cascadeOnDelete();
        });

        Schema::table('sesi_presensi', function (Blueprint $table) {
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->cascadeOnDelete();
        });

        Schema::table('presensi', function (Blueprint $table) {
            $table->foreign('sesi_id')
                ->references('id')
                ->on('sesi_presensi')
                ->cascadeOnDelete();

            $table->foreign('siswa_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('diverifikasi_oleh')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::table('kegiatan', function (Blueprint $table) {
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->nullOnDelete();

            $table->foreign('dibuat_oleh_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
