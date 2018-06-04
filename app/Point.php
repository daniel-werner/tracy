<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Point extends Model
{
	protected $guarded = [];

	public function setCoordinatesAttribute($point){
		$this->attributes['coordinates'] = DB::raw(sprintf("GeomFromText('POINT(%s %s)')", $point->getLongitude(), $point->getLatitude() ) );
	}

	public function getCoordinatesAttribute($value){
		return [
			'lat' => $this->lat,
			'lng' => $this->lng ];
	}

	/**
	 * Get a new query builder for the model's table.
	 * Manipulate in case we need to convert geometrical fields to text.
	 *
	 * @param  bool $excludeDeleted
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function newQuery($excludeDeleted = true)
	{
			$raw = 'ST_X(coordinates) as lng, ST_Y(coordinates) as lat ';

			return parent::newQuery($excludeDeleted)->addSelect('*', DB::raw($raw));

		return parent::newQuery($excludeDeleted);
	}
}
