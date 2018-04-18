<?php

namespace App\Admin\Controllers;

use App\Models\Customers;
use App\Models\Device;
use App\Models\DevModel;
use App\Models\Order;
use App\Models\DeviceExtInfo;

use App\Models\SHAdmin;
use App\Models\UpgradePackage;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

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

            $content->body(Admin::form(Order::class, function (Form $form) {

                $form->disableReset();

                $form->model()->order_number = gmstrftime('%g%W') . '0001';
                $form->model()->status = 1;

                $form->display('order_number', trans('order.number'))->value($form->model()->order_number);

                $count = DeviceExtInfo::where('order_id', $form->model()->order_id)->count();

                $form->html('<div class="box-body">'.$count.'</div>', $label = '已生产数量');
                $form->display('order_sum', trans('order.sum'));

                $form->date('order_time', trans('order.order_time'))->default(gmstrftime('%Y-%m-%d'));


                //$form->select('sh_name_id', trans('order.sh_name_id'));


                $form->select('sh_name_id', trans('order.sh_name_id'))->options('/api/allSHInfos')->load('customer_id', '/api/customer')->value($form->model()->sh_name_id);

                $form->select('customer_id', trans('order.customer_id'))->load('dev_model_id', '/api/dev_model');

                // 从api中获取数据
                $form->select('dev_model_id', trans('order.dev_model_id'))->load('package_id', '/api/packages', 'id', 'file');
                $form->select('package_id', trans('order.package_id'));
                //-> load('hardware_version','/api/model_hardware_versions')


                $form->select('soft_version', trans('order.soft_version'))->rules('required');

                $form->select('hardware_version', trans('order.hardware_version'))->rules('required');

                $form->select('inner_dev_model_id', trans('order.inner_dev_model_id'))->options([1 => 'M03U', 2 => 'M03C']);

                $script = <<<EOT
$(document).off('change', ".package_id");
$(document).on('change', ".package_id", function () {
    var soft_version = $(this).closest('.fields-group').find(".soft_version");
    var hardware_version = $(this).closest('.fields-group').find(".hardware_version");
    $.get("/api/packages_version?q="+$('.package_id').val(), function (data) {
    
    
        soft_version.find("option").remove();
        $(soft_version).select2({
            data: $.map(data, function (d) {
                d.id = d.soft;
                d.text = d.soft;
                return d;
            })
        }).trigger('change');
        hardware_version.find("option").remove();
        $(hardware_version).select2({
            data: $.map(data, function (d) {
                d.id = d.hardware;
                d.text = d.hardware;
                return d;
            })
        }).trigger('change');
    });
});
$(document).on('change',".inner_dev_model_id",function(){
    alert($('.inner_dev_model_id').val());
});
EOT;
                Admin::script($script);



                $lastImei = $this->generateIMEI('01');


                $lastMacWifi = $this->generateMac(DeviceExtInfo::orderBy('mac_wifi', 'desc')->value('mac_wifi'), 1);
                $lastBTWifi = $this->generateMac(DeviceExtInfo::orderBy('mac_bt', 'desc')->value('mac_bt'), 1);

                DeviceExtInfo::where('order_id',$form->model()->order_id)->first();

                $form->text('imei_start', trans('order.imei_start'))->value($lastImei)->rules('required|max:15|min:15');
                $form->text('wifi_mac_start', trans('order.mac_wifi_start'))->value($lastMacWifi)->rules('required|regex:/^([A-Za-z0-9]{2}:){5}[A-Za-z0-9]{2}/');
                $form->text('bt_mac_start', trans('order.mac_bluetooth_start'))->value($lastBTWifi)->rules('required|regex:/^[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}/');


            })->edit($id));
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

            $grid->column('order_id', trans('order.order_id'))->sortable();
            $grid->column('order_number', trans('order.number'))->sortable();
            $grid->column('order_sum', trans('order.sum'))->sortable();


            $grid->column('order_has', trans('order.order_has'))->sortable()->display(function ()  {
                $count = DeviceExtInfo::where('order_id', $this->order_id)->count();
                return $count;
            });

            $grid->column('dev_model_id', trans('order.dev_model_id'))->sortable()->display(function ($dev_mode_id) {
                return DevModel::find($dev_mode_id)->dev_model_name;
            });
            $grid->column('order_time', trans('order.order_time'))->sortable();


                $grid->actions(function ($actions){
                    $count = DeviceExtInfo::where('order_id', $actions->getKey())->count();
                    if($count == $actions->row->order_sum) {

                        //已生产的 设备已经达到了总值
                        $actions->disableEdit();
                        $actions->disableDelete();
                        $actions->append('订单已完成');
                    }

                    if (!Admin::user()->can('delete-image')) {
                        $actions->disableDelete();
                    }
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
        return Admin::form(Order::class, function (Form $form) {


            $gw = gmstrftime('%g%W') ;

            $lastOrder = Order::where("order_number","like",$gw."%")->orderBy('order_number', 'desc')->first();
            $lastOrderNumber = $gw . '0001';

            if($lastOrder) {
                $lastOrderNumber = intval($lastOrder->order_number) + 1 ;
            }
            $form->model()->order_number = $lastOrderNumber ;
            $form->model()->status = 1;

            $form->display('order_number', trans('order.number'))->value($form->model()->order_number);


            $form->number('order_sum', trans('order.sum'))->rules('required');

            $form->date('order_time', trans('order.order_time'))->default(gmstrftime('%Y-%m-%d'));



            $form->select('sh_name_id', trans('order.sh_name_id'))->options('/api/allSHInfos')->load('customer_id', '/api/customer')->value($form->model()->sh_name_id);

            $form->select('customer_id', trans('order.customer_id'))->load('dev_model_id', '/api/dev_model');

            // 从api中获取数据
            $form->select('dev_model_id', trans('order.dev_model_id'))->load('package_id', '/api/packages', 'id', 'file');
            $form->select('package_id', trans('order.package_id'));
            //-> load('hardware_version','/api/model_hardware_versions')

            $form->select('soft_version', trans('order.soft_version'))->rules('required');

            $form->select('hardware_version', trans('order.hardware_version'))->rules('required');

            $form->select('inner_dev_model_id', trans('order.inner_dev_model_id'))->options([1 => 'M03U', 2 => 'M03C']);

            $script = <<<EOT
$(document).off('change', ".package_id");
$(document).on('change', ".package_id", function () {
    var soft_version = $(this).closest('.fields-group').find(".soft_version");
    var hardware_version = $(this).closest('.fields-group').find(".hardware_version");
    $.get("/api/packages_version?q="+$('.package_id').val(), function (data) {
    
    
        soft_version.find("option").remove();
        $(soft_version).select2({
            data: $.map(data, function (d) {
                d.id = d.soft;
                d.text = d.soft;
                return d;
            })
        }).trigger('change');
        hardware_version.find("option").remove();
        $(hardware_version).select2({
            data: $.map(data, function (d) {
                d.id = d.hardware;
                d.text = d.hardware;
                return d;
            })
        }).trigger('change');
    });
});

$(document).on('change',".inner_dev_model_id",function(){
    var inner_id = $('.inner_dev_model_id').val();
    var imei = generateIMEI(inner_id);  
    alert(imei);  
    
    
});

    //判断当前日期为当年第几周
var getYearWeek = function (a, b, c) {
    //date1是当前日期
    //date2是当年第一天
    //d是当前日期是今年第多少天
    //用d + 当前年的第一天的周差距的和在除以7就是本年第几周
    var date1 = new Date(a, parseInt(b) - 1, c), date2 = new Date(a, 0, 1),
        d = Math.round((date1.valueOf() - date2.valueOf()) / 86400000);
    return Math.ceil((d + ((date2.getDay() + 1) - 1)) / 7);
};

function generateIMEI(innerId){
    //生成imei
    var day = new Date();
    var year = day.getFullYear()-2000 ;
    var month = getYearWeek(day.getFullYear(), day.getMonth()+1 , day.getDate());

    return "8650"+innerId+"1"+year+""+month+"0001";
}
function formatIMEI(imei14) {
     var imeiChars = imei14.split("");
     
     
}


EOT;
            Admin::script($script);

            /// IMEI 由 15 位数字组成。 AAAAAA BBBB CCCC D
            /// AAAAAA 6位： TAG为设备型号 定义为 865xx1  xx 表示设备型号代码
            /// BBBB   4位： 生产日期      定义为 YYWW
            /// CCCC   4位： 设备流水号    从0001开始 +1 计数
            /// D      1位： 校验号        0 - 9

            $model_num = '01' ;

            $lastImei = generateIMEI($model_num);

            $lastDevice = Device::where('imei','like',"865".$model_num."1".$gw."%")->orderBy('imei','desc')->first();

            if($lastOrder){
                $temp = mb_substr($lastOrder->imei_start,0,14);
                $tempInt = intval($temp) + $lastOrder->order_sum ;
                $lastImei = formatImei(($tempInt + 1)."");
            }
            else if($lastDevice) {
                $temp = mb_substr($lastDevice->imei,0,14);
                $tempInt = intval($temp) + 1 ;
                $lastImei = formatImei($tempInt ."");
            }

            $lastMacWifi = $this->generateMac(DeviceExtInfo::orderBy('mac_wifi', 'desc')->value('mac_wifi'), 1);
            $lastBTWifi = $this->generateMac(DeviceExtInfo::orderBy('mac_bt', 'desc')->value('mac_bt'), 1);

            $form->text('imei_start', trans('order.imei_start'))->value($lastImei)->rules('required|max:15|min:15');
            $form->text('wifi_mac_start', trans('order.mac_wifi_start'))->value($lastMacWifi)->rules('required|regex:/^([A-Za-z0-9]{2}:){5}[A-Za-z0-9]{2}/');
            $form->text('bt_mac_start', trans('order.mac_bluetooth_start'))->value($lastBTWifi)->rules('required|regex:/^[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}:[A-Za-z0-9]{2}/');

            $form->saved(function (Form $form) {
                // 保存后回调

            });

        });
    }


    private function generateMac($start, $index = 0)
    {
        $nums = explode(":", $start);
        if ($nums || $nums->sum != 4) {
            $nums = [0x00, 0x00, 0x00, 0x00, 0x00, 0x00];
        }

        // 取出 每一位 并转换成 十进制
        $first = hexdec($nums[0]);
        $second = hexdec($nums[1]);
        $third = hexdec($nums[2]);
        $four = hexdec($nums[3]);
        $five = hexdec($nums[4]);
        $last = hexdec($nums[5]);

        // 大于 最大值 则取下一位、
        if (($last + $index) > 255) {
            $last = 255;

            // 大于 最大值 则取下一位、
            if (($third + $index) > 255) {
                $third = 255;

                // 大于 最大值 则取下一位、
                if (($second + $index) > 255) {
                    $second = 255;

                    // 大于 最大值 则取下一位、
                    if (($first + $index) > 255) {
                        $first = 255;
                    } else {
                        $first = $first + $index;
                    }
                } else {
                    $second = $second + $index;
                }
            } else {
                $third = $third + $index;
            }

        } else {
            $last = $last + $index;
        }


        return str_pad_dechex_0($first) . ':' . str_pad_dechex_0($second) . ':' . str_pad_dechex_0($third) . ':' . str_pad_dechex_0($four) . ':' . str_pad_dechex_0($five) . ':' . str_pad_dechex_0($last);

    }

}

