<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('game_id')->unsigned();
            $table->integer('total_games');
            $table->integer('win_games');
            $table->integer('lose_games');
            $table->integer('bet_lose');
            $table->integer('bet_win');
            $table->integer('all_coins');

            $table->primary(['user_id', 'game_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('game_id')->references('id')->on('games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats');
    }
}
