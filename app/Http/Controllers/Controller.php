<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceExtInfo;
use App\Models\DevModel;
use App\Models\Order;
use App\Models\UpgradePackage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\SHAdmin ;
use App\Models\Customers ;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // 所有的商户信息
    public function allSHInfos(){
        return SHAdmin::get([DB::raw('sh_name_id as id'),DB::raw('sh_name as text')]);
    }

    //商户下的客户信息
    public function customer(Request $request){
        // `customer_id`,`customer_name`
        $id = $request->get('q');

        return Customers::where('sh_name_id',$id)->get([DB::raw('customer_id as id'),DB::raw('customer_name as text')]);
    }
    // 设备型号
    public function dev_model(Request $request){
        // `customer_id`,`customer_name`
        $id = $request->get('q');
        return DevModel::where('customer_id',$id)->get([DB::raw('dev_model_id as id'),DB::raw('dev_model_name as text')]);
    }
    // 获取设备型号的 软件版本号
    public function model_versions(Request $request) {
        $id = $request->get('q');
        return UpgradePackage::where('dev_model_id',$id)->get([DB::raw('package_id as id'),DB::raw('package_version as soft'),DB::raw('package_hwver as hardware')]) ->unique("soft");
    }

    public function packages_version(Request $request){
        $id = $request->get('q');
        return UpgradePackage::where('package_id',$id)->get([DB::raw('package_version as soft'),DB::raw('package_hwver as hardware')]);

    }

    public function packages(Request $request) {
        $id = $request->get('q');
        return UpgradePackage::where('dev_model_id',$id)->get([DB::raw('package_id as id'),DB::raw('package_file as file'),DB::raw('package_version as soft'),DB::raw('package_hwver as hardware')]);

    }

    // 获取设备型号的 硬件版本号
    public function model_hardware_versions(Request $request) {
        $id = $request->get('q');
        return UpgradePackage::where('dev_model_id',$id)->get([DB::raw('package_id as id'),DB::raw('package_hwver as text')]);
    }


    public function getDeviceInfo(Request $request) {
        $sn = $request->get('sn');
        return Device::where('sn',$sn);
    }

    public function createDevice(Request $request) {
        // 订单号
        $orderNo = $request->get('order');
        $order = Order::where('order_number',$orderNo)->first();
        if( $order == null) {
            return  ['code' => -1 , 'msg' => '请核对订单号后再打印条码!!!'];
        }


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

        $count = DeviceExtInfo::where('order_id', $order->order_id)->count();
        if ($count == $order->order_sum) {
            // 已经创建了足够的设备.
            return ['code' => -1 , 'msg' => '入库设备已经达到订单数量!!!'];
        }


        $imei = mb_substr($order->imei_start,0,14);


        $imei_14 = intval($imei) + $count ;
        $imei = $this->genrateIMEI($imei_14) ;




        // 通过订单号 生成
        $device = Device::create([
            'softwareversion' => $order->soft_version,
            'hardwareversion' => $order->hardware_version,
            'dev_model_id' => $order->dev_model_id,
            'sh_name_id' => $order->sh_name_id,
            'customer_id' => $order->customer_id,
            'imei' => $imei
        ]);

/*

 $table->increments('device_ext_info_id')->comment('设备额外信息id');
                $table->integer('device_info_id')->unique()->comment('设备id');
                $table->integer('order_id')->comment('订单id');
                $table->string('mac_wifi',30)->comment('设备wifi 的mac地址');
                $table->string('mac_bt',30)->comment('设备 蓝牙 的mac地址');
 * */
        $extInfo = DeviceExtInfo::create([
            'order_id' => $order->order_id,
            'device_info_id' => $device->device_info_id ,
            'mac_wifi' => $order->wifi_mac_start,
            'mac_bt' => $order->bt_mac_start
        ]);

        return ['code' => 0 ,'device'=>$device ,'order_left' => ( $order->order_sum - $count - 1)  ,'ext_info' => $extInfo] ;

    }


    private function generateMac($start, $index = 0)
    {
        $nums = explode(":", $start);
        if ($nums || $nums->sum != 6) {
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


        return str_pad_dechex_0($first) . ':' . str_pad_dechex_0($second) . ':' . str_pad_dechex_0($third) . ':'. str_pad_dechex_0($four) . ':'. str_pad_dechex_0($five) . ':' . str_pad_dechex_0($last);

    }


    private function genrateIMEI($imei_14){
        $array = str_split($imei_14);

        $d = 0 ;

        for ($i = 0 ; $i < count($array); $i ++ ){
            $value_i = $array[$i] ;
            $i++ ;
            $temp = $value_i * 2 ;
            $temp = $temp < 10 ? $temp : $temp-9;
            $d += $value_i+$temp;
        }
        $d %= 10 ;
        $d = $d==0 ? 0:10-$d;

        return $imei_14.$d ;
    }
}

function str_pad_dechex_0($input, $length = 2, $pad_type = STR_PAD_LEFT)
{
    return str_pad(dechex($input), $length, "0", $pad_type);
}
