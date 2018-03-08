<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevModel extends Model
{
    // 很多设备
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
