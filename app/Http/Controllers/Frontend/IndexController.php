<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function download(Request $request){
        $data_type = $request->input('data_type') ?? 'json';
        //header Content-type 类型库
        if ($data_type == 'json'){
            $header_content_type = ['Content-type'=>'application/json'];
        }else{
            $header_content_type = ['Content-type'=>'application/sql'];
        }
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
