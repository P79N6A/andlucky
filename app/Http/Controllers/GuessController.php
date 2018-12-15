<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Guess;
use App\Models\UserCash;
use App\Ext\Easemob;
use Illuminate\Support\Facades\Cache;
use App\Models\MessageLog;
use App\Ext\RecentChatHelper;
use App\Models\GuessJoin;

class GuessController extends Controller
{

    public function create( Request $request ) {

        return view('guess.create');
    }

	public function store( Request $request ) {
		$user = auth()->guard()->user();
		$cash = $request->input('cash');
		$type = $request->input('type');
		$seed = $request->input('seed');
		$last = (int) $request->input( 'last' );
		$last = time() + $last * 3600 ;
		if( $user->cash_reward < $cash ) {
			return response()->json([
					'errcode' => 30000,
					'msg' => '您的余额不足，请选充值'
			]);
		}
		if( !$user->alipay_account ) {
        	return response()->json([
        			'errcode' => 10006,
        			'msg' => '您没有填写支付宝账号，请先去填写支付宝账号吧'
        	]);
        }
		//每人每天最大可以投入多少铜板来玩 
		$guessMaxCash = config('GUESS_MAX_CASH' , 1000 );
		if( $cash > $guessMaxCash ) {
            return response()->json([
                'errcode' => 10002 ,
                'msg' => "超过最大投注{$guessMaxCash}上限"
            ]);
        }
		if( Guess::where('created_at' , '>=' , date('Y-m-d 00:00:00') )->where('user_id' , $user->id )->whereIn('status' , [0,1])->sum('cash') + GuessJoin::where('created_at' , '>=' , date('Y-m-d 00:00:00') )->where('user_id' , $user->id )->sum('cash') >= $guessMaxCash ) {
			return response()->json([
					'errcode' => 10002 ,
					'msg' => "您今日已经达到最大投注{$guessMaxCash}上限"
			]);
		}
		//每天最多玩的次数
		$maxTimes = config('GUESS_MAX_TIMES', 20);
		if( Guess::where('created_at' , '>=' , date('Y-m-d 00:00:00') )->where('user_id' , $user->id )->whereIn('status' , [0,1])->count() + GuessJoin::where('created_at' , '>=' , date('Y-m-d 00:00:00')  )->where('user_id' , $user->id )->count() >= $maxTimes ) {
			return response()->json([
					'errcode' => 10002 ,
					'msg' => "您今日已经达到最大游戏次数{$maxTimes}上限"
			]);
		}
		
		
		
		\DB::beginTransaction();
		try {
			$guess = new Guess();
			$guess->user_id = $user->id ;
			$guess->cash = $cash ;
			$guess->rate = $type ;
			$guess->seed = $seed ;
			$guess->max_join = 3 ;
			$guess->has_join = 0 ;
			$guess->occupy_cash = 0 ;
			$guess->win_cash = 0 ;
			$guess->lose_cash = 0 ;
			$guess->end_time = $last ;
			$guess->status = 0 ;
			if( !$guess->save() ) {
				throw new \Exception("发起押大小失败");
			}
			
			$row = User::where('id', $user->id)->where('cash_reward', $user->cash_reward)->update([
					'cash_reward' => \DB::raw('cash_reward - ' . $guess->cash )
			]);
			if ( !$row) {
				throw new \Exception("用户余额扣减失败");
			}
			$cash = new UserCash();
			$cash->user_id = $user->id;
			$cash->event = "create_guess";
			$cash->target_id = $guess->id;
			$cash->target_desc = '发起押大小:' . $guess->id;
			$cash->act = 'out';
			$cash->cash = $guess->cash ;
			$cash->extra_cash = 0;
			if ( !$cash->save()) {
				throw new \Exception("用户余额扣减记录写入失败");
			}
			\DB::commit();
		} catch( \Exception $e ) {
			\DB::rollBack();
			return response()->json([
					'errcode' => 10003,
					'msg' => $e->getMessage()
			]);
		}
		return response()->json([
				'errcode' => 0 ,
				'msg' => '发起成功' ,
                'url' => route('wap.guess.show' , ['id' => $guess->id , 'from' => 'form'])
		]);
	}
	
	
	public function show( $id ) {
		$guess = Guess::with(['join' , 'join.user' , 'user' ])->findOrFail( $id ) ;
		$user = auth()->guard('wap')->user();
		$data = [
		    'guess' => $guess ,
            'user' => $user ,
            'win' => false
        ];
		return view('guess.show' , $data );
	}

