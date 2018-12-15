<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArticleGuess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('microblog' , function( Blueprint $table ) {
    		$table->tinyInteger('is_hot' ,  false , true ); //是否热点
    		$table->tinyInteger('allow_auth' , false , true );	//是否报审
    	});
    	
        Schema::table('guess', function (Blueprint $table) {
            //
            $table->integer('end_time' , false , true );	//结束时间
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
    		$table->dropColumn(['is_hot' , 'allow_auth']) ;
    	});
        Schema::table('guess', function (Blueprint $table) {
            //
            $table->dropColumn(['end_time']) ;
        });
    }
}
