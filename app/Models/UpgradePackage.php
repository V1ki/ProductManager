<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// `dev_model_id`,`dev_model_name`,`dev_model_type`,`customer_id`,`sh_name_id`
class UpgradePackage extends Model
{
    protected $table = "upgrade_package";

    protected $primaryKey = 'package_id' ;

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
