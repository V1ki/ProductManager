<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceExtInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('device_ext_info', function (Blueprint $table) {
                $table->increments('device_ext_info_id')->comment('设备额外信息id');
                $table->integer('device_info_id')->unique()->comment('设备id');
                $table->integer('order_id')->comment('订单id');
                $table->string('mac_wifi',30)->comment('设备wifi 的mac地址');
                $table->string('mac_bt',30)->comment('设备 蓝牙 的mac地址');
            });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_ext_info');
    }
}
