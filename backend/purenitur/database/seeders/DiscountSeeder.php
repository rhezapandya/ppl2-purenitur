<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount::truncate();
        $heading = true;
        $input_file = fopen(base_path("database/data/datadiscount.csv"), "r");
        while (($record = fgetcsv($input_file, null, ",")) !== FALSE) {
            if (!$heading) {
                $product = array(
                    "name_discount" => $record['0'],
                    "percentage" => $record['1'],
                );
                // dd($product);
                Discount::create($product);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
