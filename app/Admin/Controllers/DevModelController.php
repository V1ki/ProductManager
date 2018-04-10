<?php

namespace App\Admin\Controllers;

use App\Models\DevModel;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class DevModelController extends Controller
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

            $content->header(trans('device.model_list'));
            $content->description(trans('device.model_list_desc'));

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

            $content->header(trans('device.edit_model'));
            $content->description(trans('device.edit_model_desc'));

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * All Device Model
     */
    public function all(){
        return DevModel::all();
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('device.create_model'));
            $content->description(trans('device.create_model_desc'));

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
        return Admin::grid(DevModel::class, function (Grid $grid) {

            $grid->column('dev_model_name',trans('device.model_name'));
            $grid->column('dev_model_type',trans('device.model_type')) -> display(function($type){
                return $type == 2 ? trans('device.model_type_android') : trans('device.model_type_not_android');
            });
            $grid->column('customer_id',trans('device.model_customer_name'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(DevModel::class, function (Form $form) {

            $form->display('id', trans('device.model_id'));
//            $table->increments('id');
//            $table->string('name',50);// 型号名称
//            $table->integer('type');//'型号的类型：非安卓、安卓'
//            $table->string('customer_name',50); //客户名称
            $form->text('name',trans('device.model_name'));
            $form->radio('type',trans('device.model_type'))->options([1 => trans('device.model_type_android'), 2=> trans('device.model_type_not_android')])->default(1);
            $form->text('customer_name',trans('device.model_customer_name'));

        });
    }
}
