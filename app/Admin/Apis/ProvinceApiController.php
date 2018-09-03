<?php

namespace App\Admin\Apis;

use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProvinceApiController extends Controller
{
    public function provincesColumn()
    {
        return Province::paginate(null, ['id', 'name as text']);
    }

    public function provincesColumnTableSelect(){
        return Province::select(['id','name as text'])->get();
    }
}
