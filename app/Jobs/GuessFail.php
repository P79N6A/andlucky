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
use App\Models\GuessJoin;

class GuessFail implements ShouldQueue
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
        GuessJoin::with('guess')->where('status' , 1 )->where('updated_at' , '<=' , date('Y-m-d H:i:s' , time() - 86400 ) )->chunk( 100 , function( $records ){
        	if( $records->isNotEmpty() ) {
	        	foreach( $records as $rec ) {
	        		\DB::beginTransaction();
	        		try {
	        			$row = GuessJoin::where('id' , $rec->id )->where('status' , 1)->update([
	        					'status' => 3
	        			]);
	        			if (! $row) {
	        				throw new \Exception("押大小参与状态更新错误");
	        			}
	        			//退还给输家
	        			if( $rec->is_win == 1 ) {
	        				//退给庄家
	        				$cash = $rec->cash * $rec->guess->rate ;
	        				$user = User::find( $rec->guess->user_id );
	        			} else {
	        				//退给参与者
	        				$cash = $rec->cash ;
	        				$user = User::find( $rec->user_id );
	        			}
	        			$row = \DB::table('users')->where('id', $user->id)
	        			->increment('cash_reward', $cash );
	        			if (! $row) {
	        				throw new \Exception('铜板更新出错');
	        			}
	        			$cashInfo = \App\Models\UserCash::create([
	        					'user_id' => $user->id,
	        					'event' => 'guessrefund',
	        					'target_id' => $rec->id,
	        					'target_desc' => '押大小失利系统退还铜板',
	        					'act' => 'in',
	        					'cash' => $cash ,
	        					'extra_cash' => 0
	        			]);
	        			if (empty($cashInfo)) {
	        				throw new \Exception('铜板流水写入错误');
	        			}
	        			//增加失败者的 不守约次数
	        			$row = \DB::table('users')->where('id', $user->id)
	        			->increment('not_pay_big_small', 1 );
	        			if (! $row) {
	        				throw new \Exception('不守约次数更新出错');
	        			}
	        			
	        			$row = \DB::table('users')->where('id', $user->id)
	        			->increment('not_pay_big_small_cash', $cash );
	        			if (! $row) {
	        				throw new \Exception('不守约铜板更新出错');
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
