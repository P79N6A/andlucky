<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImFriendsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('im_friends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true); // 用户ID
            $table->integer('friend_user_id', false, true); // 好友的id
            $table->string('alias_name')->nullable(); // 好友的别名
            $table->tinyInteger('status', false, true)->default(0); // 好友状态 0为申请中 1为通过 2黑名单
            $table->tinyInteger('is_one_way_friend', false, true)->default(1); // 是否为单向好友
            $table->timestamps();
        });
        
        // 黑名单
        Schema::create('im_blacklist', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true); // 用户ID
            $table->integer('friend_user_id', false, true); // 对方ID的id
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
        Schema::dropIfExists('im_friends');
        Schema::dropIfExists('im_blacklist');
    }
}
