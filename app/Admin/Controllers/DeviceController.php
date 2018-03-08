<?php

namespace App\Admin\Controllers;

use App\Device;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class DeviceController extends Controller
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
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('device.edit_device'));
            $content->description(trans('device.edit_device_desc'));

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
        return Admin::grid(Device::class, function (Grid $grid) {

            $grid->column('sn',trans('device.sn'));
            $grid->column('soft_version',trans('device.soft_version'));
            $grid->column('hardware_version',trans('device.hardware_version'));
            $grid->column('mac_wifi',trans('device.mac_wifi'));
            $grid->column('mac_bluetooth',trans('device.mac_bluetooth'));
            $grid->column('imei1',trans('device.imei'));

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Device::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->display('sn',trans('device.sn'));
            $form->display('soft_version',trans('device.soft_version'));
            $form->display('hardware_version',trans('device.hardware_version'));
            $form->display('mac_wifi',trans('device.mac_wifi'));
            $form->display('mac_bluetooth',trans('device.mac_bluetooth'));
            $form->display('imei1',trans('device.imei'));
        });
    }
}
