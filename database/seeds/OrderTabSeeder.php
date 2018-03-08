<?php

use Illuminate\Database\Seeder;

class OrderTabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker\Factory::create();

        for($i = 0; $i < 1000; $i++) {
            /**
            // 订单号
            $table->string('number', 50)->unique();
            // 软件版本号
            $table->string('soft_version', 50);
            // 硬件版本号
            $table->string('hardware_version', 50);
            // framework版本号
            $table->string('framework_version', 50);
            // 订单数量
            $table->integer('sum');
            // 设备型号
            $table->string('dev_model_id', 50);
            // imei 起始
            $table->string('imei_start', 50);
            // 序列号 起始
            $table->string('sn_start', 50);
            // 订单下达时间
            $table->timestamp('order_time');
            */
            App\Order::create([
                'number' => $faker->uuid,
                'soft_version' => $faker->regexify('[A-Z0-9._%+-]+'),
                'hardware_version' => $faker->regexify('[A-Z0-9._%+-]+'),
                'framework_version' => $faker->regexify('[A-Z0-9._%+-]+'),
                'sum' => $faker->randomDigitNotNull,
                'dev_model_id' => $faker->uuid,
                'imei_start' => $faker->regexify('[0-9]{15}'),
                'sn_start' => $faker->regexify('[0-9]{15}'),
                'order_time' => $faker->dateTime,
            ]);
        }
    }
}
