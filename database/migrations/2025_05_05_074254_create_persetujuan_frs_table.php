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
        Schema::create('persetujuan_frs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_frs')->constrained('frs')->onDelete('cascade');
            $table->foreignId('id_jadwal')->constrained('jadwal')->onDelete('cascade');
            $table->foreignId('nip')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('kode_matakuliah')->constrained('matakuliah')->onDelete('cascade');
            $table->enum('status', ['disetujui', 'ditolak', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_frs');
    }
};
