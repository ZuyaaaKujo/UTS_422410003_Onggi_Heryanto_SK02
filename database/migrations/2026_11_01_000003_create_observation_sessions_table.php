<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observation_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name', 150);
            $table->foreignId('astronomical_object_id')->constrained('astronomical_objects')->restrictOnDelete();
            $table->date('observation_date')->index();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status', 20)->default('scheduled')->index();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observation_sessions');
    }
};
