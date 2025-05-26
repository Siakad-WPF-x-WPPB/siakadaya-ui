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
        Schema::create('nilai', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key constraints
            $table->foreignUuid('frs_detail_id')->constrained('frs_detail')->onDelete('cascade');

            // Nilai details
            $table->enum('status', ['lulus', 'tidak lulus']);
            $table->enum('nilai_huruf', ['A', 'AB', 'B', 'BC', 'C', 'D', 'E']);
            $table->integer('nilai_angka');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
