<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->company,
            'city'=> $this->faker->city,
            'country' => $this->faker->country,


            //'user_id' => $this->faker->unique()->numberBetween($min = 1, $max = 150)

        ];
    }
}
