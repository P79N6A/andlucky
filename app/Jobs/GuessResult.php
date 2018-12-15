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

class GuessResult implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $id ;
	protected $join ;
	protected $guess ;

	/**
	 * 取这个时间前的数据进行结算
	 * @param unknown $day
	 */
	public function __construct( $join ) {
		$this->id = $join->guess_id ;	
		$this->join = $join ;
	}

	/**
	 * 计算输赢  并分头润
	 */
	public function handle() {
		$guess = Guess::find( $this->id );
		if( empty( $guess ) ) {
			\Log::info( "编号为{$this->id}的押大小活动不存在" );
			throw new \Exception("编号为{$this->id}的押大小活动不存在");
		}
		$this->guess = $guess ;
		if( $guess->seed == $this->join->seed) {
			//参与者赢了
			$this->joinerWin();
		} else {
			//庄家赢了
			$this->bankerWin();
		}
	}
	
	/**
	 * 参与者赢
	 */
	protected function joinerWin() {
		\DB::beginTransaction();
		//获胜的奖励
		$winCash = $this->join->cash * $this->guess->rate ;
		//退还的奖励
		$cash = $this->join->cash ;
		try {
			//抽取佣金给介绍人
			$user = User::findOrFail( $this->join->user_id );
			//获取上级
			if( $user->invite_by ) {
				$parent = User::where('invite_code' , $user->invite_by)->first();
				if( $parent ) {
					//返佣比
					$rerundRate = config('REFUND_RATE' , 1 );
					$refund = sprintf("%.3f", $winCash * $rerundRate / 100 );
					$cash = $cash - $refund ;
					//写流水  和  写分润记录
					$row = \DB::table('users')->where('id', $parent->id )
					//->where('cash_reward', $user->cash_reward)
					->increment('cash_reward', $refund );
					if (!$row) {
						throw new \Exception('分润者铜板更新出错');
					}
					$cashLog = \App\Models\UserCash::create([
							'user_id' => $parent->id ,
							'event' => 'refund',
							'target_id' => $this->join->id ,
							'target_desc' => '押大小分润',
							'act' => 'in',
							'cash' => $refund ,
							'extra_cash' => 0
					]);
					if (empty($cashLog)) {
						throw new \Exception('分润者铜板流水写入错误');
					}
				}
			}
		
			//平台抽取获胜者的佣金
			$platRate = config('BIGSMALL_PLAT_RATE' , 0 );
			if( $platRate > 0 ) {
				//TODO
			}
		
		
			$row = \DB::table('users')->where('id', $this->join->user_id )
			//->where('cash_reward', $user->cash_reward)
			->increment('cash_reward', $cash );
			if (!$row) {
				throw new \Exception('铜板更新出错');
			}
			$cashLog = \App\Models\UserCash::create([
					'user_id' => $this->join->user_id ,
					'event' => 'guessrefund',
					'target_id' => $this->join->id ,
					'target_desc' => '押大小获胜退还铜板',
					'act' => 'in',
					'cash' => $cash ,
					'extra_cash' => 0
			]);
			if (empty($cashLog)) {
				throw new \Exception('铜板流水写入错误');
			}
			
			//更新记录
			$row = GuessJoin::where('id' , $this->join->id )->update([
					'status' => 1 , //赢了未退
					'is_win' => 1 ,	//赢了
					'win_cash' => $winCash 
			]);
			if(  !$row ) {
				throw new \Exception("更新押大小记录状态出错") ;
			}
			
			//更新失败记录
			$row = Guess::where('id' , $this->guess->id )->update([
					'lose_cash' => \DB::raw( 'lose_cash + ' . $winCash )
			]);
			if(  !$row ) {
				throw new \Exception("更新押大小记录状态出错") ;
			}
			
			//更新发起者的失败次数
			$loserId = $this->guess->user_id ;
			$row = \DB::table('users')->where('id', $loserId )
			->increment('lose_big_small', 1 );
			if (!$row) {
				throw new \Exception('更新发起者失利次数出错');
			}
			$row = \DB::table('users')->where('id', $loserId )
			->increment('lose_big_small_cash', $winCash );
			if (!$row) {
				throw new \Exception('更新接受者失败铜板次数出错');
			}
			\DB::commit();
			return true ;
		} catch( \Exception $e ) {
			\Log::info( $e->getTraceAsString() );
			\DB::rollback();
			return false ;
		}
	}
	
	
	/**
	 * 庄家赢
	 */
	protected function bankerWin() {
		\DB::beginTransaction();
		//获胜的奖励
		$winCash = $this->join->cash ;
		//退还的奖励
		$cash = $this->join->cash * $this->guess->rate ;
		try {
			//抽取佣金给介绍人
			$user = User::findOrFail( $this->guess->user_id );
			//获取上级
			if( $user->invite_by ) {
				$parent = User::where('invite_code' , $user->invite_by)->first();
				if( $parent ) {
					//返佣比
					$rerundRate = config('REFUND_RATE' , 1 );
					$refund = sprintf("%.3f", $winCash * $rerundRate / 100 );
					$cash = $cash - $refund ;
					//写流水  和  写分润记录
					$row = \DB::table('users')->where('id', $parent->id )
					//->where('cash_reward', $user->cash_reward)
					->increment('cash_reward', $refund );
					if (!$row) {
						throw new \Exception('分润者铜板更新出错');
					}
					$cashLog = \App\Models\UserCash::create([
							'user_id' => $parent->id ,
							'event' => 'refund',
							'target_id' => $this->join->id ,
							'target_desc' => '押大小分润',
							'act' => 'in',
							'cash' => $refund ,
							'extra_cash' => 0
					]);
					if (empty($cashLog)) {
						throw new \Exception('分润者铜板流水写入错误');
					}
				}
			}
		
			//平台抽取获胜者的佣金
			$platRate = config('BIGSMALL_PLAT_RATE' , 0 );
			if( $platRate > 0 ) {
				//TODO
			}
		
		
			$row = \DB::table('users')->where('id', $this->guess->user_id )
			//->where('cash_reward', $user->cash_reward)
			->increment('cash_reward', $cash );
			if (!$row) {
				throw new \Exception('铜板更新出错');
			}
			$cashLog = \App\Models\UserCash::create([
					'user_id' => $this->guess->user_id ,
					'event' => 'guessrefund',
					'target_id' => $this->join->id ,
					'target_desc' => '押大小获胜退还部分铜板',
					'act' => 'in',
					'cash' => $cash ,
					'extra_cash' => 0
			]);
			if (empty($cashLog)) {
				throw new \Exception('铜板流水写入错误');
			}
				
			//更新记录
			$row = GuessJoin::where('id' , $this->join->id )->update([
					'status' => 1 , //赢了未退
					'is_win' => 2 ,	//庄家赢
					'win_cash' => $winCash
			]);
			if(  !$row ) {
				throw new \Exception("更新押大小记录状态出错") ;
			}
				
			//更新失败记录
			$row = Guess::where('id' , $this->guess->id )->update([
					'win_cash' => \DB::raw( 'win_cash + ' . $winCash )
			]);
			if(  !$row ) {
				throw new \Exception("更新押大小记录状态出错") ;
			}
				
			//更新发起者的失败次数
			$loserId = $this->join->user_id ;
			$row = \DB::table('users')->where('id', $loserId )
			->increment('lose_big_small', 1 );
			if (!$row) {
				throw new \Exception('更新发起者失败次数出错');
			}
			$row = \DB::table('users')->where('id', $loserId )
			->increment('lose_big_small_cash', $winCash );
			if (!$row) {
				throw new \Exception('更新接受者失败铜板次数出错');
			}
			\DB::commit();
			return true ;
		} catch( \Exception $e ) {
			\Log::info( $e->getTraceAsString() );
			\DB::rollback();
			$this->fail( $e ) ;
		}
	}
}
