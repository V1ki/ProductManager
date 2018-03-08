<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sn', 'order_id', 'soft_version','hardware_version','dev_model_id','imei1','imei2','mac_wifi','mac_bluetooth'
    ];
    //设备类型
    public function model()
    {
        return $this->belongsTo(DevModel::class);
    }
    // 设备订单
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
