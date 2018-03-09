<?php

namespace App\Http\Controllers;

use App\DevModel;
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
        return UpgradePackage::where('dev_model_id',$id)->get([DB::raw('package_id as id'),DB::raw('package_version as soft'),DB::raw('package_hwver as hardware')]);
    }
    // 获取设备型号的 硬件版本号
    public function model_hardware_versions(Request $request) {
        $id = $request->get('q');
        return UpgradePackage::where('dev_model_id',$id)->get([DB::raw('package_id as id'),DB::raw('package_hwver as text')]);
    }
}
