<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImMessageLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('im_message_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id', false, true);
            $table->integer('receiver_id', false, true);
            $table->text('body'); // 聊天内容
            $table->string('send_time');
            $table->string('msg_id');
            $table->string('type', 50);
            $table->text('ext');
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
        Schema::dropIfExists('im_message_logs');
    }
}
