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
        Schema::create('frs_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('frs_id')->constrained('frs')->onDelete('cascade');
            $table->foreignUuid('jadwal_id')->constrained('jadwal')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frs_detail');
    }
};
