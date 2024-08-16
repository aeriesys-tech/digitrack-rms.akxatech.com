<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use Database\Seeders\SectionSeeder;
// use Database\Seeders\ClusterSeeder;
// use Database\Seeders\PlantSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ClusterSeeder::class,
            // PlantSeeder::class,
            // SectionSeeder::class,
        ]);
    }
}
