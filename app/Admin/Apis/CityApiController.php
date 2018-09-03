<?php

namespace App\Admin\Apis;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityApiController extends Controller
{
    public function citiesColumn()
    {
        return City::paginate(null, ['id', 'name as text']);
    }

    public function citiesColumnTableSelect()
    {
        return City::select(['id','name as text'])->get();
    }
}
