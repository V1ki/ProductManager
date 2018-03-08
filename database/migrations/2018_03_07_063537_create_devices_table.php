<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');

            // 设备序列号
            $table->string('sn',50)->unique();

            // 该设备所属订单id
            $table->string('order_id', 50);

            // 软件版本号
            $table->string('soft_version', 50);
            // 硬件版本号
            $table->string('hardware_version', 50);
            // 设备型号
            $table->string('dev_model_id', 50);
            // imei1
            $table->string('imei1', 50);
            // imei2
            $table->string('imei2', 50)->nullable();
            // wifi mac
            $table->string('mac_wifi', 50);
            // 蓝牙 mac 地址 起始
            $table->string('mac_bluetooth', 50);
            // sim 卡 id
            $table->string('iccid', 50)->nullable();

            // 配置文件版本号
            $table->string('config_version', 50)->nullable();
//            // pre loader 版本号
//            $table->string('preloader_version', 50);
//            // boot loader 版本号
//            $table->string('bootloader_version', 50);
            // kernel 版本号
            $table->string('kernel_version', 50)->nullable();
            // recovery 版本号
            $table->string('recovery_version', 50)->nullable();
            // rom 版本号
            $table->string('rom_version', 50)->nullable();
            // sdk 版本号
            $table->string('sdk_version', 50)->nullable();
            // 基带 版本号
            $table->string('bb_version', 50)->nullable();
            // mcu 版本号
            $table->string('mcu_version', 50)->nullable();

            // 车牌号
            $table->string('vehicle_num', 50)->nullable();
            // 手机号
            $table->string('phone', 50)->nullable();

            // 设备状态
            $table->integer('status')->default(0);

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
        Schema::dropIfExists('devices');
    }
}
