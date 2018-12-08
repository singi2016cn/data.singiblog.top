<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetroStations extends Model
{
    protected $table = 'metro_stations';
    protected $guarded = [];

    public function metroLines()
    {
        return $this->belongsTo(MetroLines::class);
    }

    public function metroStationExits()
    {
        return $this->hasMany(MetroStationsExits::class);
    }
}
