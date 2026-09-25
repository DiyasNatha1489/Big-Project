<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE presensi MODIFY status ENUM('hadir','telat','izin','sakit','perlu_verifikasi') NOT NULL");

        Schema::table('presensi', function (Blueprint $table) {
            $table->enum('alasan_izin', ['izin', 'sakit'])->nullable()->after('tipe');
        });
    }

    public function down(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropColumn('alasan_izin');
        });

        DB::statement("ALTER TABLE presensi MODIFY status ENUM('hadir','telat','izin','perlu_verifikasi') NOT NULL");
    }
};
