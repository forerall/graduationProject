<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageStatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_statistic', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('page_id',50)->comment('页面id');
            $table->integer('user_id')->comment('用户id');
            $table->string('ip_addr',20)->comment('ip地址');
            $table->integer('day')->comment('日期天，如20170102');
            $table->tinyInteger('hour')->comment('小时0-23');
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
        Schema::dropIfExists('page_statistic');
    }
}
