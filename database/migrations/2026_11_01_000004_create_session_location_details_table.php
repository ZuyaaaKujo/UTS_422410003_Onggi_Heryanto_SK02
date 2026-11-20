<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Relasi One-to-One: satu sesi memiliki tepat satu detail lokasi (UNIQUE pada observation_session_id).
        Schema::create('session_location_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observation_session_id')->unique()->constrained('observation_sessions')->cascadeOnDelete();
            $table->foreignId('observation_location_id')->constrained('observation_locations')->restrictOnDelete();
            $table->string('weather_condition', 60);
            $table->string('visibility_condition', 60);
            $table->decimal('temperature', 4, 1)->nullable();
            $table->unsignedTinyInteger('humidity')->nullable();
            $table->unsignedTinyInteger('light_pollution_level')->nullable()->comment('Skala Bortle 1-9');
            $table->text('environment_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_location_details');
    }
};
