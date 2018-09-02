<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    protected $table = 'county';
    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function street()
    {
        return $this->hasMany(County::class);
    }
}
