<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductDetail;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductDetail::truncate();
        $heading = true;
        $input_file = fopen(base_path("database/data/dataproductdetail.csv"), "r");
        while (($record = fgetcsv($input_file, null, ",")) !== FALSE) {
            if (!$heading) {
                $product = array(
                    "name_product" => $record['0'],
                    "sku" => $record['1'],
                    "feature_1" => $record['2'],
                    "feature_2" => $record['3'],
                    "feature_3" => $record['4'],
                    "feature_4" => $record['5'],
                );
                // dd($product);
                ProductDetail::create($product);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
