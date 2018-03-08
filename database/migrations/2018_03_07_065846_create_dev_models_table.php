<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dev_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50);// 型号名称
            $table->integer('type');//'型号的类型：非安卓、安卓'
            $table->string('customer_name',50); //客户名称
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
        Schema::dropIfExists('dev_models');
    }
}
