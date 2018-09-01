<?php

namespace App\Admin\Controllers;

use App\Config;
use App\Models\Country;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CountryController extends Controller
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

            $content->header('国家管理');
            $content->description('国家列表');

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

            $content->header('国家管理');
            $content->description('编辑');

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

            $content->header('国家管理');
            $content->description('添加');

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
        return Admin::grid(Country::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->column('continent_id','大洲')->display(function($id){
                return Config::$continents[$id];
            });
            $grid->column('short_name','名称');
            $grid->column('full_name','全称');
            $grid->column('short_name_en','英文简称');
            $grid->column('code','代码');
            $grid->column('state_system','国家体制')->display(function($id){
                return Config::$state_systems[$id];
            });

            $grid->created_at();

            $grid->filter(function ($filter) {
                $filter->where(function($query){
                    if ($this->input > 0){
                        $query->where('continent_id',"{$this->input}");
                    }
                },'大洲')->select(array_merge(['全部'],Config::$continents));
                $state_systems = Config::$state_systems;
                $state_systems[''] = '全部';
                $filter->equal('state_system','国家体制')->radio($state_systems);
                $filter->like('short_name','名称');
                $filter->like('full_name','全称');
                $filter->like('short_name_en','英文简称');
                $filter->equal('code','代码');
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
        return Admin::form(Country::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->radio('continent_id','大洲')->options(Config::$continents)->default(1);
            $form->radio('state_system','国家体制')->options(Config::$state_systems)->default(4);
            $form->text('short_name','名称');
            $form->text('full_name','全称');
            $form->text('short_name_en','英文简称');
            $form->text('code','代码');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
