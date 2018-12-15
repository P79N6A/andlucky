<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\SmallBig;
use App\Ext\Easemob;
use Illuminate\Support\Facades\Cache;
use App\Models\MessageLog;
use App\Ext\RecentChatHelper;

class BigSmallController extends Controller
{
	
	public function longsearch(Request $request) {
		$keyword = $request->input('keyword');
        // 默认获取参加过比大小的用户信息
        //TODO 这里可以批量检查用户的在线状态
		$user = auth()->guard('web')->user();
        if ($keyword) {
            $users = User::where('name', 'like', "%{$keyword}%")
                    ->orWhere('nickname', 'like', "%{$keyword}%")
                    ->orWhere('id', $keyword)
                    ->where('id' , '<>' , data_get($user , 'id' ) )
                    ->take(150)->get();
        } else {
            $online = Cache::get( 'all_online_users' );
            $users = [] ;

            if( is_array( $online ) ) {
                foreach( $online as $k => $v ) {
                    if( Cache::get('online:' . $k ) === 1  ) {
                        
                    } else {
                        unset( $online[ $k ] );
                    }
                }
                $user = auth()->guard()->user();
                whereIn('name', array_keys( $online ) )->
                $users = User::where('id' , '<>' , data_get( $user , 'id') )
                ->take(150)->get();
                Cache::forever( 'all_online_users' , $online );
            }
            /**
            $user = auth()->guard()->user();
            $users = User::where('id' , '<>' , data_get( $user , 'id') )
                ->take(100)->get();
             */

        }
		
		if( $users ) {
			foreach( $users as $k=> $u ) {
				$users[ $k ]['online'] = Cache::get('online:' . data_get( $u , 'name' ) ) === 1 ? 1 : 0 ;
				$users[ $k ]['credit'] = $this->credit( $u );
				$users[ $k ]['mobile'] = substr_replace( $u['mobile'] , '****' , 3 , 7 ) ;
				//获取当前用户的比大小次数
				$users[ $k ]['game_times'] = SmallBig::where( function( $query ) use( $u ) {
					return $query->where('user_id' , $u->id )->orWhere('invite_user_id' , $u->id );
				})->where('status' , [ 1 , 2 ,6 ] )->whereDate('created_at' , date('Y-m-d'))->count();
			}
			return response()->json([
					'errcode' => 0 ,
					'data' => $users
			]);
		} else {
			return response()->json([
				'errcode' => 10002 ,
				'msg' => 'no change' 
			]);
		}
		
	}

	protected function credit( $user ) {
        $a = data_get( $user , 'not_pay_big_small' );
	    $b = data_get( $user , 'lose_big_small' );
	    $c = data_get( $user , 'not_pay_big_small_cash' );
	    $d = data_get( $user , 'lose_big_small_cash' );

	    $b = $b > 0 ? $b : 1 ;
	    $d = $d > 0 ? $d : 1 ;
	    $a = $a > 0 ? $a : 0 ;
	    $c = $c > 0 ? $c : 0 ;

	    return number_format( ( 5 - ( 2 * $a / $b + 3 * $c / $d ) ) , 2 , '.' , '') ;

    }

    public function searchUser(Request $request)
    {
        $keyword = $request->input('keyword');
        // 默认获取参加过比大小的用户信息
        //TODO 这里可以批量检查用户的在线状态
        $user = auth()->guard('web')->user();
        if ($keyword) {
            $users = User::where('name', 'like', "%{$keyword}%")
                    ->orWhere('nickname', 'like', "%{$keyword}%")
                    ->orWhere('id', $keyword)
                    ->where('id' , '<>' , data_get($user , 'id' ) )
                    ->take(150)->get();
        } else {
        	$online = Cache::get( 'all_online_users' );
        	$users = [] ;
            /*
        	if( is_array( $online ) ) {
	        	foreach( $online as $k => $v ) {
	        		if( Cache::get('online:' . $k ) === 1  ) {
	        			
	        		} else {
	        			unset( $online[ $k ] );
	        		}
	        	}
	        	$user = auth()->guard()->user();
                //whereIn('name', array_keys( $online ) )->
	        	$users = User::where('id' , '<>' , data_get( $user , 'id') )
	        	->take(50)->get();
	        	Cache::forever( 'all_online_users' , $online );
        	}
            */
            $user = auth()->guard()->user();
            $users = User::where('id' , '<>' , data_get( $user , 'id') )
                ->take(150)->get();

        }

        if( $users ) {
        	foreach( $users as $k=> $u ) {
        		$users[ $k ]['online'] = Cache::get('online:' . data_get( $u , 'name' ) ) === 1 ? 1 : 0 ;
        		$users[ $k ]['mobile'] = substr_replace( $u['mobile'] , '****' , 3 , 7 ) ;
                $users[ $k ]['credit'] = $this->credit( $u );
        		//获取当前用户的比大小次数
        		$users[ $k ]['game_times'] = SmallBig::where( function( $query ) use( $u ) {
		        	return $query->where('user_id' , $u->id )->orWhere('invite_user_id' , $u->id );
		        })->where('status' , [ 1 , 2 ,6 ] )->whereDate('created_at' , date('Y-m-d'))->count();
        	}
        }
        return response()->json([
        		'errcode' => 0 ,
        		'html' => view('game.onlineuser' , ['list' => $users ])->render()
        ]);
    }

