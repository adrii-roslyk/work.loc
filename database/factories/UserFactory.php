<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role' => $this->faker->randomElement(['worker', 'employer']),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123456',
            'first_name' => $this->faker->firstName($gender = null),
            'last_name' => $this->faker->lastName,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'phone' => $this->faker->e164PhoneNumber



            //'email_verified_at' => now(),
            //'remember_token' => Str::random(10),
        ];
    }
}