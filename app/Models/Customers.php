<?php
/**
 * Created by PhpStorm.
 * User: Vk
 * Date: 2018/3/9
 * Time: 下午3:00
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
//`customer_id`,`customer_name`,`sh_name_id`
class Customers extends Model
{
/*
+----------------+--------------+------+-----+---------+----------------+
| Field          | Type         | Null | Key | Default | Extra          |
+----------------+--------------+------+-----+---------+----------------+
| dev_model_id   | int(11)      | NO   | PRI |         | auto_increment |
| dev_model_name | varchar(100) | NO   |     |         |                |
| dev_model_type | int(11)      | NO   |     |         |                |
| customer_id    | int(11)      | NO   |     |         |                |
| sh_name_id     | int(11)      | NO   |     |         |                |
+----------------+--------------+------+-----+---------+----------------+
*/
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

}