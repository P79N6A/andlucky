<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FocusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_focus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id' , false , true );	//用户id
            $table->integer('focus_user_id' , false , true );	//关注者的用户id
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
        Schema::dropIfExists('user_focus');
    }
}
