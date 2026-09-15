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
    Schema::create('kegiatan', function (Blueprint $table) {
        $table->id();
        $table->string('judul', 150);
        $table->text('deskripsi');
        $table->date('tanggal');
        $table->enum('target', ['semua_kelas','kelas_tertentu']);
        $table->unsignedBigInteger('kelas_id')->nullable();
        $table->enum('dibuat_oleh_role', ['admin','wali_kelas']);
        $table->unsignedBigInteger('dibuat_oleh_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
