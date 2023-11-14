<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_product' => $this->faker->word,
            'category' => $this->faker->word,
            'sku' => $this->faker->unique()->regexify('[A-Za-z0-9]{1,12}'),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'image' => $this->faker->imageUrl(),
            'rating' => $this->faker->randomFloat(2, 0, 5),
            'sold' => $this->faker->numberBetween(0, 255),
        ];
    }
}
