<?php
namespace App\Http\Controllers;

use App\Ext\Easemob;
use App\User;
use App\Models\ImFriend;
use Illuminate\Http\Request;
use App\Models\MessageLog;
use function Encore\Admin\select;
use Illuminate\Support\Facades\Cache;
use App\Ext\RecentChatHelper;


class ImController extends Controller
{

    protected $easemob;

    public function __construct()
    {
        $this->easemob = new Easemob(config('global.easemob'));
    }
    
    /**
     * 客服端发出的心跳包
     */
    public function pong() {
    	$user = auth()->guard('wap')->user();
    	if( $user ) {
    		Cache::put( 'online:' . $user->name , 1 , 0.2 );
    		$online = Cache::get('all_online_users' ) ;
    		if( !$online ) {
    			$online = [] ;
    		}
    		$online[ $user->name ] = time() ;
    		Cache::forever( 'all_online_users' , $online );
    	}
    }

    public function index()
    {
        $user = auth()->guard()->user();
        
        $this->easemob->resetPassword($user->name, $user->api_token);
        return view('im.friends');
    }

    public function userinfo($id)
    {
        $user = User::select([
            'name',
            'id',
            'avatar',
            'gender' , 
        	'nickname' ,
        ])->find($id);
        return response()->json([
            'errcode' => 0,
            'data' => $user
        ]);
    }

    /**
     * 我的好友列表
     */
    public function friends()
    {
        $user = auth()->guard('wap')->user();
        $friends = ImFriend::with([
            'friend' => function( $query ){
            	return $query->select('id' , 'name' , 'mobile' , 'avatar' , 'nickname');
            }
        ])->where('user_id', $user->id)
            ->where('status', 1 )
            ->paginate(1000);
        if( $friends->items() ) {
        	foreach( $friends->items() as $k => $val ) {
        		$friends->items()[ $k ]->friend->online = Cache::get('online:' . $val->friend->name ) === 1 ? 1 : 0 ;
        		$friends->items()[ $k ]->friend->name = substr_replace( $val->friend->name , '****' , 4 , 4 ) ;
        		$friends->items()[ $k ]->friend->mobile = substr_replace( $val->friend->mobile , '****' , 4 , 4 ) ;
        	}
        }
        $data = [
            'list' => $friends ,
            'foot' => 'friends'
        ] ;
        return view('im.friends' , $data );
    }

    /**
     * 查找好友
     */
    public function search( Request $request )
    {
        $keyword = $request->input('keyword');
        if( $request->ajax() ) {
            $user = auth()->guard('wap')->user();
            $list = User::where(function ($query) use ($keyword) {
                return $query->where('id', $keyword)
                    ->orWhere('mobile', $keyword)
                    ->orWhere('nickname', 'like', "%{$keyword}%");
            })->where('id', '<>', data_get($user, 'id', 0))->whereNotIn('id', function ($query) {
                $user = auth()->guard('wap')
                    ->user();
                //TODO 这里需要考虑如果已经发起过请求的问题
                return $query->from('im_friends')
                    ->where('status', '<>', 1)
                    ->where('user_id', $user->id)
                    ->select([
                        'friend_user_id'
                    ]);
            })
                ->take(20)
                ->get();
            if ($list) {
                foreach ($list as $k => $u) {
                    $list[$k]['online'] = Cache::get('online:' . data_get($u, 'name')) === 1 ? 1 : 0;
                    $list[$k]['name'] = substr_replace($u['name'], '****', 3, 7);
                    $list[$k]['mobile'] = substr_replace($u['mobile'], '****', 3, 7);
                }
            }
            $data = [
                'list' => $list
            ] ;
            return response()->json([
                'errcode' => 0 ,
                'html' => view('im.searchitem' , $data )->render()
            ]);
        }
        return view('im.search');
    }


