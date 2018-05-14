<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Workout extends Model
{
    protected $guarded = [];

    public function points(){
        return $this->hasMany('App\Point');
    }

    public function getDistanceAttribute(){
        $distance = $this->points()->select(DB::raw('sum(ST_Distance_Sphere(B.coordinates, points.coordinates)) AS distance'))
            ->join('points AS B', 'points.id', '=', DB::raw('B.id -1'))
            ->where( [
                [ 'points.workout_id', '=', $this->id ],
                [ 'B.workout_id', '=', $this->id ]
            ] )
            ->first()
            ->distance;

        return round( $distance / 1000, 2 );
    }

    public function getDurationAttribute(){
        return $this->points()
            ->select( DB::raw( 'TIMEDIFF(max(time), min(time)) AS duration' ) )
            ->where( 'workout_id', '=', $this->id )
            ->first()
            ->duration;
    }
}
