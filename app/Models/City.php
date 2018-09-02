<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';
    protected $guarded = [];

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function county()
    {
        return $this->hasMany(County::class);
    }
}
