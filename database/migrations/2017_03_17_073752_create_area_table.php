<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('name',50)->comment('地区名');
            $table->string('full_name',255)->comment('地区全名');
            $table->string('parent_code',6)->comment('上级省市区编码');
            $table->string('code',6)->comment('省市区编码');
            $table->string('code1',2)->comment('省编码');
            $table->string('code2',2)->comment('市编码');
            $table->string('code3',2)->comment('区编码');
            $table->tinyInteger('level')->comment('级别，省1，市2，区3');

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
        Schema::dropIfExists('area');
    }
}
