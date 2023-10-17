<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shipment;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shipment::truncate();
        $heading = true;
        $input_file = fopen(base_path("database/data/datashipment.csv"), "r");
        while (($record = fgetcsv($input_file, null, ",")) !== FALSE) {
            if (!$heading) {
                $product = array(
                    "name_shipment" => $record['0'],
                    "type_shipment" => $record['1'],
                    "day_estimation" => $record['2'],
                    "price_shipment" => $record['3'],
                );
                // dd($product);
                Shipment::create($product);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
