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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('gender');
            $table->integer('games_type_id')->unsigned()->nullable();
            $table->dateTimeTz('when_play');
            $table->integer('cost');
            $table->softDeletesTz();
            $table->timestamps();
        });

        Schema::create('games_types', function (Blueprint $table) {
            $table->id();
            $table->string('sport');
            $table->string('type');
            $table->string('type_text');
            $table->softDeletesTz();
            $table->timestamps();
        });

        Schema::create('games_wanted_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('game_id')->unsigned();
            $table->integer('position_id')->unsigned();
            $table->integer('amount')->default(0);
            $table->softDeletesTz();
            $table->timestamps();
        });

        Schema::create('games_locations', function (Blueprint $table) {
            $table->id();
            $table->integer('game_id')->unsigned();
            $table->string('country');
            $table->string('countryCode');
            $table->string('province');
            $table->string('provinceCode');
            $table->string('city');
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->softDeletesTz();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
        Schema::dropIfExists('games_types');
        Schema::dropIfExists('games_wanted_positions');
        Schema::dropIfExists('games_locations');
    }
}
