<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('name')->comment('标题');
            $table->string('type')->comment('类型');
            $table->tinyInteger('state')->comment('状态 1正常，2禁用');
            $table->string('images',255)->default('')->comment('封面图片');
            $table->text('content')->comment('内容');
            $table->integer('views')->comment('浏览数');
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
        Schema::dropIfExists('article');
    }
}
