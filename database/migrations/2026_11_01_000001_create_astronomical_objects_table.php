<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('astronomical_objects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 120);
            $table->string('category', 30)->index();
            $table->string('constellation', 80)->nullable();
            $table->text('description')->nullable();
            $table->text('astronomical_info')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('astronomical_objects');
    }
};
