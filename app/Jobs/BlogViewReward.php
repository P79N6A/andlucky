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
use App\Models\ViewLog;

class BlogViewReward implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $id ;
	protected $userId ;
	protected $view ;
	protected $cash ;
	
	public function __construct( $userId , $view , $cash ) {
		$this->userId = $userId ;
		$this->view = $view ;
		$this->cash = $cash ;
	}
	
	
	public function handle() {
		$view = $this->view ;
		$cash = $this->cash ;
		\DB::beginTransaction();
		try {
			//抽取佣金给介绍人
			$user = User::findOrFail( $this->userId );
			//获取上级
			if( $user->invite_by ) {
				$parent = User::where('invite_code' , $user->invite_by)->first();
				if( $parent ) {
					//返佣比
					$rerundRate = config('REFUND_RATE' , 1 );
					$refund = sprintf( "%.3f" , $cash * $rerundRate / 100 );
					$cash = sprintf( "%.3f" , $cash - $refund );
					//写流水  和  写分润记录
                    if( $refund > 0 ) {

                        $row = \DB::table('users')->where('id', $parent->id)
                            //->where('cash_reward', $user->cash_reward)
                            ->increment('cash_reward', $refund);
                        if (!$row) {
                            throw new \Exception('分润者铜板更新出错');
                        }
                        $cashLog = \App\Models\UserCash::create([
                            'user_id' => $parent->id,
                            'event' => 'refund',
                            'target_id' => $this->userId,    //这里写入的分润者ID
                            'target_desc' => '资讯阅读分润',
                            'act' => 'in',
                            'cash' => $refund,
                            'extra_cash' => 0
                        ]);
                        if (empty($cashLog)) {
                            throw new \Exception('分润者铜板流水写入错误');
                        }
                    }
				}
			}
		
			$row = \DB::table('users')->where('id', $this->userId )
			//->where('cash_reward', $user->cash_reward)
			->increment('cash_reward', $cash );
			if (!$row) {
				throw new \Exception('铜板更新出错');
			}
			$cashLog = \App\Models\UserCash::create([
					'user_id' => $this->userId ,
					'event' => 'blog-view',
					'target_id' => $this->view ,
					'target_desc' => date('Y-m-d' , strtotime( "-1 day" ) ) . '阅读资讯获得收益',
					'act' => 'in',
					'cash' => $cash ,
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
			$this->fail( $e->getMessage() );
			return false ;
		}
		
	}
}