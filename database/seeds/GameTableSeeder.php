<?php

use Illuminate\Database\Seeder;

class GameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->insert([
            'title' => 'Dota 2'
        ]);
        DB::table('games')->insert([
            'title' => 'CS:GO'
        ]);
        DB::table('games')->insert([
            'title' => 'Warframe'
        ]);
        DB::table('games')->insert([
            'title' => 'Team Fortress 2'
        ]);
    }
}
