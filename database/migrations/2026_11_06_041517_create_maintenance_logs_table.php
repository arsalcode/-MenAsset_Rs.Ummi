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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->date('maintenance_date');
            $table->enum('type', ['kalibrasi', 'pemeliharaan_rutin', 'perbaikan'])->default('pemeliharaan_rutin');
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('vendor_technician')->nullable();
            $table->enum('status', ['selesai', 'dalam_proses'])->default('selesai');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
