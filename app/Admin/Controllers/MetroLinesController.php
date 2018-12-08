<?php

namespace App\Admin\Controllers;

use App\Models\MetroLines;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\City;

class MetroLinesController extends Controller
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
        return Admin::grid(MetroLines::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->column('city_name','城市');
            $grid->column('name','名称');
            $grid->column('code','代码');

            $grid->created_at();

            $grid->filter(function ($filter){
                $filter->equal('city_name','城市');
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
        return Admin::form(MetroLines::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text('city_name','城市');
            $form->text('name','名称');
            $form->text('code','代码');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
