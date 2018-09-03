<?php

namespace App\Admin\Controllers;

use App\Models\Province;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Config;
use App\Models\Country;

class ProvinceController extends Controller
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
        return Admin::grid(Province::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->column('country.short_name','国家');
            $grid->column('region_id','大区')->display(function($id){
                if ($id) return Config::$regions[$id];
            });
            $grid->column('name','名称');
            $grid->column('code','代码');
            $grid->column('short_name','简称');
            $grid->column('initial','大写首字母');

            $grid->created_at();

            $grid->filter(function($filter){
                $filter->equal('country_id')->select('/admin/api/countries_column_table_select');
                $filter->like('name');
                $filter->equal('code');
                $filter->like('short_name');
                $filter->equal('initial');
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
        return Admin::form(Province::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->select('country_id','国家')->options(function ($id) {
                $item = Country::find($id);
                if ($item) {
                    return [$item->id => $item->short_name];
                }
            })->ajax('/admin/api/countries_column');
            $form->radio('region_id','大区')->options(Config::$regions);
            $form->text('name','名称');
            $form->text('code','代码');
            $form->text('short_name','简称');
            $form->text('initial','大写首字母');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
