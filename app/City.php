<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
//    protected $table = 'table_name';
    protected $guarded = [];
    public $timestamps = false;

    public function rooms()
    {
        return $this->hasManyThrough('App\Room', 'App\TouristObject', 'city_id', 'object_id');
    }

    public function reservations()
    {
        return $this->hasMany('App\Reservation');
    }
}
