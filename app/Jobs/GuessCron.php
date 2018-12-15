<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Guess;
use App\Models\GuessJoin ;

class GuessCron implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function __construct() {
		
	}
	
	/**
	 * 检查时间到了还没有结束的功能
	 */
	public function handle() {
		Guess::where('status' , 0 )->where('end_time' , '<=' , time() )->orderBy('id' , 'asc' )->chunk( 100 , function( $guesses ){
			if( $guesses->isNotEmpty() ) {
				foreach( $guesses as $guess ) {
					\DB::beginTransaction();
					try {
						\Log::info( "ok" );
						$row = Guess::where('id' , $guess->id )->update([ 'status' => 1 ] );
						if( !$row ) {
							throw  new \Exception("更新状态失败");
						}
						\DB::commit();
						//分配铜板 如果有铜板则分配
						if( $guess->has_join == 0 ) {
							dispatch( new \App\Jobs\GuessReback( $guess ) ) ;
						} else {
							GuessJoin::where('guess_id' , $guess->id )->orderBy('id' , 'asc' )->chunk( 100 , function( $joins ) {
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
					}
				}
			}
		});
		
	}
}
