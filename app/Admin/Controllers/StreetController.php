<?php

namespace App\Admin\Controllers;

use App\Models\County;
use App\Models\Street;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class StreetController extends Controller
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
        return Admin::grid(Street::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->column('county.name','县');
            $grid->column('name','名称');
            $grid->column('code','代码');

            $grid->created_at();

            $grid->filter(function($filter){
                $filter->equal('county_id')->select('/admin/api/counties_column_table_select');
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
        return Admin::form(Street::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->select('county_id','县')->options(function($id){
                $item = County::find($id);
                if ($item){
                    return [$item->id,$item->name];
                }
            })->ajax('/admin/api/counties_column');
            $form->text('name','名称');
            $form->text('code','代码');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
