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

class GameFail implements ShouldQueue
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
        // TODO 两天前没有退款的用户
        SmallBig::where('status' , 1 )->where('updated_at' , '<=' , date('Y-m-d H:i:s' , time() - 86400 ) )->chunk( 100 , function( $records ){
        	foreach( $records as $rec ) {
        		\DB::beginTransaction();
        		try {
        			$row = SmallBig::where('id' , $rec->id )->where('status' , 1)->update([
        					'status' => 5
        			]);
        			if (! $row) {
        				throw new \Exception("战斗状态更新错误");
        			}
        			//退还给输家
        			if( $rec->user_num > $rec->inviter_num ) {
        				$user = User::find( $rec->invite_user_id );
        			} else {
        				$user = User::find( $rec->user_id );
        			}
        			$row = \DB::table('users')->where('id', $user->id)
        			->increment('cash_reward', $rec->cash_deposit);
        			if (! $row) {
        				throw new \Exception('铜板更新出错');
        			}
        			$cash = \App\Models\UserCash::create([
        					'user_id' => $user->id,
        					'event' => 'bigsmallrefund',
        					'target_id' => $rec->id,
        					'target_desc' => '比大小失败退还铜板',
        					'act' => 'in',
        					'cash' => $rec->cash_deposit,
        					'extra_cash' => 0
        			]);
        			if (empty($cash)) {
        				throw new \Exception('铜板流水写入错误');
        			}
        			//增加失败者的 不守约次数
        			$row = \DB::table('users')->where('id', $user->id)
        			->increment('not_pay_big_small', 1 );
        			if (! $row) {
        				throw new \Exception('不守约次数更新出错');
        			}
        			
        			$row = \DB::table('users')->where('id', $user->id)
        			->increment('not_pay_big_small_cash', $rec->cash_deposit );
        			if (! $row) {
        				throw new \Exception('不守约铜板更新出错');
        			}
        			
        			
        			//从失败者中扣除失败者的铜板
        			/***
        			 * 平台抽取佣金
        			$commissionRate = config('BIG_SMALL_COMMISSION' , 0 );
        			$commission = floor( $rec->cash_deposit * $commissionRate / 100 );
        			if( $commission ) {
        				$row = \DB::table('users')->where('id', $user->id)
        				->decrement('cash_reward', $rec->cash_deposit);
        				if (! $row) {
        					throw new \Exception('铜板更新出错');
        				}
        				$cash = \App\Models\UserCash::create([
        						'user_id' => $user->id,
        						'event' => 'bigsmallfail',
        						'target_id' => $rec->id,
        						'target_desc' => '比大小失败未支付扣取铜板',
        						'act' => 'out',
        						'cash' => $commission ,
        						'extra_cash' => 0
        				]);
        				if (empty($cash)) {
        					throw new \Exception('铜板流水写入错误');
        				}
        			}
        			***/
        			\DB::commit();
        		} catch( \Exception $e ) {
        			\Log::info( $e->getTraceAsString() );
        			\DB::rollBack();
        		}
        	}
        });
    }
}