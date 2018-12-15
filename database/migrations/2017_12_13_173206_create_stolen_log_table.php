<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStolenLogTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stolen_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true); // 发起的用户
            $table->integer('from_user_id', false, true); // 从哪个用户那里偷的
            $table->integer('cash', false, true); // 偷取了多少
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
        Schema::dropIfExists('stolen_logs');
    }
}
