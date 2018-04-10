<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            /*
  DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(30) DEFAULT NULL COMMENT '订单号',
  `order_sum` int(11) DEFAULT NULL COMMENT '订单总数',
  `order_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '订单时间',
  `sh_name_id` int(11) NOT NULL DEFAULT '0' COMMENT '商户id',
  `customer_id` int(11) NOT NULL COMMENT '客户id',
  `dev_model_id` int(11) NOT NULL COMMENT '设备型号id',
  `package_id` int(11) NOT NULL COMMENT '刷机包 id',
  `imei_start` varchar(20) CHARACTER SET latin1 NOT NULL COMMENT 'imei 起始',
  `wifi_mac_start` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'WiFi mac 地址起始',
  `bt_mac_start` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT '蓝牙 mac 地址',
  `status` int(5) DEFAULT NULL COMMENT '订单状态',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            */

            $table->increments('order_id');
            // 订单号
            $table->string('order_number', 50)->unique();
            // 订单数量
            $table->integer('order_sum');

            // 订单时间
            $table->timestamp('order_time');
            // 商户 id
            $table->integer('sh_name_id');
            // 客户 id
            $table->integer('customer_id');
            // 设备型号
            $table->integer('dev_model_id');
            // 刷机包 id
            $table->integer('package_id');
            // 软件版本
            $table->string('soft_version', 30);
            // 硬件版本
            $table->string('hardware_version', 30);
            // imei 起始
            $table->string('imei_start', 20);
            // wifi mac 地址 起始
            $table->string('wifi_mac_start', 30);
            // 蓝牙 mac 地址 起始
            $table->string('bt_mac_start', 30);

            // 订单 状态 入库、发货、
            $table->integer('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
