<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use DB ;
use App\Models\Microblog;

class BlogReward implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $time ;

	/**
	 * 取这个时间前的数据进行结算
	 * @param unknown $day
	 */
	public function __construct( $day ) {
		$this->time = preg_match("/^\d{10,}$/", $day )  ? $day : strtotime( $day );		
	}
	
	public function handle() {
		//多少天后进行结算
		$day = config('MICROBLOG_REWARD_DAY' , 7 );
		$time = $this->time - 86400 * $day ;
		$rewardLevel = config('MICROBLOG_REWARD_LEVEL' , '' );
		if( empty( $rewardLevel ) ) {
			\Log::info("没有设置分成收益系数");
			return true ;
		}
		$rewardLevel = explode( PHP_EOL , $rewardLevel );
		$rewardRate = [] ;
		if( is_array( $rewardLevel ) && !empty( $rewardLevel )) {
			foreach ( $rewardLevel as $val ) {
				list( $k , $v ) = explode(',' , $val );
				$rewardRate[ $k ] = $v ;
			}
			ksort( $rewardRate );
		} else {
			\Log::info("没有设置分成收益系数");
			return true ;
		}
		
		Microblog::whereIn('allow_auth' , [0 , 1 ] )->where('created_at' , '>=' , date('Y-m-d 00:00:00' , $time ) )->where('created_at' , '<=' , date('Y-m-d 23:59:59' , $time ) )->orderBy('id' , 'asc' )->chunk( 100 , function( $blogs ) use( $rewardRate ) {
			//获取浏览数
			\Log::info( $blogs );
			if( $blogs->isNotEmpty() ) {
				foreach( $blogs as $blog ) {
					foreach( $rewardRate as $k => $r ) {
						if( $blog->views < $k ) {
							//如果大于最大值 则按归大值计算
							$r = (float) $r ;
							$num =  (int) ( data_get( $blog , 'views' , 0 ) - data_get( $blog , 'prase' , 0 ) ) ;
							$reward = $num * $r ;
							$reward = sprintf( "%.3f" , $reward ) ;
							\DB::beginTransaction();
							try {
								//抽取佣金给介绍人
								$user = User::findOrFail( $blog->user_id );
								//获取上级
								if( $user->invite_by ) {
									$parent = User::where('invite_code' , $user->invite_by)->first();
									if( $parent ) {
										//返佣比
										$rerundRate = config('REFUND_RATE' , 1 );
										$refund = sprintf("%.2f", $reward * $rerundRate / 100 );
										$reward = $reward - $refund ;
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
												'target_id' => $blog->id,
												'target_desc' => '资讯分润',
												'act' => 'in',
												'cash' => $refund ,
												'extra_cash' => 0
										]);
										if (empty($cashLog)) {
											throw new \Exception('分润者铜板流水写入错误');
										}
									}
								}
							
								$row = \DB::table('users')->where('id', $blog->user_id )
								//->where('cash_reward', $user->cash_reward)
								->increment('cash_reward', $reward );
								if (!$row) {
									throw new \Exception('铜板更新出错1');
								}
								$cashLog = \App\Models\UserCash::create([
										'user_id' => $blog->user_id ,
										'event' => 'blog',
										'target_id' => $blog->id,
										'target_desc' => '发布资讯获得收益',
										'act' => 'in',
										'cash' => $reward ,
										'extra_cash' => 0
								]);
								if (empty($cashLog)) {
									throw new \Exception('铜板流水写入错误');
								}
								\DB::commit();
								return true ;
							} catch( \Exception $e ) {
								\Log::info( $e->getTraceAsString() );
								\DB::rollback();
								return false ;
							}
							
							break ;
						}
					}
				}
			}
		}) ;
	}
}