<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create([
            'name'      => 'Admin',
            'username'  => 'admin',
            'email'     => 'admin@gmail.com',
            'password'  =>  app('hash')->make('Admin1234'),
            'avatar'    => null,
            'role'      => 1,
            'is_active' => true

        ]);
        
    }
}
