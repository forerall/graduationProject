<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name',20)->default('')->comment('姓名');
            $table->string('nickname',20)->default('')->comment('昵称');
            $table->string('avatar',255)->default('')->comment('头像');
            $table->string('phone',11)->unique()->comment('手机号');
            $table->string('email',255)->default('')->comment('邮箱');
            $table->string('password')->comment('密码');
            $table->tinyInteger('source')->default(1)->comment('注册来源 1网站 2微博,3QQ,4微信');
            $table->string('qq',128)->default('')->comment('qq openid');
            $table->string('weibo',128)->default('')->comment('微博 openid');
            $table->string('wechat',128)->default('')->comment('微信 openid');
            $table->integer('balance')->default(0)->comment('账户余额');
            $table->rememberToken()->comment('Token');
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
        Schema::dropIfExists('users');
    }
}
