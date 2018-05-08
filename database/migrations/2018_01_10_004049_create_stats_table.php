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
            $table->string('user_id')->unsigned();
            $table->integer('total_games')->default(0);
            $table->integer('win_games')->default(0);;
            $table->integer('lose_games')->default(0);;
            $table->double('bet_lose')->default(0);;
            $table->double('bet_win')->default(0);;

            $table->primary(['user_id']);
            $table->foreign('user_id')->references('player_id')->on('users');
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
