<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot Many-to-Many antara sesi pengamatan dan perlengkapan.
        Schema::create('equipment_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observation_session_id')->constrained('observation_sessions')->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipment')->restrictOnDelete();
            $table->unsignedInteger('quantity_used')->default(1);
            $table->string('usage_notes', 255)->nullable();
            $table->timestamps();

            $table->unique(['observation_session_id', 'equipment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_session');
    }
};
