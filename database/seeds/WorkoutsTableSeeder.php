<?php

use Illuminate\Database\Seeder;

class WorkoutsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('workouts')->delete();
        
        \DB::table('workouts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'external_id' => 1034753061,
                'type' => 2,
                'user_id' => 1,
                'title' => 'Endomondo workout',
                'time' => '2016-07-30 14:31:25',
                'import_filename' => '',
                'status' => 1,
                'created_at' => '2018-09-19 19:06:21',
                'updated_at' => '2018-09-19 19:06:21',
            ),
        ));

    }
}