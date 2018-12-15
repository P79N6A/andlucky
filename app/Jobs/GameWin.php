<?php
/**
 * 这个方法最好是用来进行游戏结算
 * 在退还的时候进行结算  其他时间的结算都 直接退还 不抽取佣金
 */
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

class GameWin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   
    protected $cash ;
    protected $userId ;
    protected $gameId ;
    protected $loserId ;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $gameId , $cash , $userId , $loserId ) {
    	$this->userId = $userId ;
    	$this->gameId = $gameId ;
    	$this->cash = $cash ;
    	$this->loserId = $loserId ;
    }

    public function handle()
    {
    	$userId = $this->userId ;
    	$gameId = $this->gameId ;
    	$cash = $this->cash ;
    	$loserId = $this->loserId ;
    	\DB::beginTransaction();
    	try {
    		//抽取佣金给介绍人
    		$user = User::findOrFail( $userId );
    		//获取上级
    		if( $user->invite_by ) {
    			$parent = User::where('invite_code' , $user->invite_by)->first();
    			if( $parent ) {
    				//返佣比
    				$rerundRate = config('REFUND_RATE' , 1 );
    				$refund = sprintf("%.3f", $cash * $rerundRate / 100 );
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
    						'target_id' => $gameId,
    						'target_desc' => '比大小分润',
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
    		
    		
    		$row = \DB::table('users')->where('id', $userId )
    		//->where('cash_reward', $user->cash_reward)
    		->increment('cash_reward', $cash );
    		if (!$row) {
    			throw new \Exception('铜板更新出错1');
    		}
    		$cashLog = \App\Models\UserCash::create([
    				'user_id' => $userId,
    				'event' => 'bigsmallrefund',
    				'target_id' => $gameId,
    				'target_desc' => '比大小获胜退还铜板',
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
    		return false ;
    	}
    }
}