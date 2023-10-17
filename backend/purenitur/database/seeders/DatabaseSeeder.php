<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ProductSeeder::class);
        $this->call(ShipmentSeeder::class);
        $this->call(DiscountSeeder::class);
        $this->call(ProductDetailSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
