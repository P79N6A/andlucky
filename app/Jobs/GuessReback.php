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

class GuessReback implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $guess ;

	
	public function __construct( $guess ) {
		$this->guess = $guess ;
	}
	
	
	public function handle() {
		DB::beginTransaction();
		try {
			$row = Guess::where('id' , $this->guess->id )->where('status' , 1 )->update([
					'status' => 2
			]);
			if (! $row) {
				throw new \Exception("开台状态更新错误");
			}
			$row = \DB::table('users')->where('id', $this->guess->user_id)
			->increment('cash_reward', $this->guess->cash );
			if (! $row) {
				throw new \Exception('余额更新出错');
			}
			$cash = \App\Models\UserCash::create([
					'user_id' => $this->guess->user_id,
					'event' => 'guessfailed',	//开台失败
					'target_id' => $this->guess->id,
					'target_desc' => '押大小人数不足过期退还',
					'act' => 'in',
					'cash' => $this->guess->cash ,
					'extra_cash' => 0
			]);
			if (empty($cash)) {
				throw new \Exception('铜板流水写入错误');
			}
			\DB::commit();
		} catch( \Exception $e ) {
			\Log::info( $e->getTraceAsString() );
			\DB::rollBack();
			$this->fail( $e ) ;
		}
	}
}
