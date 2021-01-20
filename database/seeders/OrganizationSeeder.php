<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::factory(100)
            ->create()
            ->each(function (Organization $organization){
                $user = User::all()->where('role','employer')->random();
                $organization->user()->associate($user);
                $organization->save();
            });
    }
}
