<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::truncate();
        $heading = true;
        $input_file = fopen(base_path("database/data/datafurniture.csv"), "r");
        while (($record = fgetcsv($input_file, null, ",")) !== FALSE) {
            if (!$heading) {
                $product = array(
                    "name_product" => $record['0'],
                    "category" => $record['1'],
                    "sku" => $record['2'],
                    "price" => $record['3'],
                    "image" => $record['4'],
                    "rating" => $record['5'],
                );
                // dd($product);
                Product::create($product);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
