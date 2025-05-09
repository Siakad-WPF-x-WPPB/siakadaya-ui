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
            $table->uuid('id')->primary();
            $table->foreignUuid('frs_id')->constrained('frs')->onDelete('cascade');
            $table->foreignUuid('jadwal_id')->constrained('jadwal')->onDelete('cascade');
            $table->enum('status', ['disetujui', 'ditolak', 'pending'])->default('pending');
            $table->date('tanggal_persetujuan');
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
