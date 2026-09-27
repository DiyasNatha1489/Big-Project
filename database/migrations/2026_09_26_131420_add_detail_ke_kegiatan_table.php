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
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->after('tanggal');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
            $table->string('lokasi')->nullable()->after('jam_selesai');
            $table->string('dresscode')->nullable()->after('lokasi');
            $table->text('peraturan')->nullable()->after('dresscode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            //
        });
    }
};
