<?php

namespace App\Admin\Apis;

use App\Models\County;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountyApiController extends Controller
{
    public function countiesColumn()
    {
        return County::paginate(null, ['id', 'name as text']);
    }

    public function countiesColumnTableSelect()
    {
        return County::select(['id','name as text'])->get();
    }
}
