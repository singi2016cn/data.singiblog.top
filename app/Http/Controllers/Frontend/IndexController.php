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
        $data_type = $request->input('data_type') ?? 'json';
        //header Content-type 类型库
        $header_content_type = ['Content-type'=>Config::$content_type[$data_type]];
        $download_filename = '2018.'.$data_type;
        $download_path = public_path('storage/output/pcc/'.$download_filename);
        switch ($request->input('type')){
            case 'pcc':
                return response()->download($download_path,$download_filename,$header_content_type);
                break;
            default:
                return false;
        }
    }
}
