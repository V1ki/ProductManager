<?php

namespace App\Http\Controllers;

use App\Device;
use App\DevModel;
use App\Order;
use App\UpgradePackage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\SHAdmin ;
use App\Customers ;
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
        $order = Order::where('number',$orderNo)->first();
        if( $order == null) {
            return "23";
        }


        $device = Device::create([
            'sn' => 'sn_test',
            'order_id' => $orderNo,
            'soft_version' => $order->soft_version,
            'hardware_version' => $order->hardware_version,
            'dev_model_id' => $order->dev_model_id,
            'imei1' => str_pad_dechex_0($order->imei_start + 0,15) ,
            //'imei2' => $imei_start,
            'mac_wifi' => $this->generateMac($order->mac_wifi_start, 1),
            'mac_bluetooth' => $this->generateMac($order->mac_bluetooth_start,1),
        ]);


        // 通过订单号 生成
        return $device ;

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


        return str_pad_dechex_0($first) . ':' . str_pad_dechex_0($second) . ':' . str_pad_dechex_0($third) . ':'. str_pad_dechex_0($four) . ':'. str_pad_dechex_0($five) . ':' . str_pad_dechex_0($last);

    }



}

function str_pad_dechex_0($input, $length = 2, $pad_type = STR_PAD_LEFT)
{
    return str_pad(dechex($input), $length, "0", $pad_type);
}
