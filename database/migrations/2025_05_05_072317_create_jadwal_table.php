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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kelas')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('kode_matakuliah')->constrained('matakuliah')->onDelete('cascade');
            $table->foreignId('nip')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('kode_ruangan')->constrained('ruangan')->onDelete('cascade');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->date('hari');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
