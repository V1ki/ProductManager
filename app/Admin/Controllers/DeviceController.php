<?php

namespace App\Admin\Controllers;

use App\Models\Device;

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

/*
+---------------------+--------------+------+-----+-------------------+-----------------------------+
| Field               | Type         | Null | Key | Default         | Extra                       |
+---------------------+--------------+------+-----+-------------------+-----------------------------+
| device_info_id      | bigint(20)   | NO   | PRI |                   | auto_increment              |
| dev_id              | varchar(100) | YES  |     |                   |                             |
| imei                | varchar(100) | NO   |     |                   |                             |
| dev_model_id        | varchar(100) | YES  |     |                   |                             |
| sh_name_id          | int(11)      | NO   |     |                   |                             |
| vehicle_num         | varchar(128) | YES  |     |                   |                             |
| softwareversion     | varchar(100) | YES  |     |                   |                             |
| hardwareversion     | varchar(100) | YES  |     |                   |                             |
| iccid               | varchar(100) | YES  |     |                   |                             |
| phone_num           | varchar(100) | YES  |     |                   |                             |
| customer_id         | int(11)      | YES  |     |                   |                             |
| no_boot             | int(2)       | NO   |     | 0                 |                             |
| mod_time            | timestamp    | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
| log_upload_flag     | int(11)      | NO   |     | 0                 |                             |
| log_upload_protocol | int(11)      | NO   |     | 0                 |                             |
| reboot_reason_code  | int(11)      | NO   |     | 0                 |                             |
| reboot_num          | int(11)      | NO   |     | 0                 |                             |
+---------------------+--------------+------+-----+-------------------+-----------------------------+
 */
            $grid->disableCreateButton();
            $grid->column('device_info_id',trans('device.device_info_id'))->sortable();
            $grid->column('dev_id',trans('device.dev_id'));
            $grid->column('imei',trans('device.imei'));
            $grid->column('softwareversion',trans('device.soft_version'));
            $grid->column('hardwareversion',trans('device.hardware_version'));
            $grid->column('iccid',trans('device.iccid'));
            $grid->column('phone_num',trans('device.phone_num'));
            $grid->column('vehicle_num',trans('device.vehicle_num'));

            $grid->actions(function ($actions){
                $actions->disableDelete();
                $actions->append("<a class='device_delete' data-id='{$actions->getKey()}' href=''><i class='fa fa-trash'></i></a>");
                $script =  <<<SCRIPT

$('.device_delete').on('click', function () {

    // Your code.
    $.get("/api/device/delete/"+$(this).data('id'), function (data) {
        toastr.success('删除成功');
    });
    
});

SCRIPT;
                Admin::script($script);
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
        return Admin::form(Device::class, function (Form $form) {

            $form->display('dev_id',trans('device.dev_id'));
            $form->display('imei',trans('device.imei'));
            $form->display('softwareversion',trans('device.soft_version'));
            $form->display('hardwareversion',trans('device.hardware_version'));
            $form->display('iccid',trans('device.iccid'));
            $form->display('phone_num',trans('device.phone_num'));
            $form->display('vehicle_num',trans('device.vehicle_num'));
            $form->display('customer_id',trans('device.customer_id'));
            $form->display('reboot_reason_code',trans('device.reboot_reason_code'));
            $form->display('reboot_num',trans('device.reboot_num'));
        });
    }
}
