<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_config', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('app_id',255)->default('')->comment('');
            $table->string('app_secret',255)->default('')->comment('');
            $table->string('message_token',255)->default('')->comment('推送消息验证token');
            $table->string('encoding_aes_key',255)->default('')->comment('消息加密key');
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
        Schema::dropIfExists('wx_config');
    }
}
