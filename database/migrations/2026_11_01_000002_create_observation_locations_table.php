<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observation_locations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 150);
            $table->string('country', 80)->index();
            $table->string('province', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('elevation')->default(0)->comment('meter di atas permukaan laut');
            $table->string('sky_condition', 60)->nullable();
            $table->unsignedTinyInteger('light_pollution_level')->default(5)->comment('Skala Bortle 1-9');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observation_locations');
    }
};
