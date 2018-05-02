<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_record', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('phone',11)->comment('手机号');
            $table->string('code',10)->comment('验证码');
            $table->string('type',50)->comment('短信类型');
            $table->tinyInteger('available')->default(0)->comment('是否可用');
            $table->integer('expired_at')->default(0)->comment('过期时间');
            $table->integer('send_time')->default(0)->comment('发送时间');
            $table->timestamps();

            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_record');
    }
}