    public function index() {
        $desc = config('BIG_SMALL_DESC');
        $min = config('BIG_SMALL_MIN_POINT');
        $max = config('BIG_SMALL_MAX_POINT');

        $data = [
            'desc' => $desc ,
            'min' => $min ,
            'max' => $max ,
            'foot' => 'game'
        ] ;
        return view('game.index' , $data );
        return response()->json([
            'min' => $min,
            'max' => $max,
            'desc' => $desc
        ]);
    }

    public function help() {
        $desc = config('BIG_SMALL_DESC');
        return view('game.help' , ['data' => $desc ]) ;
    }

    /**
     * 邀请
     * 
     * @param Request $request
     * @throws \Exception
     * @throws Exception
     */
    public function invite(Request $request)
    {
        $inviteUserId = $request->input('invite_user');
        $deposit = (int) $request->input('promise');
        if (! $deposit) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '请填写铜板'
            ]);
        }
        $user = auth()->guard('wap')->user();
        if( $inviteUserId == $user->id ) {
        	return response()->json([
        			'errcode' => 10002,
        			'msg' => '想左右互搏是不可能的'
        	]);
        }
        if ($user->cash_reward < $deposit) {
            return response()->json([
                'errcode' => 10002,
                'msg' => '您的铜板不足，请先去赚取铜板吧'
            ]);
        }
        if( !$user->alipay_account && false ) {
        	return response()->json([
        			'errcode' => 10006,
        			'msg' => '您没有填写支付宝账号，请先去填写支付宝账号吧'
        	]);
        }
        $inviter = User::findOrFail($inviteUserId);
        if (empty($inviter)) {
            return response()->json([
                'errcode' => 10003,
                'msg' => '您邀请的用户走丢了，请换个用户再试吧'
            ]);
        }
        if( !$inviter->alipay_account && false ) {
        	return response()->json([
        			'errcode' => 10004,
        			'msg' => '您邀请对手还没有填写支付宝账号，请换个用户再试吧'
        	]);
        }
        
        
        //限制用户每天发起的比大小次数
        $maxTimes = config('GAME_TIMES', 20);
        //获取自己的次数
        $userTimes = SmallBig::where( function( $query ) use( $user ) {
        	return $query->where('user_id' , $user->id )->orWhere('invite_user_id' , $user->id );
        })->where('status' , [ 1 , 2 ,6 ] )->whereDate('created_at' , date('Y-m-d'))->count();
        if( $userTimes >= $maxTimes ) {
        	return response()->json([
        			'errcode' => 10003,
        			'msg' => '您今日已经达到比大小次数上限'
        	]);
        }
        
        //获取对方的次数
        $inviterTimes = SmallBig::where( function( $query ) use( $inviter ) {
        	return $query->where('user_id' , $inviter->id )->orWhere('invite_user_id' , $inviter->id );
        })->where('status' , [ 1 , 2 ,6 ] )->whereDate('created_at' , date('Y-m-d'))->count();
        if( $inviterTimes >= $maxTimes ) {
        	return response()->json([
        			'errcode' => 10003,
        			'msg' => '对方今日已经达到比大小次数上限'
        	]);
        }
        
        // 开始扣减费用 并且创建邀请信息
        \DB::beginTransaction();
        try {
            $roll = rand(1, 13);
            $game = \App\Models\SmallBig::create([
                'user_id' => $user->id,
                'invite_user_id' => $inviter->id,
                'cash_deposit' => $deposit,
                'deposit_status' => 1,
                'status' => 0,
                'user_num' => $roll,
                'inviter_num' => 0
            ]);
            if (empty($game)) {
                // 战斗创建失败
                throw new \Exception("战斗创建失败");
            }
            
            $row = \DB::table('users')->where('id', $user->id)
                ->where('cash_reward', $user->cash_reward)
                ->decrement('cash_reward', $deposit);
            if (! $row) {
                throw new Exception('铜板不足');
            }
            $cash = \App\Models\UserCash::create([
                'user_id' => $user->id,
                'event' => 'bigsmall',
                'target_id' => $game->id,
                'target_desc' => '发起比大小战斗',
                'act' => 'out',
                'cash' => $deposit,
                'extra_cash' => 0
            ]);
            if (empty($cash)) {
                throw new \Exception('铜板流水写入错误');
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'errcode' => 10005,
                'msg' => $e->getMessage()
            ]);
        }
        // TODO 下战书给对方
        // 加好友信息成功 发送好友申请请求
        /**
        $easemob = new Easemob(config('global.easemob')); 
        $result = $easemob->sendCmd( $user->name , "users", [
        		$inviter->name
        ], $user->nickname , [
        		'action' => 'invite_bigsmall',
        		'from_user_name' => $user->nickname,
        		'from_user_avatar' => $user->avatar,
        		'event_id' => $game->id,
        		'event_info' => $game->toArray()
        
        ]);
        **/
        //把系统消息  写到聊天记录上去
        /**
         * 移除聊天提示
         *
        $log = MessageLog::create([
        		'sender_id' => $user->id ,
        		'receiver_id' => $inviter->id ,
        		'msg_id' => $game->id ,
        		'body' => [
        				'data' => data_get( $user , 'nickname' )
        		],
        		'type' => 'invite_bigsmall',
        		'send_time' => \Carbon\Carbon::now()->getTimestamp()
        ]);
        $helper = new RecentChatHelper();
        $helper->set( $log );
         */
        //发送聊天信息
        return response()->json([
            'errcode' => 0,
            'game' => $game,
            'msg' => '邀战成功，等待对方应战!'
        ]);
    }

    public function accept($id){
    	$user = auth()->guard('wap')->user();
    	$game = SmallBig::where('id', $id)->where('invite_user_id', $user->id)->first();
    	if (! $game) {
    		return response()->json([
    				'errcode' => 10001,
    				'msg' => '您没有这个邀请'
    		]);
    	}
    	if( false && !$user->alipay_account ) {
    		return response()->json([
    				'errcode' => 10006,
    				'msg' => '您没有填写支付宝账号，请先去填写支付宝账号吧'
    		]);
    	}
    	if ($user->cash_reward < $game->cash_deposit ) {
    		return response()->json([
    				'errcode' => 10002,
    				'msg' => '您的铜板不足，请先充值铜板'
    		]);
    	}
    	$inviter = User::where('id', $game->user_id)->first();
    	
    	//限制用户每天发起的比大小次数
    	$maxTimes = config('GAME_TIMES', 20);
    	//获取自己的次数
    	$userTimes = SmallBig::where( function( $query ) use( $user ) {
    		return $query->where('user_id' , $user->id )->orWhere('invite_user_id' , $user->id );
    	})->where('status' , [ 1 , 2 ,6 ] )->whereDate('created_at' , date('Y-m-d'))->count();
    	if( $userTimes >= $maxTimes ) {
    		return response()->json([
    				'errcode' => 10003,
    				'msg' => '您今日已经达到比大小次数上限'
    		]);
    	}
    	
    	//获取对方的次数
    	$inviterTimes = SmallBig::where( function( $query ) use( $inviter ) {
    		return $query->where('user_id' , $inviter->id )->orWhere('invite_user_id' , $inviter->id );
    	})->where('status' , [ 1 , 2 ,6 ] )->whereDate('created_at' , date('Y-m-d'))->count();
    	if( $inviterTimes >= $maxTimes ) {
    		return response()->json([
    				'errcode' => 10003,
    				'msg' => '对方今日已经达到比大小次数上限'
    		]);
    	}
    	
    	// 拒绝本次邀请 只需要修改战斗状态 ， 退还铜板 ， 发送邮件提醒就可以了
    	\DB::beginTransaction();
    	try {
    		
    		
    		//生成随机数
    		
    		do {
    			$roll = rand(1, 13);
    		} while( $roll == $game->user_num ) ;
    		$row = SmallBig::where('id', $id)->where('invite_user_id', $user->id)
    		->where('status', 0)
    		->update([
    				'status' => 1 ,
    				'inviter_num' => $roll
    		]);
    		if (! $row) {
    			throw new \Exception("战斗状态更新错误");
    		}
    		//扣取应战者的费用
    		$row = \DB::table('users')->where('id', $user->id)
    		->where('cash_reward', $user->cash_reward)
    		->decrement('cash_reward', $game->cash_deposit);
    		if (! $row) {
    			throw new \Exception('铜板不足');
    		}
    		$cash = \App\Models\UserCash::create([
    				'user_id' => $user->id,
    				'event' => 'bigsmall',
    				'target_id' => $game->id,
    				'target_desc' => '接受比大小战斗',
    				'act' => 'out',
    				'cash' => $game->cash_deposit,
    				'extra_cash' => 0
    		]);
    		if (empty($cash)) {
    			throw new \Exception('铜板流水写入错误');
    		}
    		
    		if( $roll > $game->user_num ) {
    			//更新失败的 失败总次数
    			$row = \DB::table('users')->where('id', $inviter->id)
    			->increment('lose_big_small', 1 );
    			if (! $row) {
    				throw new \Exception('更新失败者的总次数');
    			}
    			$row = \DB::table('users')->where('id', $inviter->id)
    			->increment('lose_big_small_cash', $game->cash_deposit );
    			if (! $row) {
    				throw new \Exception('更新失败者的总铜板');
    			}
    			//应战者获胜	退还应战者的铜板
    			dispatch( new \App\Jobs\GameWin($game->id , $game->cash_deposit , $user->id , $inviter->id )) ;
    		} else {
    			$row = \DB::table('users')->where('id', $user->id)
    			->increment('lose_big_small', 1 );
    			if (! $row) {
    				throw new \Exception('更新失败者的总次数');
    			}
    			$row = \DB::table('users')->where('id', $user->id)
    			->increment('lose_big_small_cash', $game->cash_deposit );
    			if (! $row) {
    				throw new \Exception('更新失败者的总铜板');
    			}
    			//发起者获胜 退还发起者的铜板
    			dispatch( new \App\Jobs\GameWin($game->id , $game->cash_deposit  , $inviter->id , $user->id )) ;
    		}
    		//更新双方的参与次数
    		$row = \DB::table('users')->where('id', $inviter->id)
    		->increment('total_big_small', 1 );
    		if (! $row) {
    			throw new \Exception('更新发起者总次数出错');
    		}
    		$row = \DB::table('users')->where('id', $user->id)
    		->increment('total_big_small', 1 );
    		if (! $row) {
    			throw new \Exception('更新接受者总次数出错');
    		}
    		\DB::commit();
    	} catch (\Exception $e) {
    		\DB::rollBack();
    		\Log::info( $e->getTraceAsString() );
    		return response()->json([
    				'errcode' => 10005,
    				'msg' => $e->getMessage() ,
    				'trace' => $e->getTraceAsString()
    		]);
    	}
    	// TODO send msg to inviter
        $game = SmallBig::with('user', 'inviter')->find( $id ) ;
    	if( $game->user_num > $game->inviter_num ) {
    	    $msg = '很遗憾您输掉了战斗！！！';
        } else {
    	    $msg = '恭喜您获得胜利！！！';
        }
    	return response()->json([
    			'errcode' => 0,
    			'msg' => $msg
    	]);
    }
    
    /**
     * 比大小获胜退不铜板
     * @param unknown $gameId
     * @param unknown $cash
     * @param unknown $userId
     */
    protected function _refund( $gameId , $cash , $userId , $loserId ) {
    	
    }

    public function reject($id)
    {
        $user = auth()->guard('wap')->user();
        $game = SmallBig::where('id', $id)->where('invite_user_id', $user->id)->first();
        if (! $game) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '您没有这个邀请'
            ]);
        }
        $inviter = User::where('id', $game->user_id)->first();
        // 拒绝本次邀请 只需要修改战斗状态 ， 退还铜板 ， 发送邮件提醒就可以了
        \DB::beginTransaction();
        try {
            $row = SmallBig::where('id', $id)->where('invite_user_id', $user->id)
                ->where('status', 0)
                ->update([
                'status' => 3
            ]);
            if (! $row) {
                throw new \Exception("战斗状态更新错误");
            }
            $row = \DB::table('users')->where('id', $inviter->id)
                ->where('cash_reward', $inviter->cash_reward)
                ->increment('cash_reward', $game->cash_deposit);
            if (! $row) {
                throw new \Exception('铜板更新出错');
            }
            $cash = \App\Models\UserCash::create([
                'user_id' => $inviter->id,
                'event' => 'bigsmallrefund',
                'target_id' => $game->id,
                'target_desc' => '比大小拒绝战斗退还铜板',
                'act' => 'in',
                'cash' => $game->cash_deposit,
                'extra_cash' => 0
            ]);
            if (empty($cash)) {
                throw new \Exception('铜板流水写入错误');
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'errcode' => 10005,
                'msg' => $e->getMessage()
            ]);
        }
        // TODO send msg to inviter
        return response()->json([
            'errcode' => 0,
            'game' => SmallBig::with('user', 'inviter')->find( $id ),
        	'user' => $user,
            'msg' => '拒绝成功'
        ]);
    }
    
    public function cancel($id)
    {
    	$user = auth()->guard('wap')->user();
    	$game = SmallBig::where('id', $id)->where('user_id', $user->id)->first();
    	if (! $game) {
    		return response()->json([
    				'errcode' => 10001,
    				'msg' => '您没有这个邀请'
    		]);
    	}
    	$inviter = User::where('id', $game->user_id)->first();
    	// 拒绝本次邀请 只需要修改战斗状态 ， 退还铜板 ， 发送邮件提醒就可以了
    	\DB::beginTransaction();
    	try {
    		$row = SmallBig::where('id', $id)->where('user_id', $user->id)
    		->where('status', 0)
    		->update([
    				'status' => 4
    		]);
    		if (! $row) {
    			throw new \Exception("战斗状态更新错误");
    		}
    		$row = \DB::table('users')->where('id', $inviter->id)
    		->where('cash_reward', $inviter->cash_reward)
    		->increment('cash_reward', $game->cash_deposit);
    		if (! $row) {
    			throw new \Exception('铜板更新出错');
    		}
    		$cash = \App\Models\UserCash::create([
    				'user_id' => $inviter->id,
    				'event' => 'bigsmallrefund',
    				'target_id' => $game->id,
    				'target_desc' => '比大小拒绝战斗退还铜板',
    				'act' => 'in',
    				'cash' => $game->cash_deposit,
    				'extra_cash' => 0
    		]);
    		if (empty($cash)) {
    			throw new \Exception('铜板流水写入错误');
    		}
    		\DB::commit();
    	} catch (\Exception $e) {
    		\DB::rollBack();
    		return response()->json([
    				'errcode' => 10005,
    				'msg' => $e->getMessage()
    		]);
    	}
    	// TODO send msg to inviter
    	return response()->json([
    			'errcode' => 0,
    			'game' => SmallBig::with('user', 'inviter')->find( $id ),
    			'msg' => '成功'
    	]);
    }

    /**
     * 应邀请
     */
    public function invited($id)
    {
        $desc = config('BIG_SMALL_DESC');
        $min = config('BIG_SMALL_MIN_POINT');
        $max = config('BIG_SMALL_MAX_POINT');
        $user = auth()->guard('wap')->user();
        $game = SmallBig::with('user', 'inviter')->where('invite_user_id', $user->id)
            ->where('id', $id)
            ->first();
        return response()->json([
            'min' => $min,
            'max' => $max,
            'desc' => $desc,
            'game' => $game
        ]);
    }
    
    public function reback($id) {
    	$game = SmallBig::findOrFail( $id ) ;
    	$user= auth()->guard('wap')->user();
    	if( $game->inviter_num > $game->user_num ) {
    		if( $user->id != $game->invite_user_id ) {
    			return response()->json([
    					'errcode' => '您没有权限'
    			]);
    		}
    	
    	} else {
    		if( $user->id != $game->user_id ) {
    			return response()->json([
    					'errcode' => '您没有权限'
    			]);
    		}
    	}
		try {
			$row = SmallBig::where('id', $id)
			->where('status', 1)
			->update([
					'status' => 2 ,
			]);
			if (! $row) {
				throw new \Exception("战斗状态更新错误");
			}
			//退还失败者的费用 并更新状态为
			if( $game->inviter_num < $game->user_num ) {
				//TODO 扣除平台佣金
				
				
				$inviter = User::find( $game->invite_user_id );
				$row = \DB::table('users')->where('id', $inviter->id)
				->where('cash_reward', $inviter->cash_reward)
				->increment('cash_reward', $game->cash_deposit);
				if (! $row) {
					throw new \Exception('铜板更新出错');
				}
				$cash = \App\Models\UserCash::create([
						'user_id' => $inviter->id,
						'event' => 'bigsmallrefund',
						'target_id' => $game->id,
						'target_desc' => '比大小退还铜板',
						'act' => 'in',
						'cash' => $game->cash_deposit,
						'extra_cash' => 0
				]);
				if (empty($cash)) {
					throw new \Exception('铜板流水写入错误');
				}
				
			} else {
				//TODO 扣除平台佣金
				
				
				$row = \DB::table('users')->where('id', $user->id)
				->where('cash_reward', $user->cash_reward)
				->increment('cash_reward', $game->cash_deposit);
				if (! $row) {
					throw new \Exception('铜板更新出错');
				}
				$cash = \App\Models\UserCash::create([
						'user_id' => $user->id,
						'event' => 'bigsmallrefund',
						'target_id' => $game->id,
						'target_desc' => '比大小退还铜板',
						'act' => 'in',
						'cash' => $game->cash_deposit,
						'extra_cash' => 0
				]);
				if (empty($cash)) {
					throw new \Exception('铜板流水写入错误');
				}
				
			}
			\DB::commit();
		} catch( \Exception $e ) {
			\DB::rollBack();
			return response()->json([
					'errcode' => 10005,
					'msg' => $e->getMessage()
			]);
		}
		return response()->json([
				'errcode' => 0,
				'game' => SmallBig::with('user', 'inviter')->find( $id ),
				'user' => $user,
				'msg' => '退还成功'
		]);
    }

    /**
     * 邀请详情
     */
    public function detail($id)
    {
        $user = auth()->guard('wap')->user();
        $game = SmallBig::with('user', 'inviter')->where('id', $id)->first();
        if (! $game) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '没有本次战斗'
            ]);
        }
        // 是否是发起者
        $isInviter = $user->id == $game->user_id;
        $win = false ;
        if( $isInviter ) {
            $win = $game->user_num > $game->inviter_num ;
        } else {
            $win = $game->user_num < $game->inviter_num ;
        }
        $data = [
            'game' => $game,
            'user' => $user,
            'is_inviter' => $isInviter ,
            'win' => $win
        ] ;
        return view('game.show' , $data );
    }
    
    
    public function mine( Request $request ) {
    	$type = $request->input('type');
    	$query = SmallBig::with('user' , 'inviter');
    	$user = auth()->guard('wap')->user();
    	if( empty( $user ) ) {
    		return response()->json([
    				'errcode' => 0 ,
    				'data' => []
    		]);
    	}
    	$query = $query->where('user_id' , $user->id )->orWhere('invite_user_id' , $user->id );
    	switch( $type ) {
    		case 'mine' :
    			$query = $query->where('user_id' , $user->id );
    			break ;
    		case 'me' :
    			$query = $query->where('user_id' , $user->id )->where('status' , 5 );
    			break ;
    		case 'tomine' :
    			$query = $query->where('invite_user_id' , $user->id );
    			break ;
    		case 'theirs':
    			$query = $query->where('invite_user_id' , $user->id )->where('status' , 5 );
    			break ;
    	}
    	$list = $query->orderBy('id' , 'desc' )->paginate( 10 );
    	$data = [
    	    'list' => $list ,
            'user' => $user ,
            'foot' => 'game' ,
        ] ;
    	if( $request->ajax() ) {
    	    return response()->json([
    	        'errcode' => 0 ,
                'html' => view('game.mineitem' , $data )->render()
            ]);
        }
    	return view('game.mine' , $data );
    }
}
