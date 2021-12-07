<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('team_id')->unsigned();
            $table->bigInteger('enemy_id')->unsigned();
            $table->bigInteger('site_id')->unsigned();
            $table->dateTime('datetime');
            $table->boolean('is_league')->default(0);
            $table->boolean('is_away')->default(0);
            $table->boolean('is_finish')->default(0);
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('enemy_id')->references('id')->on('teams');
            $table->foreign('site_id')->references('id')->on('sites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
