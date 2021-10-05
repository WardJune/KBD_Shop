<?php

namespace Database\Factories;

use App\Models\Merk;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Merk::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->word(),
            'image' => 'merks/default.jpg'
        ];
    }
}
