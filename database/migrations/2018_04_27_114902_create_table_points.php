<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workout_id');
            $table->point('coordinates');
            $table->integer('heart_rate')->nullable(true);;
            $table->float('elevation')->nullable(true);
            $table->timestamp('time')->nullable(true);
            $table->timestamps();

            $table->foreign('workout_id')
                ->references('id')->on('workouts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points');
    }
}
