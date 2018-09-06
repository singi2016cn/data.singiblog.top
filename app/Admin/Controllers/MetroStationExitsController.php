<?php

namespace App\Admin\Controllers;

use App\Models\MetroStations;
use App\Models\MetroStationsExits;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class MetroStationExitsController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(MetroStationsExits::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->column('metroStations.name','地铁站点');
            $grid->column('name','名称');
            $grid->column('note','备注');

            $grid->created_at();

            $grid->filter(function($filter){
                $filter->equal('metro_station_id')->select('/admin/api/metro_stations_column_table_select');
                $filter->like('name');
                $filter->equal('code');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(MetroStationsExits::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->select('metro_stations_id','地铁站点')->options(function($id){
                $item = MetroStations::find($id);
                if ($item){
                    return [$item->id=>$item->name];
                }
            })->ajax('/admin/api/metro_stations_column');
            $form->text('name','名称');
            $form->text('note','备注');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
