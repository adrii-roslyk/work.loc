<?php

namespace Database\Seeders;

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
        User::factory()
            ->create(['role' => 'admin',
                'email' => 'admin@localhost']);

        User::factory(99)
            ->create();
    }
}
