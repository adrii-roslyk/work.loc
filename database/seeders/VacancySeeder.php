<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::all()->each(function (Organization $organization){
            $organization->vacancies()->saveMany(Vacancy::factory(3)->create());
        });

        Vacancy::all()->each(function (Vacancy $vacancy){
            $quantity = mt_rand(1, $vacancy->workers_amount);
            $users = User::where('role', 'worker')->inRandomOrder()->take($quantity)->get();
            $vacancy->users()->attach($users);
        });
    }
}
