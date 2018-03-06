<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'bobka',
            'email' => 'bobka@test.com',
            'password' => bcrypt('123456'),
            'coins' => 200,
            'rate' => '4200'
        ]);

        DB::table('users')->insert([
            'name' => 'valik',
            'email' => 'valik@test.com',
            'password' => bcrypt('123456'),
            'coins' => 0,
        ]);

        DB::table('users')->insert([
            'name' => 'bori',
            'email' => 'bori@test.com',
            'password' => bcrypt('123456'),
            'coins' => 500,
            'rate' => '5800'
        ]);
    }
}
