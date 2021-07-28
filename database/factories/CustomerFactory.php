<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone' => function () {
                $phone = '';
                for ($i = 0; $i <= 12; $i++) {
                    $phone .= mt_rand(0, 9);
                }
                return $phone;
            },
            'gender' => function () {
                $arr = ['1' => 'male', '2' => 'female'];
                $key = array_rand($arr);
                return "$arr[$key]";
            },
            'password' => Hash::make('warda11!'),
        ];
    }
}
