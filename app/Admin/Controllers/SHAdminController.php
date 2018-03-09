<?php

namespace App\Admin\Controllers;

use App\SHAdmin ;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SHAdminController extends Controller
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

            $content->header(trans('device.device_list'));
            $content->description(trans('device.device_list_desc'));

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $sh_name_id
     * @return Content
     */
    public function edit($sh_name_id)
    {
        return Admin::content(function (Content $content) use ($sh_name_id) {

            $content->header(trans('device.edit_device'));
            $content->description(trans('device.edit_device_desc'));

            $content->body($this->form()->edit($sh_name_id));
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

            $content->header(trans('device.create_device'));
            $content->description(trans('device.create_device_desc'));

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
        return Admin::grid(SHAdmin::class, function (Grid $grid) {

            $grid->column('sh_name_id',trans('sh_admin.sh_name_id'));
            $grid->column('sh_name',trans('sh_admin.sh_name'));
            $grid->column('sh_user_name',trans('sh_admin.sh_user_name'));

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SHAdmin::class, function (Form $form) {
            $form->display('sh_name',trans('sh_admin.sh_name'));
        });
    }
}
