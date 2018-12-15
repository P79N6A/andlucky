<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Stolen as StolenModel;
use App\User;
use DB ;
use App\Models\SmallBig;

class Gameover implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $smallBig;

    protected $fromUser;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( ) {
    }

    public function handle()
    {
        // TODO 这个是比大小无人参与 退还发起着钱
        SmallBig::where('status' , 0 )->where('created_at' , '<=' , date('Y-m-d H:i:s' , time() - 86400 ) )->chunk( 100 , function( $records ){
        	if( $records->isNotEmpty() ) {
	        	foreach( $records as $rec ) {
	        		DB::beginTransaction();
	        		try {
	        			$row = SmallBig::where('id' , $rec->id )->where('status' , 0 )->update([
	        					'status' => 6
	        			]);
	        			if (! $row) {
	        				throw new \Exception("战斗状态更新错误");
	        			}
	        			$row = \DB::table('users')->where('id', $rec->user_id)
	        			->increment('cash_reward', $rec->cash_deposit);
	        			if (! $row) {
	        				throw new \Exception('余额更新出错');
	        			}
	        			$cash = \App\Models\UserCash::create([
	        					'user_id' => $rec->user_id,
	        					'event' => 'bigsmallrefund',
	        					'target_id' => $rec->id,
	        					'target_desc' => '比大小取消战斗退还铜板',
	        					'act' => 'in',
	        					'cash' => $rec->cash_deposit,
	        					'extra_cash' => 0
	        			]);
	        			if (empty($cash)) {
	        				throw new \Exception('铜板流水写入错误');
	        			}
	        			\DB::commit();
	        		} catch( \Exception $e ) {
	        			\Log::info( $e->getTraceAsString() );
	        			\DB::rollBack();
	        			$this->fail( $e );
	        		}
	        	}
        	}
        });
    }
}