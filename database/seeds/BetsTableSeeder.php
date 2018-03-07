<?php

use Illuminate\Database\Seeder;

class BetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bets')->insert([
            'room_rank' => 'newbie',
            'max_bet' => '50',
        ]);
        DB::table('bets')->insert([
            'room_rank' => 'ordinary',
            'max_bet' => '100',
        ]);
        DB::table('bets')->insert([
            'room_rank' => 'expert',
            'max_bet' => '500',
        ]);
    }
}
