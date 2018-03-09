<?php

namespace App\Admin\Controllers;

use App\Device;
use App\DevModel;
use App\Order;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\MessageBag;

class OrderController extends Controller
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

            $content->header(trans('order.order_list'));
            $content->description(trans('order.order_list'));

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

            $content->header(trans('order.update_order'));
            $content->description(trans('order.update_order'));

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

            $content->header(trans('order.create_order'));
            $content->description(trans('order.create_order'));

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
        return Admin::grid(Order::class, function (Grid $grid) {

            $grid->column('number',trans('order.number'))->sortable();
            $grid->column('sum',trans('order.sum'))->sortable();
            $grid->column('dev_model_id',trans('order.dev_model_id'))->sortable();
            $grid->column('created_at',trans('admin.created_at'))->sortable();

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Order::class, function (Form $form) {

            $form->model()->number = time();
            $form->model()->status = 1;

            $form->display('number', trans('order.number'))->value($form->model()->number);



            $form->number('sum', trans('order.sum'))->rules('required');

            $form->date('order_time',trans('order.order_time'));


            $form->select('sh_name_id',trans('order.sh_name_id')) -> options('/api/allSHInfos') -> load('customer_id','/api/customer');

            $form->select('customer_id',trans('order.customer_id')) -> load('dev_model_id','/api/dev_model');

            // 从api中获取数据
            $form->customSelect('dev_model_id',trans('order.dev_model_id'));//->cusLoad('soft_version','hardware_version','/api/model_versions') ;

            //-> load('hardware_version','/api/model_hardware_versions')
            $form->select('soft_version',trans('order.soft_version'))->rules('required');


            $form->select('hardware_version',trans('order.hardware_version'))->rules('required');

            $lastImei = str_pad_dechex_0(Device::orderBy('imei1','desc')->value('imei1') + 1,15) ;

            $lastMacWifi =  $this->generateMac(Device::orderBy('mac_wifi','desc')->value('mac_wifi'),1);
            $lastBTWifi =  $this->generateMac(Device::orderBy('mac_bluetooth','desc')->value('mac_bluetooth'),1);

            $form->text('imei_start',trans('order.imei_start'))->value($lastImei)->rules('required|max:15|min:15');
            $form->text('mac_wifi_start',trans('order.mac_wifi_start'))->value($lastMacWifi)->rules('required|regex:/^[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}/');
            $form->text('mac_bluetooth_start',trans('order.mac_bluetooth_start'))->value($lastBTWifi)->rules('required|regex:/^[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}/');

//            $form->saving(function (Form $form) {
//
//                // 抛出错误信息
//                $success = new MessageBag([
//                    'title'   => '标题',
//                    'message' => '错误信息',
//                ]);
//
//                return back()->with(compact('success'));
//            });
            $form->saved(function (Form $form) {
                // 保存后回调
                // 创建设备
                $model = $form->model();
                $id = $model->id ;
                $number = $model->number ;
                $dev_model_id = $model->dev_model_id ;
                $soft_version = $model->soft_version ;
                $hardware_version = $model->hardware_version ;
                $imei_start = $model->imei_start ;
                $mac_wifi_start = $model->mac_wifi_start ;
                $mac_bluetooth_start = $model->mac_bluetooth_start ;

                for($i = 0; $i < $model->sum; $i++) {

                    Device::create([
                        'sn' => $number.''.$i,
                        'order_id' => $id,
                        'soft_version' => $soft_version,
                        'hardware_version' => $hardware_version,
                        'dev_model_id' => $dev_model_id,

                        'imei1' => str_pad_dechex_0($imei_start + $i,15) ,
                        //'imei2' => $imei_start,
                        'mac_wifi' => $this->generateMac($mac_wifi_start,$i + 1),
                        'mac_bluetooth' => $this->generateMac($mac_bluetooth_start,$i + 1),

                    ]);

                }



            });



        });
    }






    private function generateMac($start , $index = 0){
        $nums = explode(":",$start);
        if ($nums || $nums->sum != 4) {
            $nums = [0x00,0x00,0x00,0x00] ;
        }

        // 取出 每一位 并转换成 十进制
        $first = hexdec($nums[0]);
        $second = hexdec($nums[1]);
        $third = hexdec($nums[2]);
        $last = hexdec($nums[3]);

        // 大于 最大值 则取下一位、
        if ( ($last + $index ) > 255 ){
            $last = 255 ;

            // 大于 最大值 则取下一位、
            if ( ($third + $index ) > 255 ){
                $third = 255 ;

                // 大于 最大值 则取下一位、
                if ( ($second + $index ) > 255 ){
                    $second = 255 ;

                    // 大于 最大值 则取下一位、
                    if ( ($first + $index ) > 255 ){
                        $first = 255 ;
                    }
                    else {
                        $first = $first + $index ;
                    }
                }
                else {
                    $second = $second + $index ;
                }
            }
            else {
                $third = $third + $index ;
            }

        }
        else {
            $last = $last + $index ;
        }



        return str_pad_dechex_0($first).':'.str_pad_dechex_0($second).':'.str_pad_dechex_0($third).':'.str_pad_dechex_0($last) ;

    }





}
function str_pad_dechex_0($input , $length = 2,$pad_type = STR_PAD_LEFT){
    return str_pad(dechex($input),$length,"0",$pad_type);
}