    /**
     * 所有的guess列表  以最新发布的排在最前
     *
     */
    public function all( Request $request ) {
        $list = Guess::with('user' , 'join' , 'join.user')
        ->where('status' , 0 )
        //->where('end_time' , '>' , time() )
        ->orderBy('status' , 'asc' )->orderBy('id' , 'desc' )->paginate( 10 );
        $data = [
            'list' => $list ,
            'foot' => 'game'
        ] ;
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'html' => view('guess.indexitem' , $data )->render()
            ]);
        }
        return view('guess.index' , $data );
    }
    
    /**
     * 获取参与的信息
     * @param unknown $id
     */
    public function take( $id ) {
    	$user = auth()->guard('wap')->user();
    	$game = Guess::with('user' , 'join')->where('id' , $id )->first();
    	if( $user->id == $game->user_id ) {
    		return response()->json([
    				'errcode' => 10001 ,
    				'msg' => '自己不能参与自己发起的比大小'
    		]);
    	}
    	$hasJoin = GuessJoin::where('user_id' , $user->id )->where('guess_id' , $id )->count();
    	if( $hasJoin ) {
    		return response()->json([
    				'errcode' => 10002 ,
    				'msg' => '您已经参与过了，请等待开台'
    		]);
    	}
    	return response()->json([
    			'errcode' => 0 ,
    			'data' => $game
    	]);
    }
    
    public function takeStore( $id , Request $request ) {
    	$user = auth()->guard('wap')->user();
    	$cash = $request->input('money');
    	$cash = number_format( $cash , 2 , '.' , '' );
    	$seed = $request->input('seed');
    	if( $cash < 1 ) {
    		return response()->json([
    				'errcode' => 10001 ,
    				'msg' => '您投入的铜板不能小于1'
    		]);
    	}
    	if( !in_array( $seed , [ 'max' , 'min' , 'mid' ] ) ) {
    		return response()->json([
    				'errcode' => 10002 ,
    				'msg' => '您选择的大小不符合规则'
    		]);
    	}
    	
    	if( !$user->alipay_account ) {
    		return response()->json([
    				'errcode' => 10003,
    				'msg' => '您没有填写支付宝账号，请先去填写支付宝账号吧'
    		]);
    	}
    	if ($user->cash_reward < $cash ) {
    		return response()->json([
    				'errcode' => 10004,
    				'msg' => '您的铜板不足，请先充值铜板'
    		]);
    	}
    	$game = Guess::with('user' , 'join')->where('id' , $id )->first();
    	if( $user->id == $game->user_id ) {
    		return response()->json([
    				'errcode' => 10005 ,
    				'msg' => '自己不能参与自己发起的比大小'
    		]);
    	}
    	$hasJoin = GuessJoin::where('user_id' , $user->id )->where('guess_id' , $id )->count();
    	if( $hasJoin ) {
    		return response()->json([
    				'errcode' => 10006 ,
    				'msg' => '您已经参与过了，请等待开台'
    		]);
    	}
    	$game = Guess::where('id' , $id )->whereRaw("( cash / rate - occupy_cash ) >= $cash ")->first();
    	if( empty( $game ) ) {
    		return response()->json([
    				'errcode' => 10007 , 
    				'msg' => '铜板发生了变化，刷新重试'
    		]);
    	}
    	//每人每天最大可以投入多少铜板来玩
    	$guessMaxCash = config('GUESS_MAX_CASH' , 1000 );
    	if( Guess::where('created_at' , '>=' , date('Y-m-d 00:00:00') )->where('user_id' , $user->id )->whereIn('status' , [0,1])->sum('cash') + GuessJoin::where('created_at' , '>=' , date('Y-m-d 00:00:00') )->where('user_id' , $user->id )->sum('cash') >= $guessMaxCash ) {
    		return response()->json([
    				'errcode' => 10002 ,
    				'msg' => "您今日已经达到最大投注{$guessMaxCash}上限"
    				]);
    	}
    	//每天最多玩的次数
    	$maxTimes = config('GUESS_MAX_TIMES', 20);
    	if( Guess::where('created_at' , '>=' , date('Y-m-d 00:00:00') )->where('user_id' , $user->id )->whereIn('status' , [0,1])->count() + GuessJoin::where('created_at' , '>=' , date('Y-m-d 00:00:00') )->where('user_id' , $user->id )->count() >= $maxTimes ) {
    		return response()->json([
    				'errcode' => 10002 ,
    				'msg' => "您今日已经达到最大游戏次数{$maxTimes}上限"
    				]);
    	}
    	\DB::beginTransaction();
    	try {
    		//创建参与记录
    		$join = new GuessJoin();
    		$join->guess_id = $id ;
    		$join->user_id = $user->id ;
    		$join->cash = $cash ;
    		$join->seed = $seed ;
    		$join->status = 0 ;
    		if( !$join->save() ) {
    			throw new \Exception("参与记录写入失败");
    		}
    	
    		//更新已投入铜板
    		$row = Guess::where('id' , $id )->whereRaw("( cash / rate - occupy_cash ) >= $cash ")
    		->where('end_time' , '>' , time() )
    		->update([
    				'occupy_cash' => $game->occupy_cash + $cash ,
    				'has_join' => $game->has_join + 1 
    		]);
    		if (! $row) {
    			throw new \Exception("铜板发生了变化，请重试");
    		}
    		//扣取费用
    		$row = \DB::table('users')->where('id', $user->id)
    		->where('cash_reward', $user->cash_reward)
    		->decrement('cash_reward', $cash );
    		if (! $row) {
    			throw new \Exception('铜板不足');
    		}
    		$cash = \App\Models\UserCash::create([
    				'user_id' => $user->id,
    				'event' => 'guess',
    				'target_id' => $id,
    				'target_desc' => '参与押大小',
    				'act' => 'out',
    				'cash' => $cash ,
    				'extra_cash' => 0
    		]);
    		if (empty($cash)) {
    			throw new \Exception('铜板流水写入错误');
    		}
    		//更新参与游戏记录数
    		$row = \DB::table('users')->where('id', $user->id)
    		->increment('total_big_small', 1 );
    		if (! $row) {
    			throw new \Exception('更新接受者总次数出错');
    		}
    		\DB::commit();
    		//TODO 处理参与结果 如果已经刚好满人了
    		dispatch( new \App\Jobs\GuessCheck( $id ) );
    		return response()->json([
    				'errcode' => 0,
    				'url' => route('wap.guess.show' , ['id' => $id ] ) ,
    				'msg' => '参与成功'
    		]);
    	} catch ( \Exception $e ) {
    		\DB::rollBack();
    		\Log::info( $e->getTraceAsString() );
    		return response()->json([
    				'errcode' => 10005,
    				'msg' => $e->getMessage() ,
    				'trace' => $e->getTraceAsString()
    		]);
    	}
    	
    	
    }
    
    /**
     * 我发起的比大小
     * @param Request $request
     */
    public function mine( Request $request ) {
    	$user = auth()->guard('wap')->user();
    	$list = Guess::with(['user'])->where('user_id' , $user->id )->orWhereIn('id' , function( $query ) use( $user ) {
    		return $query->from('guess_join')->where('user_id' , $user->id )->select('guess_id') ;
    	} )->orderBy('id' , 'desc')->paginate( 10 );
        $data = [
            'list' => $list ,
            'foot' => 'game'
        ] ;
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'html' => view('guess.mineitem' , $data )->render()
            ]);
        }
    	return view('guess.mine' , $data );
    }
    
    /**
     * 我参与的比大小
     */
    public function joins() {
    	$user = auth()->guard('wap')->user();
    	$list = GuessJoin::with('guess' , 'user' , 'guess.user')->where('user_id' , $user->id )->orderBy('id' , 'desc' )->paginate( 10 );
    	return response()->json([
    			'errcode' => 0 ,
    			'data' => $list
    	]);
    }
    
    /**
     * 退还铜板
     */
    public function reback( $id ) {
    	$user = auth()->guard('wap')->user();
    	$join = GuessJoin::with('guess')->findOrFail( $id ) ;
    	if( $join->is_win == 1 ) {
    		//参与者赢了退款
    		if( $user->id != $join->user_id ) {
    			return response()->json([
    					'errcode'=> 10001 , 
    					'msg' => '您没有权限'
    			]);
    		}
    		//退给庄家的是双倍的
    		$cash = $join->cash * $join->guess->rate ;
    		\DB::beginTransaction();
    		//退还奖励
    		try {
    			$row = \DB::table('users')->where('id', $join->guess->user_id )
    			->increment('cash_reward', $cash );
    			if (!$row) {
    				throw new \Exception('铜板更新出错');
    			}
    			$cashLog = \App\Models\UserCash::create([
    					'user_id' => $join->guess->user_id ,
    					'event' => 'guessrefund',
    					'target_id' => $join->id ,
    					'target_desc' => '押大小支付退还铜板',
    					'act' => 'in',
    					'cash' => $cash ,
    					'extra_cash' => 0
    			]);
    			if (empty($cashLog)) {
    				throw new \Exception('铜板流水写入错误');
    			}
    				
    			//更新记录
    			$row = GuessJoin::where('id' , $join->id )->where('status' , 1 )->update([
    					'status' => 2 , //赢了改退
    			]);
    			if(  !$row ) {
    				throw new \Exception("更新押大小记录状态出错") ;
    			}
    			\DB::commit();
    			return response()->json([
    					'errcode' => 0 ,
    					'msg' => '退还完成' ,
    					'guess' => Guess::with(['join' , 'join.user' , 'user' ])->findOrFail( $join->guess_id ) ,
    					'user' => $user
    			]) ;
    		} catch( \Exception $e ) {
    			\Log::info( $e->getTraceAsString() );
    			\DB::rollback();
    			return response()->json([
    					'errcode' => 10002 ,
    					'msg' => $e->getMessage()
    			]) ;
    		}
    		
    	} elseif( $join->is_win == 2 ) {
    		if( $user->id != $join->guess->user_id ) {
    			return response()->json([
    					'errcode'=> 10001 ,
    					'msg' => '您没有权限'
    			]);
    		}
    		$cash = $join->cash ;
    		\DB::beginTransaction();
    		//退还奖励
    		try {
    			$row = \DB::table('users')->where('id', $join->user_id )
    			->increment('cash_reward', $cash );
    			if (!$row) {
    				throw new \Exception('铜板更新出错');
    			}
    			$cashLog = \App\Models\UserCash::create([
    					'user_id' => $join->user_id ,
    					'event' => 'guessrefund',
    					'target_id' => $join->id ,
    					'target_desc' => '押大小支付退还铜板',
    					'act' => 'in',
    					'cash' => $cash ,
    					'extra_cash' => 0
    			]);
    			if (empty($cashLog)) {
    				throw new \Exception('铜板流水写入错误');
    			}
    		
    			//更新记录
    			$row = GuessJoin::where('id' , $join->id )->where('status' , 1 )->update([
    					'status' => 2 , //赢了改退
    			]);
    			if(  !$row ) {
    				throw new \Exception("更新押大小记录状态出错") ;
    			}
    			\DB::commit();
    			return response()->json([
    					'errcode' => 0 ,
    					'msg' => '退还完成' ,
    					'guess' => Guess::with(['join' , 'join.user' , 'user' ])->findOrFail( $join->guess_id ) ,
    					'user' => $user 
    			]) ;
    		} catch( \Exception $e ) {
    			\Log::info( $e->getTraceAsString() );
    			\DB::rollback();
    			return response()->json([
    					'errcode' => 10002 ,
    					'msg' => $e->getMessage()
    			]) ;
    		}
    	}
    	return response()->json([
    			'errcode' => 10002 ,
    			'msg' => '不可退还'
    	]);
    	
    }
}
