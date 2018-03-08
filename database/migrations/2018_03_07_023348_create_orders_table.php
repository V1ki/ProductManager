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
            $table->increments('id');
            // 订单号
            $table->string('number', 50)->unique();
            // 软件版本号
            $table->string('soft_version', 50);
            // 硬件版本号
            $table->string('hardware_version', 50);
            // 订单数量
            $table->integer('sum');
            // 设备型号
            $table->string('dev_model_id', 50);
            // imei 起始
            $table->string('imei_start', 50);
            // wifi mac 地址 起始
            $table->string('mac_wifi_start', 50);
            // 蓝牙 mac 地址 起始
            $table->string('mac_bluetooth_start', 50);
            // 订单下达时间
            $table->timestamp('order_time');
            // 订单 状态 入库、发货、
            $table->integer('status');




            $table->timestamps();
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
