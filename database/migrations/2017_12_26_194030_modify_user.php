<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUser extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->integer('total_big_small', false, true); // 总比大小次数 成功接受的算
            $table->integer('lose_big_small', false, true); // 失败的次数
            $table->integer('not_pay_big_small', false, true); // 未支付的次数
            $table->integer('lose_big_small_cash', false, true); // 总输掉的狗粮
            $table->integer('not_pay_big_small_cash', false, true); // 总输掉未支付的狗粮
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
