<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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

//        dd($workout);

        return $workout;
    }
}
