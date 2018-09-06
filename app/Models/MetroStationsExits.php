<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetroStationsExits extends Model
{
    protected $table = 'metro_station_exits';
    protected $guarded = [];

    public function metroStations()
    {
        return $this->belongsTo(MetroStations::class);
    }
}
