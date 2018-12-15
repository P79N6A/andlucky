<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use DB ;
use App\Models\Guess ;
use App\Models\GuessJoin;

class GuessCheck implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $id ;

	/**
	 * 取这个时间前的数据进行结算
	 * @param unknown $day
	 */
	public function __construct( $id ) {
		$this->id = $id ;		
	}
	
	public function handle() {
		date_default_timezone_set( 'Asia/Shanghai') ;
		$id = $this->id ;
		$guess = Guess::with('join')->find( $id );
		if( empty( $guess ) ) {
			\Log::info("guess {$id} not find") ;
			return true ;
		}
		$time = time() ;
		if( $guess->status != 0 ) {
			\Log::info("guess {$id} 不在开奖状态") ;
			return true ;
		}
		//如果大前时间大于结束时间  表示时间已经过了 需要结案
		if( $guess->end_time < $time ) {
			$this->_close( );
			return true ;
		}
		
		//如果 参与人数满了 则结案
		if( $guess->max_join == $guess->has_join ) {
			$this->_close( );
			return true ;
		}
		
		//钱花光了也要结案
		if( $guess->cash / $guess->rate - $guess->occupy_cash <= ceil( $guess->rate ) ) {
			$this->_close( );
			return true ;
		}
		
	}
	
	protected function _close() {
		$id = $this->id ;
		\DB::beginTransaction();
		try {
			$guess = Guess::find( $id );
			$row = Guess::where('id' , $this->id )->update(['status' => 1] );
			if( !$row ) {
				throw  new \Exception("更新状态失败");
			}
			\DB::commit();
			//分配铜板 如果有铜板则分配
			if( $guess->has_join == 0 ) {
				dispatch( new \App\Jobs\GuessReback( $guess ) ) ;
			} else {
				dispatch( new \App\Jobs\GuessReturn( $guess ) ) ;
				GuessJoin::where('guess_id' , $this->id )->orderBy('id' , 'asc' )->chunk( 100 , function( $joins ) {
					if( $joins->isNotEmpty() ) {
						foreach( $joins as $join ) {
							dispatch( new \App\Jobs\GuessResult( $join ) );
						}
					}
				});
			}
			
		}catch( \Exception $e ) {
        	\Log::info( $e->getMessage() );
        	\DB::rollBack();
        	$this->fail( $e );
		}
		
	}
}
