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
            $table->integer('tahun_berakhir');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif')->index();

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
