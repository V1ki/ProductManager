<?php
/**
 * Created by PhpStorm.
 * User: Vk
 * Date: 2018/3/9
 * Time: 下午3:00
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
//`customer_id`,`customer_name`,`sh_name_id`
class Customers extends Model
{

    protected $primaryKey = 'customer_id';

}