<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        $defaultUsers = [
            [
                'id' => 1,
                'name' => 'Isaac Oduh',
                'email' => 'ioduh@mail.com',
                'password' => bcrypt('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach($defaultUsers as $user) {
            DB::table('users')->updateOrInsert(['id' => $user['id']], $user);
        }

        User::factory()->count(100)->create();
    }
}
