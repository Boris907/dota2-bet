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
        ]);
        DB::table('bets')->insert([
            'room_rank' => 'ordinary',
        ]);
        DB::table('bets')->insert([
            'room_rank' => 'expert',
        ]);
    }
}
