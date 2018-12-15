<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Stolen;
use App\Ext\Easemob;
use App\Ext\RecentStolenHelper;
use App\Models\ImFriend;

class StolenController extends Controller
{

    /**
     * 偷取金币
     */
    public function stolen($userId)
    {
        $maxTimes = config('STOLEN_MAX_TIMES', 10);
        $rate = config('STOLEN_RATE', 10);
        $fromUser = User::findOrFail($userId);
        $user = auth()->guard('wap')->user();
        if ($userId == $user->id) {
            return response()->json([
                'errcode' => 10003,
                'msg' => '您不能自摸'
            ]);
        }
        $isFriend = ImFriend::where('user_id' , $user->id )->where('friend_user_id' , $userId )
        ->where('status' , 1 )->count();
        if( !$isFriend ) {
        	return response()->json([
        			'errcode' => 10001,
        			'msg' => '他还不是您的好友，不能随便摸'
        	]);
        }
        
        $count = Stolen::where('from_user_id', $userId)->whereDate('created_at', date('Y-m-d'))->count();
        if ($count >= $maxTimes) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '亲给他留点吧'
            ]);
        }
        $userCash = $fromUser->cash_reward;
        if ($userCash < 100) {
            return response()->json([
                'errcode' => 10002,
                'msg' => '对方铜板不足100点，亲给他留点吧'
            ]);
        }
        $couldStolen = floor($userCash * $rate / 100); // 可以偷的最大铜板
        $stolen = rand(1, $couldStolen);
        
        // 写偷入记录
        \DB::beginTransaction();
        try {
            $stolenInfo = Stolen::create([
                'user_id' => $user->id,
                'from_user_id' => $userId,
                'cash' => $stolen
            ]);
            if (empty($stolenInfo)) {
                // 游戏创建失败
                throw new \Exception("没摸到");
            }
            
            $row = \DB::table('users')->where('id', $user->id)
                ->where('cash_reward', $user->cash_reward)
                ->increment('cash_reward', $stolen);
            if (! $row) {
                throw new Exception('摸的铜板遗失了');
            }
            $cash = \App\Models\UserCash::create([
                'user_id' => $user->id,
                'event' => 'stolen',
                'target_id' => $stolenInfo->id,
                'target_desc' => '摸',
                'act' => 'in',
                'cash' => $stolen,
                'extra_cash' => 0
            ]);
            if (empty($cash)) {
                throw new \Exception('铜板流水写入错误');
            }
            $row = \DB::table('users')->where('id', $userId)
                ->where('cash_reward', $fromUser->cash_reward)
                ->decrement('cash_reward', $stolen);
            if (! $row) {
                throw new Exception('摸的铜板遗失了');
            }
            $cash = \App\Models\UserCash::create([
                'user_id' => $userId,
                'event' => 'stolen',
                'target_id' => $stolenInfo->id,
                'target_desc' => '被摸',
                'act' => 'out',
                'cash' => $stolen,
                'extra_cash' => 0
            ]);
            if (empty($cash)) {
                throw new \Exception('铜板流水写入错误');
            }
            $helper = new RecentStolenHelper();
            $helper->set( $stolenInfo );
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'errcode' => 10005,
                'msg' => $e->getMessage()
            ]);
        }
        // TODO
        dispatch(new \App\Jobs\Stolen($stolenInfo, $fromUser, $user));
        
        // $easemob = new Easemob( config('global.easemob') );
        
        // $result = $easemob->sendCmd( $user->name , "users", [ $fromUser->name ] , 'stolen' , ['worth' => $stolen ]);
        
        return response()->json([
            'errcode' => 0,
            'data' => $stolen,
            'msg' => "您成功的摸到了{$stolen}铜板"
        ]);
    }

    /**
     * 最近谁偷过我的
     */
    public function thief() {
        $user = auth()->guard('wap')->user();
        $helper = new RecentStolenHelper();
        $ids = $helper->get( $user->id );
        $list = Stolen::with('thief')->whereIn( 'id' , $ids )->where('from_user_id', $user->id)
            ->select([
            'user_id',
            'cash',
            'from_user_id',
            'created_at'
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json([
            'errcode' => 0,
            'data' => $list
        ]);
    }
}
