<?php

namespace App\Admin\Controllers;

use App\Models\Airports;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Country;

class AirportsController extends Controller
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
        return Admin::grid(Airports::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->column('city_name','城市');
            $grid->column('name','名称');

            $grid->created_at();

            $grid->filter(function($filter){
                $filter->equal('country_id')->select('/admin/api/countries_column_table_select');
                $filter->like('city_name');
                $filter->like('name');
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
        return Admin::form(Airports::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->select('country_id','国家')->options(function ($id) {
                $item = Country::find($id);
                if ($item) {
                    return [$item->id => $item->short_name];
                }
            })->ajax('/admin/api/countries_column');
            $form->text('city_name','城市');
            $form->text('name','名称');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
