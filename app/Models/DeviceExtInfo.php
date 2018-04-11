<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceExtInfo extends Model
{
    protected $table = 'device_ext_info';

    protected $primaryKey = 'device_ext_info_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'device_info_id',
        'mac_wifi',
        'mac_bt'
    ];

}
