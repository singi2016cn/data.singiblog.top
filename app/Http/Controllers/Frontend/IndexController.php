<?php

namespace App\Http\Controllers\Frontend;

use App\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function download(Request $request){
        $type = $request->input('type');
        $data_type = $request->input('data_type') ?? 'json';
        //header Content-type 类型库
        $header_content_type = ['Content-type'=>Config::$content_type[$data_type]];
        $download_filename = date('Y').'.'.$data_type;
        $download_path = public_path('storage/output/'.$type.'/'.$download_filename);

        return response()->download($download_path,$download_filename,$header_content_type);
    }
}
