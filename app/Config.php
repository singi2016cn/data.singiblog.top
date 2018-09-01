<?php

namespace App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Config extends Controller
{
    //国家体制
    public static $state_systems = [
        1 => "奴隶制",
        2 => "封建制",
        3 => "资本主义",
        4 => "社会主义",
    ];

    //大洲
    public static $continents = [
        1 => '亚洲',
        2 => '欧洲',
        3 => '北美洲',
        4 => '南美洲',
        5 => '非洲',
        6 => '大洋洲',
    ];

    //地理大区
    public static $regions = [
        1 => '华东',
        2 => '华北',
        3 => '华中',
        4 => '华南',
        5 => '西南',
        6 => '西北',
        7 => '东北',
    ];


}
