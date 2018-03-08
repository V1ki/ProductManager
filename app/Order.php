<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // 很多设备
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
