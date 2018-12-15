<?php
/**
 * 开台失败
 */
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB ;
use App\Models\Guess ;

class GuessReturn implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $guess ;

	
	public function __construct( $guess ) {
		$this->guess = $guess ;
	}
	
	
	public function handle() {
		DB::beginTransaction();
		try {
			$return = round( $this->guess->cash , 3 ) - round( $this->guess->occupy_cash * $this->guess->rate , 3 ) ;
			if( $return > 0 ) {
				$row = \DB::table('users')->where('id', $this->guess->user_id)
				->increment('cash_reward', $return );
				if (! $row) {
					throw new \Exception('余额更新出错');
				}
				$cash = \App\Models\UserCash::create([
						'user_id' => $this->guess->user_id,
						'event' => 'guessnotuse',	//开台失败
						'target_id' => $this->guess->id,
						'target_desc' => '押大小未使用铜板退还',
						'act' => 'in',
						'cash' => $return ,
						'extra_cash' => 0
				]);
				if (empty($cash)) {
					throw new \Exception('铜板流水写入错误');
				}
				\DB::commit();
			}
		} catch( \Exception $e ) {
			\Log::info( $e->getTraceAsString() );
			\DB::rollBack();
			$this->fail( $e ) ;
		}
	}
}
