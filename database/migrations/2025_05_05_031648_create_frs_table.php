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
        Schema::create('frs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nrp')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('nip')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('id_tahun_ajar')->constrained('tahun_ajar')->onDelete('cascade');
            $table->enum('semester', ['1', '2', '3', '4', '5', '6', '7', '8']);
            $table->date('tanggal_pengisian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frs');
    }
};
