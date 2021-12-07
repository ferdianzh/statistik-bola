<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchPlayerStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_player_stats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('match_stat_id')->unsigned();
            $table->bigInteger('player_id')->unsigned();

            $table->smallInteger('turnover')->default(0);
            $table->smallInteger('duel_lost')->default(0);
            $table->smallInteger('duel_won')->default(0);
            $table->smallInteger('lostball_left')->default(0);
            $table->smallInteger('lostball_center')->default(0);
            $table->smallInteger('lostball_right')->default(0);
            $table->smallInteger('cross_left')->default(0);
            $table->smallInteger('cross_right')->default(0);
            $table->smallInteger('block')->default(0);
            $table->smallInteger('itc')->default(0);
            $table->smallInteger('tackle')->default(0);
            $table->smallInteger('pass_c')->default(0);
            $table->smallInteger('pass_uc')->default(0);
            $table->smallInteger('corner_kick')->default(0);
            $table->smallInteger('assist')->default(0);
            $table->smallInteger('offside')->default(0);
            $table->smallInteger('shot_on')->default(0);
            $table->smallInteger('shot_off')->default(0);
            $table->smallInteger('freekick_c')->default(0);
            $table->smallInteger('freekick_uc')->default(0);

            $table->timestamps();

            $table->foreign('match_stat_id')->references('id')->on('match_stats');
            $table->foreign('player_id')->references('id')->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_player_stats');
    }
}
