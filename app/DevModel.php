<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevModel extends Model
{
    protected $table = "device_models";

    protected $primaryKey = 'dev_model_id' ;

    // 商户
    public function sh_admin()
    {
        return $this->belongsTo(SHAdmin::class);
    }

    // 客户
    public function customer(){
        return $this->belongsTo(Customers::class);
    }


}
