<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Workout extends Model
{
    protected $guarded = [];

    public function points()
    {
        return $this->hasMany('App\Point');
    }

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    const TYPE_RUNNING = 1;
    const TYPE_CYCLING = 2;

    public $types = [
        self::TYPE_RUNNING => 'Running',
        self::TYPE_CYCLING => 'Cycling'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_id', function (Builder $builder) {
            $builder->where('user_id', '=', Auth::id());
        });

        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', '=', self::STATUS_ACTIVE);
        });
    }


    protected $params = null;

    private function calculateParams()
    {
        if ($this->params === null) {
            $this->params = $this->points()->select(DB::raw(
                'sum(ST_Distance_Sphere(B.coordinates, points.coordinates)) AS distance,
                min(points.heart_rate) as min_hr,
                max(points.heart_rate) as max_hr,
                avg(points.heart_rate) as avg_hr,
                min(points.elevation) as min_elevation,
                max(points.elevation) as max_elevation'
            ))
                ->join('points AS B', function ($join) {
                    $join->on('points.index', '=', DB::raw('B.index - 1'));
                    $join->on('points.segment_index', '=', 'B.segment_index');
                    $join->on('points.workout_id', '=', 'B.workout_id');
                })
                ->where([
                    ['points.workout_id', '=', $this->id],
                    ['B.workout_id', '=', $this->id]
                ])
                ->first();


            $durations = $this->points()->select(DB::raw(
                'TIME_TO_SEC(TIMEDIFF( max(B.time), min(points.time))) as duration'
            ))
                ->join('points AS B', function ($join) {
                    $join->on('points.index', '=', DB::raw('B.index - 1'));
                    $join->on('points.workout_id', '=', DB::raw('B.workout_id'));
                })
                ->where([
                    ['points.workout_id', '=', $this->id],
                    ['B.workout_id', '=', $this->id]
                ])
                ->groupBy('points.segment_index')
                ->having('duration', '>', 0)
                ->get();

            $this->params['duration'] = 0;

            foreach ($durations as $duration) {
                $this->params['duration'] += $duration->duration;
            }
        }
    }

    public function getDurationAttribute()
    {
        $this->calculateParams();

        return date('H:i:s', $this->params->duration);
    }

    public function getDistanceAttribute()
    {
        $this->calculateParams();

        return Round($this->params->distance / 1000, 2);
    }

    public function getAvgSpeedAttribute()
    {
        $this->calculateParams();

        $time = $this->params->duration;
        $avgSpeed = 0;

        if ($time > 0) {
            $avgSpeed = round($this->params->distance / $time * 3.6, 2);
        }
        return $avgSpeed;
    }

    public function getMaxHrAttribute()
    {
        $this->calculateParams();

        return $this->params->max_hr;
    }

    public function getMinHrAttribute()
    {
        $this->calculateParams();

        return $this->params->min_hr;
    }

    public function getMaxElevationAttribute()
    {
        $this->calculateParams();

        $this->attributes['maxelevation'] = $this->params->max_elevation;
        return $this->params->max_elevation;
    }

    public function getMinElevationAttribute()
    {
        $this->calculateParams();

        $this->attributes['minelevation'] = $this->params->min_elevation;
        return $this->params->min_elevation;
    }

    public function getAvgHrAttribute()
    {
        $this->calculateParams();

        return round($this->params->avg_hr);
    }

    public function getTypeAttribute($type)
    {
        return $this->types[$type];
    }

    public function savePoints(\Iterator $points)
    {
        $data = [];

        foreach ($points as $index => $point) {
            $data[] = new Point([
                'workout_id' => $this->id,
                'segment_index' => $point->getSegmentIndex(),
                'index' => $index,
                'coordinates' => $point,
                'heart_rate' => $point->getHeartRate(),
                'elevation' => $point->getEvelation(),
                'time' => $point->getTime()->setTimeZone(new \DateTimeZone('UTC')),
            ]);
        }

        return $this->points()->saveMany($data);
    }

    public function getTimeAttribute($time)
    {
        $time = new Carbon($time);
        return $time->setTimezone(Auth::user()->timezone)->format('Y-m-d H:i:s');
    }
}
