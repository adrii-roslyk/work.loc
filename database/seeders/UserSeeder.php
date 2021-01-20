<?php

namespace Database\Seeders;

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
        User::factory(1)
            ->create(['role' => 'admin',
                      'email' => 'admin@localhost'])
            ->first(function (User $user) {
                $role = Role::where('name', 'admin')->get();
                $user->roles()->attach($role);
            });

        User::factory(99)
            ->create()
            ->each(function (User $user){
                $roles = Role::where('name','!=','admin')->get();
                foreach ($roles as $role){
                    if ($user->role == $role->name){
                        $user->roles()->attach($role);
                    }
                }
            });
    }
}
