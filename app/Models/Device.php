<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/*
+---------------------+--------------+------+-----+-------------------+-----------------------------+
| Field               | Type         | Null | Key | Default           | Extra                       |
+---------------------+--------------+------+-----+-------------------+-----------------------------+
| device_info_id      | bigint(20)   | NO   | PRI |                   | auto_increment              |
| dev_id              | varchar(100) | YES  |     |                   |                             |
| imei                | varchar(100) | NO   |     |                   |                             |
| dev_model_id        | varchar(100) | YES  |     |                   |                             |
| sh_name_id          | int(11)      | NO   |     |                   |                             |
| vehicle_num         | varchar(128) | YES  |     |                   |                             |
| softwareversion     | varchar(100) | YES  |     |                   |                             |
| hardwareversion     | varchar(100) | YES  |     |                   |                             |
| iccid               | varchar(100) | YES  |     |                   |                             |
| phone_num           | varchar(100) | YES  |     |                   |                             |
| customer_id         | int(11)      | YES  |     |                   |                             |
| no_boot             | int(2)       | NO   |     | 0                 |                             |
| mod_time            | timestamp    | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
| log_upload_flag     | int(11)      | NO   |     | 0                 |                             |
| log_upload_protocol | int(11)      | NO   |     | 0                 |                             |
| reboot_reason_code  | int(11)      | NO   |     | 0                 |                             |
| reboot_num          | int(11)      | NO   |     | 0                 |                             |
+---------------------+--------------+------+-----+-------------------+-----------------------------+
 */
class Device extends Model
{

    protected $table = "device_infos";

    protected $primaryKey = 'device_info_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'imei', 'softwareversion','hardwareversion','dev_model_id','sh_name_id','customer_id'
    ];

    public $timestamps = false;
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'device_info_id';
    }
}
