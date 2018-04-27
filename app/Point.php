<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Point extends Model
{
	protected $guarded = [];


	public function setCoordinatesAttribute(Utilities\WorkoutImport\Point $point){
		$this->attributes['coordinates'] = DB::raw(sprintf("GeomFromText('POINT(%s %s)')", $point->getLatitude(),$point->getLongitude() ) );
	}
}
