<?php


namespace App\Http\Controllers;

use App\Device;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class DeviceController extends BaseController
{
    /**
     * last wifi mac address
     */
    public function lastWifiMac(){
        return Device::orderBy('mac_wifi','desc')->value('mac_wifi');
    }

    /**
     * last bluetooth mac address
     */
    public function lastBTMac(){}



}