    public function addFriendShow( $id ) {
        $user = auth()->guard('wap')->user();
        $friend = ImFriend::with('friend')->where('friend_user_id' , $user->id )->where('id' , $id )->first();

        if( empty( $friend ) ) {
            return back()->with('error' , '没有好友添加请求');
        }

        if ($friend->status == 1) {
            return redirect()->to( route('wap.im.chat' , ['id' => $friend->user_id ]) );
            return back()->with('error' , '对方已经是您的好友了' );
        }

        return view('im.addfriend' , ['data' => $friend ]) ;

    }

    public function addFriend($id)
    {
        $user = auth()->guard('wap')->user();
        if( $id == $user->id ) {
        	return response()->json([
        			'errcode' => 10001,
        			'msg' => '不能加自己为好友'
        	]);
        }
        $friend = ImFriend::where('friend_user_id', $id)->where('user_id', $user->id)->first();
        if ($friend) {
            if ($friend->status == 1) {
                return response()->json([
                    'errcode' => 10001,
                    'msg' => '对方已经是您的好友了'
                ]);
            }
            /**
            if ($friend->status == 2) {
                return response()->json([
                    'errcode' => 10001,
                    'msg' => '对方已经在您的黑名单中'
                ]);
            }
            **/
        }
        $friend = User::find($id);
        if (empty($friend)) {
            return response()->json([
                'errcode' => 10002,
                'msg' => '您添加的好友不存在'
            ]);
        }
        // 检查对方的黑名单
        $hisFriend = ImFriend::where('user_id', $id)->where('friend_user_id', $user->id)->first();
        if ($hisFriend) {
            if ($hisFriend->status == 1 ) {
                return response()->json([
                    'errcode' => 10001,
                    'msg' => '对方已经是您的好友了'
                ]);
            }
            if ($hisFriend->status == 2) {
                return response()->json([
                    'errcode' => 10001,
                    'msg' => '您在对方的黑名单中'
                ]);
            }
        }
        // 如果都不是 则加一次好友
        $imFriend = ImFriend::firstOrCreate([
            'user_id' => $user->id,
            'friend_user_id' => $id
        ], [
            'status' => 0
        ]);
        if ($imFriend) {
        	if( $imFriend->status != 1 ) {
        		$imFriend->status = 0 ;
        		$imFriend->save();
        	}
            // 加好友信息成功 发送好友申请请求
            $result = $this->easemob->sendText("admin", "users", [
                $friend->name
            ], $user->nickname . "请求加您为好友，是否同意?", [
                'action' => 'addfriend',
                'from_user_name' => $user->nickname,
                'from_user_avatar' => $user->avatar,
                'event_id' => $imFriend->id,
                'event_info' => $imFriend->toArray()
            
            ]);
            //把系统消息  写到聊天记录上去
            $log = MessageLog::create([
            		'sender_id' => '0',
            		'receiver_id' => $id ,
            		'msg_id' => $imFriend->id ,
            		'body' => [
            			'data' => $user->nickname . "请求加您为好友，是否同意?"
            		],
            		'type' => 'addfriend',
            		'send_time' => \Carbon\Carbon::now()->getTimestamp()
            ]);
            $helper = new RecentChatHelper();
            $helper->set( $log );
            return response()->json([
                'errcode' => 0,
                'data' => $result,
                'msg' => '申请发送成功,等待对方同意'
            ]);
        }
        return response()->json([
            'errcode' => 10005,
            'msg' => '申请发送失败'
        ]);
		//$result = $this->easemob->addFriend( $user->name , $friend->name ) ;
    }


    public function chat( $id ) {
        $user = auth()->guard('wap')->user();
        $friend = ImFriend::where('user_id' , $user->id )->where('friend_user_id' , $id )->where('status' , 1 )->first();
        if( empty( $friend ) ) {
            return back()->with('error' , '他还不是您的好友') ;
        }
        $min = config('BIG_SMALL_MIN_POINT');
        $max = config('BIG_SMALL_MAX_POINT');

        $data = [
            'min' => $min ,
            'max' => $max ,
            'user' => $user ,
            'friend' => $friend
        ] ;
        return view('im.chat' , $data );
    }

