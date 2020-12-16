<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(150)
            ->create()
            ->each(function (User $user){
                $roles = Role::all()->random();
                $user->roles()->attach($roles);
            });
    }
}
