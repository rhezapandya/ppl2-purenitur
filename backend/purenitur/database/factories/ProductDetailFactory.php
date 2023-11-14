<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductDetail;

class ProductDetailFactory extends Factory
{
    protected $model = ProductDetail::class;

    public function definition()
    {
        return [
            'name_product' => $this->faker->word,
            'sku' => $this->faker->unique()->regexify('[A-Za-z0-9]{6}'),
            'feature_1' => $this->faker->sentence,
            'feature_2' => $this->faker->sentence,
            'feature_3' => $this->faker->sentence,
            'feature_4' => $this->faker->sentence,
        ];
    }
}