    public function game( $id , Request $request  ) {
        $message = MessageLog::find( $id ) ;
        if( empty( $message ) ) {
            return back();
        }
        $user = auth()->guard('wap')->user();
        $url = '';
        switch( $message->type ) {
            case 'addfriend' :
                $url = route('wap.im.addfriend' , [ 'id' => $message->msg_id ]) ;
                break ;
            case 'txt' :
            case 'image' :
                $url = route('wap.im.chat' , ['id' => $user->id == $message->sender_id ? $message->receiver_id : $message->sender_id  ]) ;
                break ;
            case 'invite_bigsmall':
                $url = route('wap.game.show' , [ 'id' => $message->msg_id ]) ;
                break ;
        }
        //$url = route('wap.im.chat' , ['id' => $user->id == $message->sender_id ? $message->receiver_id : $message->sender_id  ]) ;
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'url' => $url
            ]);
        }
        return redirect()->to( $url );
    }

    /**
     * 发送消息
     */
    public function sendTextMsg($id, Request $request)
    {
        $text = $request->input('msg');
        if (! $text) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '发送的内容不能为空'
            ]);
        }
        $sender = auth()->guard('wap')->user();
        $receiver = User::find($id);
        $result = $this->easemob->sendText($sender->name, "users", [
            $receiver->name
        ], $text, [
            'action' => 'msg'
        ]);
        return response()->json([
            'errcode' => 0,
            'data' => $result
        ]);
    }

    /**
     * 检查用户是否在线
     * 
     * @param Reuqest $request
     */
    public function checkOnline(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);
        $result = $this->easemob->isOnline($user->name);
        return response()->json([
            'errcode' => 0,
            'online' => data_get(data_get($result, 'data'), $user->name)
        ]);
    }

    /**
     * 记录消息记录
     */
    public function record(Request $request)
    {
        $data = $request->input('data');
        $toUserName = data_get($data, 'to');
        $toUser = User::where('name', $toUserName)->first();
        $fromUserName = data_get($data, 'from');
        $fromUser = User::where('name', $fromUserName)->first();
        $msgId = data_get($data, 'id');
        $type = data_get($data, 'type');
        $log = MessageLog::create([
            'sender_id' => $fromUser->id,
            'receiver_id' => $toUser->id,
            'msg_id' => $msgId,
            'body' => $data,
            'type' => $type,
            'send_time' => \Carbon\Carbon::now()->getTimestamp()
        ]);
       	$helper = new RecentChatHelper();
       	$helper->set( $log );
       	
        return response()->json([
            'errcode' => 0,
            'data' => $log
        ]);
    }

    /**
     * 最近聊天
     */
    public function recentChat(Request $request)
    {
        $user = auth()->guard('wap')->user();
        $helper = new RecentChatHelper();
        $lists = $helper->get( $user->id );
        $friends = MessageLog::with([
            'sender',
            'receiver'
        ])->whereIn( 'id' , $lists )
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $data = [
            'list' => $friends ,
            'foot' => 'friends' ,
            'user' => $user ,
        ] ;
        return view('im.recent' , $data );
    }

    public function history(Request $request, $id)
    {
        $user = auth()->guard('wap')->user();
        $logs = MessageLog::with([
            'sender',
            'receiver'
        ])->where(function ($query) use ($user, $id) {
            
            return $query->where('receiver_id', $user->id)
                ->where('sender_id', $id);
        })
            ->orWhere(function ($query) use ($user, $id) {
            return $query->where('receiver_id', $id)
                ->where('sender_id', $user->id);
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json([
            'errcode' => 0,
            'data' => $logs,
            'id' => $id
        ]);
    }
    
    public function agreeFriend( Request $request ) {
    	$id = $request->input('id');
    	if( !$id ) {
    		return response()->json([
    				'errcode' => 10001 ,
    				'msg' => '参数错误'
    		]);
    	}
    	$user = auth()->guard('wap')->user();
    	$friendLog = ImFriend::findOrFail( $id ) ;
    	if( $friendLog->friend_user_id != $user->id ) {
    		return response()->json([
    				'errcode' => 10002 ,
    				'msg' => '没有权限'
    		]);
    	}
    	//$hisFriend = ImFriend::where('user_id', $id)->where('friend_user_id', $user->id)->first();
    	if ( ImFriend::where('id' , $id )->update(['status' => 1 ]) ) {
    	    //同时添加对方为好友
    		$imFriend = ImFriend::firstOrCreate([
    				'user_id' => $friendLog->friend_user_id,
    				'friend_user_id' => $friendLog->user_id
    		], [
    				'status' => 1
    		]);
    		if( $imFriend->status != 1 ) {
    			$imFriend->status = 0 ;
    			$imFriend->save();
    		}
    		
    		$friend = User::find( $friendLog->user_id );
    		$result = $this->easemob->sendText( $user->name , "users", [
    				$friend->name
    		], "我们已经是好友了，快来跟我聊天吧!", [
    				'action' => 'txt',
    				'from_user_name' => $user->nickname,
    				'from_user_avatar' => $user->avatar,
    		]);
    		
    		//把系统消息  写到聊天记录上去
    		$log = MessageLog::create([
    				'sender_id' => $user->id ,
    				'receiver_id' => $friend->id ,
    				'msg_id' => data_get( $result , 'id' , 0 ) ,
    				'body' => [
    						'data' => "我们已经是好友了，快来跟我聊天吧!"
    				],
    				'type' => 'txt',
    				'send_time' => \Carbon\Carbon::now()->getTimestamp()
    		]);
    		$helper = new RecentChatHelper();
    		$helper->set( $log );
    		//TODO 删除这条消息记录
    		MessageLog::where('msg_id' , $id )->where('type' , 'addfriend')->delete();
    		return response()->json([
    				'errcode' => 0,
    				'url' => route('wap.im.chat' , ['id' => $friendLog->user_id ]) ,
    				'msg' => '同意添加好友完成'
    		]);
    	}
    	return response()->json([
    			'errcode' => 10003 ,
    			'msg' => '添加失败'
    	]);
    }
    
    public function disagreeFriend(  Request $request  ) {
    	$id = $request->input('id');
    	if( !$id ) {
    		return response()->json([
    				'errcode' => 10001 ,
    				'msg' => '参数错误'
    		]);
    	}
    	$user = auth()->guard('wap')->user();
    	$friendLog = ImFriend::findOrFail( $id ) ;
    	if( $friendLog->user_id != $user->id ) {
    		return response()->json([
    				'errcode' => 10002 ,
    				'msg' => '没有权限'
    		]);
    	}
    	$hisFriend = ImFriend::where('user_id', $id)->where('friend_user_id', $user->id)->first();
    	if ( ImFriend::where('id' , $id )->delete() ) {
    		$friend = User::find( $friendLog->user_id );
    		$result = $this->easemob->sendText( "admin", "users", [
    				$friend->name
    		],  $user->nickname . "拒绝了您的好友请求!", [
    				'action' => 'txt',
    				'from_user_name' => $user->nickname,
	                'from_user_avatar' => $user->avatar,
	                'event_id' => $friendLog->id,
	                'event_info' => $friendLog->toArray()
    		]);
    		//把系统消息  写到聊天记录上去
    		$log = MessageLog::create([
    				'sender_id' => 0 ,
    				'receiver_id' => $friend->id ,
    				'msg_id' => data_get( $result , 'id' , 0 ) ,
    				'body' => [
    						'data' => $user->nickname . "拒绝了您的好友请求!"
    				],
    				'type' => 'txt',
    				'send_time' => \Carbon\Carbon::now()->getTimestamp()
    		]);
    		$helper = new RecentChatHelper();
    		$helper->set( $log );
    		//TODO 删除这条消息记录
    		MessageLog::where('msg_id' , $id )->where('type' , 'addfriend')->delete();
    		return response()->json([
    				'errcode' => 0,
    				'data' => $result,
    				'msg' => '拒绝添加好友完成'
    		]);
    	}
    	return response()->json([
    			'errcode' => 10003 ,
    			'msg' => '拒绝失败'
    	]);
    }
}