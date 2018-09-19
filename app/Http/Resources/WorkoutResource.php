<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class WorkoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $workout = parent::toArray( $request );

        $workout = array_merge( $workout, [
            'distance' => $this->distance,
            'duration' => $this->duration,
            'avgspeed' => $this->avgspeed,
            'minelevation' => $this->minelevation,
            'maxelevation' => $this->maxelevation,
            'avghr' => $this->avghr,
            'minhr' => $this->minhr,
            'maxhr' => $this->maxhr,

        ]);

        return $workout;
    }

    public function toGeoMockJson($request)
    {
        $workout = parent::toArray( $request );
        $points = [];
        foreach($workout['points'] as $point){
            $points[] = [
                'coords' => [
                    'latitude' => $point['coordinates']['lat'],
                    'longitude' => $point['coordinates']['lng'],
                    'accuracy' => 150,
                    'altitude' => $point['elevation']
                ],
                'timestamp' => Carbon::createFromTimeString( $point['time'] )->timestamp * 1000
            ];
        }

        return $points;
    }
}
