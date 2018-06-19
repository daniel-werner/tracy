<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WorkoutsTimesatampExternalId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workouts', function (Blueprint $table) {
            $table->integer('external_id')->after('id')->nullable()->default(null);
            $table->dateTime('time')->after('title')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workouts', function (Blueprint $table) {
            $table->dropColumn('external_id');
            $table->dropColumn('time');
        });
    }
}
