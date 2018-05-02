<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBalanceLogTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_balance_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->tinyInteger('type')->comment('类型 1充值，2提现，3支付 , 4退款，5订单收入');
            $table->tinyInteger('state')->comment('状态1未确认，2已确认，3已取消');
            $table->integer('money')->comment('金额（单位分）');
            $table->integer('balance')->comment('当前余额');
            $table->string('detail')->default('')->comment('详情');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_balance_log');
    }
}
