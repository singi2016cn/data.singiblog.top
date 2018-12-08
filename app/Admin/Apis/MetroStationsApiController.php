<?php

namespace App\Admin\Apis;

use App\Models\MetroStations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MetroStationsApiController extends Controller
{
    public function metroStationsColumn()
    {
        return MetroStations::paginate(null, ['id', 'name as text']);
    }

    public function metroStationsColumnTableSelect()
    {
        return MetroStations::select(['id','name as text'])->get();
    }
}
