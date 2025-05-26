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
        Schema::create('tahun_ajar', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Tahun Ajar details
            $table->string('semester', 6);
            $table->integer('tahun_mulai');
            $table->integer('tahun_akhir');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif')->index();

            // Tanggal penting
            $table->date('mulai_frs')->nullable();
            $table->date('selesai_frs')->nullable();

            $table->date('mulai_edit_frs')->nullable();
            $table->date('selesai_edit_frs')->nullable();

            $table->date('mulai_drop_frs')->nullable();
            $table->date('selesai_drop_frs')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajar');
    }
};
