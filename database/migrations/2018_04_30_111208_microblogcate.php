<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Microblogcate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('microblog_cate', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');	//标题
            $table->integer('sort' , false , true ) ;	//排序
            $table->text('keyword')->nullable();	//关键字
            $table->text('description')->nullable();	//描述
            $table->text('cover' )->nullable();	//封面图
            $table->tinyInteger('display' , false , true )->default( 1 ) ;	//是否显示
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
        Schema::dropIfExists('microblog_cate');
    }
}
