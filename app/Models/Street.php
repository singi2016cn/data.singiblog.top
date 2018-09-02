<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    protected $table = 'street';
    protected $guarded = [];

    public function county()
    {
        return $this->belongsTo(County::class);
    }
}
