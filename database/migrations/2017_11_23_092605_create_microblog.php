<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMicroblog extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('microblog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->default(0); // 发表者
            $table->integer('cate_id', false, true)->default(1); // 分类
            $table->string('content')->nullable();
            $table->text('extra'); // 额外的附件
            $table->integer('views', false, true)->default(0); // 阅读数
            $table->integer('prase', false, true)->default(0); // 赞数
            $table->integer('comment_count', false, true)->default(0); // 评论数
            $table->timestamps();
        });
        
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('target_id', false, true); // 目标ID
            $table->string('target_type', 20); // 目标类型
            $table->integer('user_id', false, true); // 用户id
            $table->text('content'); // 评论内容
            $table->tinyInteger('status', false, true)->default(0); // 状态 0正常显示 1删除
            $table->integer('prase', false, true)->default(0); // 赞数
            $table->integer('comment_count', false, true)->default(0); // 评论数
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
        Schema::dropIfExists('microblog');
        Schema::dropIfExists('comments');
    }
}
