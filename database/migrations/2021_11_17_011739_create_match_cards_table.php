<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_cards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('match_stat_id')->unsigned();
            $table->bigInteger('match_player_id')->unsigned();
            $table->boolean('type');
            $table->smallInteger('minute');
            $table->timestamps();

            $table->foreign('match_stat_id')->references('id')->on('match_stats');
            $table->foreign('match_player_id')->references('id')->on('match_player_stats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_cards');
    }
}
