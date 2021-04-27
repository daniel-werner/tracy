<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Point extends Model
{
    protected $guarded = [];

    public function setCoordinatesAttribute($point)
    {
        $this->attributes['coordinates'] = DB::raw(sprintf(
            "GeomFromText('POINT(%s %s)')",
            $point->getLongitude(),
            $point->getLatitude()
        ));
    }

    public function getCoordinatesAttribute($value)
    {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng ];
    }

    /**
     * Get a new query builder for the model's table.
     * Manipulate in case we need to convert geometrical fields to text.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        $raw = 'ST_X(coordinates) as lng, ST_Y(coordinates) as lat ';

        return parent::newQuery()->addSelect('*', DB::raw($raw));
    }

    public function getTimeAttribute($time)
    {
        $time = new Carbon($time);
        return $time->setTimezone(Auth::user()->timezone)->format('Y-m-d H:i:s');
    }
}
