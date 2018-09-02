<?php

namespace App\Admin\Apis;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryApiController extends Controller
{
    public function countriesColumn()
    {
        return Country::paginate(null, ['id', 'short_name as text']);
    }
}
