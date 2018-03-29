<?php

use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms')->insert([
            'room_rank' => 'newbie',
            'min_bet' => 2,
            'max_bet' => 50,
            'url' => '/lobby/newbie'
        ]);
        DB::table('rooms')->insert([
            'room_rank' => 'ordinary',
            'min_bet' => 4,
            'max_bet' => 100,
            'url' => '/lobby/ordinary'
        ]);
        DB::table('rooms')->insert([
            'room_rank' => 'expert',
            'min_bet' => 10,
            'max_bet' => 500,
            'url' => '/lobby/expert'
        ]);
    }
}
