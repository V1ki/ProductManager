<?php
/**
 * Created by PhpStorm.
 * User: Vk
 * Date: 2018/3/9
 * Time: 下午3:00
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
//`sh_name_id`,`sh_name`,`sh_user_name`,`sh_user_password`,`cs_password`,`check_san_pass`,`oss_acc_keyid`,`oss_acc_keysecret`,`oss_acc_url`,`oss_acc_bucket`,`last_mod_pass_time`,`sh_name_level`,`sh_name_lastlogintime`
class SHAdmin extends Model
{

    protected $table = 'admin_sh_name' ;


}