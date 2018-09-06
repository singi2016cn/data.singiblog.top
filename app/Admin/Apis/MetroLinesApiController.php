<?php

namespace App\Admin\Apis;

use App\Models\MetroLines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MetroLinesApiController extends Controller
{
    public function metroLinesColumn()
    {
        return MetroLines::paginate(null, ['id', 'name as text']);
    }

    public function metroLinesColumnTableSelect()
    {
        return MetroLines::select(['id','name as text'])->get();
    }
}
