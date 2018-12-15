<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMicroblog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('microblog', function (Blueprint $table) {
            //
            $table->tinyInteger('auth_status' , false , true );	//审核状态
            $table->text('adv_cover' )->nullable();	//广告封面
            $table->text('adv_link')->nullable();	//广告链接
            $table->text('adv_pos')->nullable();		//广告位置
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('microblog', function (Blueprint $table) {
            //
        });
    }
}
