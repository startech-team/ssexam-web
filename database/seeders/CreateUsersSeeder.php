<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'is_admin' => '1',
                'password' => bcrypt('ssm2020'),
                'status' => '0',
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'is_admin' => '3',
                'password' => bcrypt('ssm2020'),
                'status' => '0',
            ],
            [
                'name' => 'User',
                'email' => 'aa@gmail.com',
                'is_admin' => '2',
                'password' => bcrypt('ssm2020'),
                'status' => '0',
            ],
            [
                'name' => 'User',
                'email' => 'kk@gmail.com',
                'is_admin' => '2',
                'password' => bcrypt('ssm2020'),
                'status' => '0',
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