function str_pad_dechex_0($input, $length = 2, $pad_type = STR_PAD_LEFT)
{
    return str_pad(dechex($input), $length, "0", $pad_type);
}




/// IMEI 由 15 位数字组成。 AAAAAA BBBB CCCC D
/// AAAAAA 6位： TAG为设备型号 定义为 865xx1  xx 表示设备型号代码
/// BBBB   4位： 生产日期      定义为 YYWW
/// CCCC   4位： 设备流水号    从0001开始 +1 计数
/// D      1位： 校验号        0 - 9
function generateIMEI($model_num)
{
    $a = '865' . str_pad($model_num, 2, '0', STR_PAD_LEFT) . '1';
    $b = gmstrftime('%g%W');
    $c = '0001';
    /*
    * IMEI校验码算法：
    * (1).将偶数位数字分别乘以2，分别计算个位数和十位数之和
    * (2).将奇数位数字相加，再加上上一步算得的值
    * (3).如果得出的数个位是0则校验位为0，否则为10减去个位数
    */
    $imei_14 = $a . $b . $c;
    return formatImei($imei_14);
}

function formatImei($imei_14){
    $array = str_split($imei_14);

    $d = 0;

    for ($i = 0; $i < count($array); $i++) {
        $a = intval($array[$i]);
        $i++;
        $temp = intval($array[$i]) * 2;
        $b = $temp < 10 ? $temp : $temp - 9;
        $d += $a + $b;
    }
    $d %= 10;
    $d = $d == 0 ? 0 : 10 - $d;

    return $imei_14 . $d;
}
