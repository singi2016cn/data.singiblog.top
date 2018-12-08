<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetroLines extends Model
{
    protected $table = 'metro_lines';
    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function metroStations()
    {
        return $this->hasMany(MetroStations::class);
    }
}
