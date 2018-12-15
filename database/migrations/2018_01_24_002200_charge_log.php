<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChargeLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charge_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('out_trade_no');
            $table->integer('user_id' , false , true );
            $table->string('trade_no')->nullable()->default( '' );
            $table->decimal('charge')->default( 0 );	//充值铜板
            $table->string('status')->nullable()->default('wait');
            $table->integer('notice_time' , false , true )->nullable();
            $table->timestamp('deleted_at' , 0 )->nullable();
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
        Schema::dropIfExists('charge_log');
    }
}
