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
        Vacancy::factory(100)
            ->create()
            ->each(function (Vacancy $vacancy) {
                $organization = Organization::all()->random();
                $vacancy->organization()->associate($organization);
                $vacancy->save();

                $quantity = mt_rand(1, $vacancy->workers_amount);
                $users = User::all()->where('role', 'worker')->random($quantity);
                $vacancy->users()->attach($users);
            });
    }
}
