<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = "order_id" ;
    public $timestamps = false ;
    // 很多设备
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
