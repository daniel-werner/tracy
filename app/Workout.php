<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $guarded = [];

    public function points(){
        return $this->hasMany('App\Point');
    }
}
