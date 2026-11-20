<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AstronomicalObjectSeeder::class,
            ObservationLocationSeeder::class,
            EquipmentSeeder::class,
            ObservationSessionSeeder::class,
        ]);
    }
}
