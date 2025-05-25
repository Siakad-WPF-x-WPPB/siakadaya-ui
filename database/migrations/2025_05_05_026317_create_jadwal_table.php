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
            $table->uuid('id')->primary();

            // Foreign key constraints
            $table->foreignUuid('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignUuid('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->foreignUuid('mk_id')->constrained('matakuliah')->onDelete('cascade');
            $table->foreignUuid('ruangan_id')->constrained('ruangan')->onDelete('cascade');
            $table->foreignUuid('tahun_ajar_id')->constrained('tahun_ajar')->onDelete('cascade');

            // Jadwal details
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('hari', 10);

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
