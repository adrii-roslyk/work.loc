<?php

namespace Database\Factories;

use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vacancy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vacancy_name' => $this->faker->jobTitle,
            'workers_amount' => $this->faker->numberBetween($min = 1, $max = 15),
            'salary' => $this->faker->numberBetween($min = 1000, $max = 30000)
        ];
    }
}
