<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 150);
            $table->foreignId('equipment_category_id')->constrained('equipment_categories')->restrictOnDelete();
            $table->string('brand', 80)->nullable();
            $table->string('model', 80)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('condition', 20)->default('good')->index();
            $table->string('availability_status', 20)->default('available')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
