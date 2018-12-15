<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePraiseLogTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('praise_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true); // 用户ID
            $table->string('type', 20); // 记录类型
            $table->integer('target_id', false, true); // 目标对象
            $table->timestamps();
        });
        
        Schema::create('view_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true); // 用户ID
            $table->string('type', 20); // 记录类型
            $table->integer('target_id', false, true); // 目标对象
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
        Schema::dropIfExists('praise_logs');
        Schema::dropIfExists('view_logs');
    }
}
