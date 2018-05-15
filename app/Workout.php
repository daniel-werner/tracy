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

    protected $params = null;

    private function calculateParams(){
        if( $this->params === null ){
            $this->params = $this->points()->select(DB::raw(
                'sum(ST_Distance_Sphere(B.coordinates, points.coordinates)) AS distance,
                TIMEDIFF( max(points.time), min(points.time)) as duration,
                min(points.heart_rate) as min_hr,
                max(points.heart_rate) as max_hr,
                avg(points.heart_rate) as avg_hr,
                min(points.elevation) as min_elevation,
                max(points.elevation) as max_elevation'
            ))
                ->join('points AS B', 'points.id', '=', DB::raw('B.id -1'))
                ->where( [
                    [ 'points.workout_id', '=', $this->id ],
                    [ 'B.workout_id', '=', $this->id ]
                ] )
                ->first();
        }


    }


    public function getDurationAttribute(){
        $this->calculateParams();

        return $this->params->duration;
    }

    public function getDistanceAttribute(){
        $this->calculateParams();

        return Round($this->params->distance / 1000, 2 );
    }

    public function getAvgSpeedAttribute(){
        $this->calculateParams();

        $time = (strtotime($this->params->duration) - strtotime('TODAY'));
        return round( $this->params->distance / $time * 3.6, 2 );
    }

    public function getMaxHrAttribute(){
        $this->calculateParams();

    return $this->params->max_hr;
}

    public function getMinHrAttribute(){
            $this->calculateParams();

        return $this->params->min_hr;
    }

    public function getMaxElevationAttribute(){
            $this->calculateParams();

        return $this->params->max_elevation;
    }

    public function getMinElevationAttribute(){
        $this->calculateParams();
        return $this->params->min_elevation;
    }

    public function getAvgHrAttribute(){
        $this->calculateParams();

        return round($this->params->avg_hr);
    }
}
