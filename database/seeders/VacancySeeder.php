<?php

namespace Database\Seeders;

use App\Models\Organization;
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
        Vacancy::factory(150)
            ->create()
            ->each(function (Vacancy $vacancy) {
                $organization = Organization::all()->random();
                $vacancy->organization()->associate($organization);
                $vacancy->save();
            });
    }
}
