<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Guessbig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guess', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id' , false , true );	//发起用户
            $table->integer('cash' , false , true ) ;	//总价格
            $table->float('rate');	//赔率
            $table->text('seed');	//种子
            $table->integer('max_join' , false , true );	//最大参与人数
            $table->integer('has_join' , false , true );	//已经参与的人数
            $table->integer('occupy_cash' , false , true ) ;	//占用的狗粮等于已经下注的部分*赔率
            $table->integer('win_cash' , false , true ) ;	//赢的狗粮
            $table->integer('lose_cash' , false , true );	//输掉的狗粮
            $table->tinyInteger('status' , false , true )->default( 0 ); // 活动状态 0待开奖 1已开奖 2过期
			$table->timestamps ();
		} );
        
        //参与押大小的记录
        Schema::create('guess_join', function (Blueprint $table) {
        	$table->increments('id');
        	$table->integer('guess_id' , false , true );	//押大小编号
        	$table->integer('user_id' , false , true );	//参与者ID
        	$table->float('cash');		//投入了多少狗粮
        	$table->text('seed');		//押注大还是小
        	$table->tinyInteger('is_win' , false , true )->default( 0 );	//0未开奖 1用户win 2庄家win
        	$table->integer('win_cash' ,false , true )->default( 0 );	//赢了多少狗粮 如果是用户输则为0 
        	$table->tinyInteger('status' , false , true );	//退款状态  0未开奖   1已开奖未退 2已开奖已退
        	$table->timestamps ();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guess');
        Schema::dropIfExists('guess_join');
    }
}
