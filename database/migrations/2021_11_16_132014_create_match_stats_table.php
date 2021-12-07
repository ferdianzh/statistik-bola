<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_stats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('match_id')->unsigned();
            $table->tinyInteger('halftime');
            // $table->bigInteger('team_id')->unsigned();
            // $table->boolean('is_home')->default(0);
            
            $table->smallInteger('score')->default(0);
            $table->smallInteger('ball_position')->default(0);
            $table->smallInteger('lostball_left')->default(0);
            $table->smallInteger('lostball_center')->default(0);
            $table->smallInteger('lostball_right')->default(0);
            $table->smallInteger('accuracy_freekick')->default(0);
            $table->smallInteger('accuracy_shot')->default(0);
            $table->smallInteger('crossing_pass')->default(0);

            $table->smallInteger('assist')->default(0);
            $table->smallInteger('block')->default(0);
            $table->smallInteger('intercept')->default(0);
            $table->smallInteger('tackle')->default(0);
            $table->smallInteger('foul')->default(0);
            $table->smallInteger('turnover')->default(0);
            $table->smallInteger('saves')->default(0);
            
            $table->smallInteger('switch_1pass')->default(0);
            $table->smallInteger('switch_2pass')->default(0);
            $table->smallInteger('switch_3pass')->default(0);
            $table->smallInteger('switch_4pass')->default(0);
            $table->smallInteger('switch_5pass')->default(0);
            $table->smallInteger('switch_npass')->default(0);
            
            $table->timestamps();

            $table->foreign('match_id')->references('id')->on('matches');
            // $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_stats');
    }
}
