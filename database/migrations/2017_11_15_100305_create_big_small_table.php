<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBigSmallTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('big_small', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true); // 用户ID
            $table->integer('invite_user_id', false, true); // 被邀请的用户ID
            $table->integer('cash_deposit', false, true); // 本次比大小保证金大小
            $table->tinyInteger('deposit_status', false, true)->default(0); // 1发起者支付 2双方支付
            $table->tinyInteger('status', false, true)->default(0); // 应邀请状态 0未接受 1已接受并支付 2已退款 3已关闭 4已完成
            $table->tinyInteger('user_num', false, true)->default(0); // 邀请者的点数大小
            $table->tinyInteger('inviter_num', false, true)->default(0); // 被邀请者的点数大小
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
        Schema::dropIfExists('big_small');
    }
}
