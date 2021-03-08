<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaysAvailablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days_availables', function (Blueprint $table) {
            $table->id();
            $table->integer('dayOfTheWeek');
            $table->json('available');
            $table->integer('user_id');
            $table->softDeletesTz();
            $table->timestampsTz();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('days_availables');
        Schema::dropIfExists('days_available_user');
    }
}
