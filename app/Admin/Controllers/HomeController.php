<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\County;
use App\Models\Street;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{

    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('后台首页');
            $content->description('后台首页');

            $content->row(view('backend.title'));

            $content->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $data['国家'] = Country::count();
                    $data['省'] = Province::count();
                    $data['市'] = City::count();
                    $data['县'] = County::count();
                    $data['街道'] = Street::count();
                    $column->append(view('backend.box')->with('title','国家省市县街道')->with('data',$data));
                });
            });

            $content->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
        });
    }
}
