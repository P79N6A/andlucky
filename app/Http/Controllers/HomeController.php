<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Sherrycin\Cms\Models\Advertisement;
use Illuminate\Http\Request;
use Stevenyangecho\UEditor\Uploader\UploadFile;
use Illuminate\Support\Str;
use Illuminate\Cache\RateLimiter;
use App\Support\SSms;
use App\Models\AdvPosts;
use App\Models\UserCash;
use DB;
use Cache;
use App\User;
use App\Models\ClickLogs;
use App\Models\UserReward;
use Sherrycin\Cms\Models\Posts;
use Sherrycin\Cms\Models\Single;
use Sherrycin\Cms\Models\Feedback;
use App\Models\PraiseLog;
use App\Models\Microblog;
use App\Models\ChargeLog;
use App\Models\MicroblogCate;

class HomeController extends Controller
{

    public function home( Request $request ) {
        $select = $request->input('cate_id' );
        if( !$select ) {
            $current = MicroblogCate::where('display' , 1 )->orderBy('sort' , 'asc' )->first();
            $select = $current->id ;
        }
        $cate = MicroblogCate::where('display' , 1 )->orderBy('sort' , 'asc' )->select('title' , 'id' )->get();

        $query = Microblog::withCount([
            'praises'
        ])->with('user');
        $query = $query->where('cate_id', $select )->where('auth_status' , 1 );
        $blogs = $query->orderBy('id', 'desc')->paginate(10);
        $index = 0 ;
        foreach( $cate as $k => $val ) {
            if( $val->id == $select ) {
                $index = $k ;
                break ;
            }
        }
        $data = [
            'cate' => $cate ,
            'select' => $select ,
            'list' => $blogs ,
            'index' => $index ,
            'foot' => 'microblog'
        ];
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'data' => view('home.index-item' , $data )->render()
            ]) ;
        }
        return view('home.index' , $data );
    }


    public function home2( Request $request ) {

    	if( !\Cookie::get('laravel_session') ) {
    		$request->session()->regenerate();
    	}
    	$user = auth()->guard('wap')->user();
    	if( $user ) {
    		auth()->guard('wap')->login( $user , true );
    		//auth()->guard('wap')->loginById( $user->id );
    		$loginToken = $user->api_token ;
    		$cookie = \Cookie::forever( 'login_token' , $user->api_token , '/' , null , null , false );
    		\Cookie::queue( $cookie );
    	} else {
    		//没有登录的
    		$loginToken = \Cookie::get('login_token') ;
    		if( $loginToken ) {
    			//重新登录
    			$user = User::where('api_token' , $loginToken )->first();
    			if( $user ) {
    				auth()->guard('wap')->login( $user , true );
    				//auth()->guard('wap')->loginById( $user->id );
    			} else {
    				$cookie = \Cookie::forget( 'login_token');
    				\Cookie::queue( $cookie );
    				$loginToken = '' ;
    			}
    		}
    	}
        $cate = MicroblogCate::where('display' , 1 )->orderBy('sort' , 'asc' )->select('title' , 'id' )->get();
        return view('welcome' , ['login_token' => $loginToken , 'cate' => $cate ]);
    }

    public function index(Request $request)
    {
        // 获取可以显示的广告
        
        $adv = Advertisement::where('target_id', function ($query) {
            return $query->from('cms_adv_target')
                ->where('slug', 'WAP_INDEX_BANNER')
                ->select('id');
        })->where('display', 1)
            ->orderBy('sort', 'asc')
            ->get();
        
        $query = Microblog::withCount([
            'praises'
        ])->with('user');
        $cateId = request('cate_id', 1);
        $query = $query->where('cate_id', $cateId)->where('auth_status' , 1 );
        $blogs = $query->orderBy('id', 'desc')->paginate(10);
        $data = [
            'adv' => $adv,
            'blogs' => $blogs
        ];
        return response()->json([
            'errcode' => 0,
            'data' => $blogs,
        ]);
    
        
    }



    public function advShow($id)
    {
        $adv = AdvPosts::with('user')->where('display', 1)->where('pay_status', 1)->where('id' , $id )->first();
        return response()->json([
            'errcode' => 0 ,
            'data' => $adv 
        ]) ;
    }

    public function notices(Request $request)
    {
        $cateId = config('NOTICE_CATE_ID');
        $notices = Posts::where('category_id', $cateId)->orderBy('is_top', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data = [
            'list' => $notices
        ] ;
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0,
                'html' => view('home.noticeitem' , $data )->render(),
            ]);
        }

        return view('home.notice' , $data );

    }

    public function notice($id)
    {
        $notice = Posts::findOrFail($id);
        return response()->json([
            'errcode' => 0,
            'data' => $notice,
        ]);
    }

    /**
     * 发布
     */
    public function posts()
    {
        // 获取价格阶梯
        $price = config('CLICK_REWARD');
        $price = explode("\r\n", $price);
        $minPupCash = config('MIN_PUP_CASH');
        $minPupCash = (int) $minPupCash;
        // 获取广告主题内容
        $cates = Category::where('display', 1)->orderBy('sort', 'asc')->pluck('title', 'id');
        
        $data = [
            'min_pup_cash' => $minPupCash,
            'price' => $price,
            'cates' => $cates
        ];
        return response()->json([
        		'errcode' => 0 ,
        		'data' => $data 
        ]);
    }

    public function postEdit($id)
    {
        $post = AdvPosts::findOrFail($id);
        $price = config('CLICK_REWARD');
        $price = explode("\r\n", $price);
        $minPupCash = config('MIN_PUP_CASH');
        $minPupCash = (int) $minPupCash;
        // 获取广告主题内容
        $cates = Category::where('display', 1)->orderBy('sort', 'asc')->pluck('title', 'id');
        
        $data = [
            'min_pup_cash' => $minPupCash,
            'price' => $price,
            'cates' => $cates,
            'post' => $post
        ];
        return view('home.post_edit', $data);
    }

    public function postUpdate($id, Request $request)
    {
        $topic = $request->input('topic');
        $cate = Category::where('display', 1)->where('title', $topic)->first();
        if (! $cate) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '您选择的广告分类没找到哦'
            ]);
        }
        $user = auth()->guard('wap')->user();
        $post = AdvPosts::findOrFail($id);
        $post->title = $request->input('title');
        $post->user_id = $user->id;
        $post->category_id = $cate->id;
        $post->click_price = $request->input('price');
        $post->last_days = $request->input('day');
        $post->cover = $request->input('cover');
        $post->posts_content = $request->input('desc');
        $post->url = $request->input('url');
        $post->display = 0;
        $post->start_hour = 0;
        $post->end_hour = 24;
        $post->has_click_times = 0;
        $post->click_times = (int) $request->input('click_times');
        $post->total_price = $post->click_times * $post->price;
        $post->down_times = 0;
        $post->up_times = 0;
        if ($post->save()) {
            // TODO 保存成功 去扣减用户的点数 并改为发布状态
            if ($user->cash_recharge > $post->total_price) {
                $result = DB::transaction(function () use ($user, $post) {
                    $row = User::where('id', $user->id)->where('cash_recharge', $user->cash_recharge)->update([
                        'cash_recharge' => DB::raw('cash_recharge - ' . $post->total_price)
                    ]);
                    if (! $row) {
                        throw new \Exception("用户余额扣减失败");
                    }
                    $cash = new UserCash();
                    $cash->user_id = $user->id;
                    $cash->event = "create_post";
                    $cash->target_id = $post->id;
                    $cash->target_desc = '发布广告:' . $post->title;
                    $cash->act = 'out';
                    $cash->cash = $post->total_price;
                    $cash->extra_cash = 0;
                    if (! $cash->save()) {
                        throw new \Exception("用户余额扣减记录写入失败");
                    }
                    $row = AdvPosts::where('id', $post->id)->where('pay_status', 0)->update([
                        'display' => 1,
                        'pay_status' => 1
                    
                    ]);
                    if (! $row) {
                        throw new \Exception("支付状态修改失败");
                    }
                    return true;
                }, 3);
                if (true === $result) {
                    return response()->json([
                        'errcode' => 0,
                        'msg' => '发布完成，为您跳到广告页',
                        'url' => route('wap.post', [
                            'id' => $post->id
                        ])
                    ]);
                } else {
                    return response()->json([
                        'errcode' => 10003,
                        'msg' => '支付异常'
                    ]);
                }
            } else {
                return response()->json([
                    'errcode' => 0,
                    'url' => route('wap.charge'),
                    'msg' => '您的余额不足，请选充值'
                ]);
            }
        }
        return response()->json([
            'errcode' => 10002,
            'msg' => '发布失败'
        ]);
    }

    public function storePosts(Request $request)
    {
        $topic = $request->input('topic');
        $cate = Category::where('display', 1)->where('title', $topic)->first();
        if (! $cate) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '您选择的广告分类没找到哦'
            ]);
        }
        $user = auth()->guard('wap')->user();
        $post = new AdvPosts();
        $post->title = $request->input('title');
        $post->user_id = $user->id;
        $post->category_id = $cate->id;
        $post->click_price = $request->input('price');
        $post->last_days = $request->input('day');
        $post->cover = $request->input('cover');
        $post->posts_content = $request->input('desc');
        $post->url = $request->input('url');
        $post->display = 0;
        $post->start_hour = 0;
        $post->end_hour = 24;
        $post->has_click_times = 0;
        $post->click_times = (int) $request->input('click_times');
        $post->total_price = $post->click_times * $post->price;
        $post->down_times = 0;
        $post->up_times = 0;
        if ($post->save()) {
            // TODO 保存成功 去扣减用户的点数 并改为发布状态
            if ($user->cash_recharge > $post->total_price) {
                $result = DB::transaction(function () use ($user, $post) {
                    $row = User::where('id', $user->id)->where('cash_recharge', $user->cash_recharge)->update([
                        'cash_recharge' => DB::raw('cash_recharge - ' . $post->total_price)
                    ]);
                    if (! $row) {
                        throw new \Exception("用户余额扣减失败");
                    }
                    $cash = new UserCash();
                    $cash->user_id = $user->id;
                    $cash->event = "create_post";
                    $cash->target_id = $post->id;
                    $cash->target_desc = '发布广告:' . $post->title;
                    $cash->act = 'out';
                    $cash->cash = $post->total_price;
                    $cash->extra_cash = 0;
                    if (! $cash->save()) {
                        throw new \Exception("用户余额扣减记录写入失败");
                    }
                    $row = AdvPosts::where('id', $post->id)->where('pay_status', 0)->update([
                        'display' => 1,
                        'pay_status' => 1
                    
                    ]);
                    if (! $row) {
                        throw new \Exception("支付状态修改失败");
                    }
                    return true;
                }, 3);
                if (true === $result) {
                    return response()->json([
                        'errcode' => 0,
                        'msg' => '发布完成，为您跳到广告页',
                        'url' => route('wap.post', [
                            'id' => $post->id
                        ])
                    ]);
                } else {
                    return response()->json([
                        'errcode' => 10003,
                        'msg' => '支付异常'
                    ]);
                }
            } else {
                return response()->json([
                    'errcode' => 0,
                    'url' => route('wap.charge'),
                    'msg' => '您的余额不足，请选充值'
                ]);
            }
        }
        return response()->json([
            'errcode' => 10002,
            'msg' => '发布失败'
        ]);
    }

    /**
     * 发送验证码
     */
    public function sendVerifyCode(Request $request)
    {
        $mobile = $request->input('phone');
        if (! $mobile) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '参数不正确'
            ]);
        }
        $count = User::where('mobile', $mobile)->count();
        if ($count) {
            return response()->json([
                'errcode' => 10002,
                'msg' => '手机号已经存在'
            ]);
        }
        $key = "verify_" . $mobile . ":reg";
        $verify = Cache::get($key);
        if ($verify) {
            return response()->json([
                'errcode' => 10002,
                'msg' => '你发送的太快'
            ]);
        }
        $random = rand(100000, 999999);
        $conf = config('global.sms');
        $appId = $conf['sms_app_id'];
        $appKey = $conf['sms_app_key'];
        $appSign = $conf['sms_sign'];
        $sms = new SSms($appId, $appKey, $appSign);
        $tpl = data_get($conf, 'sms_tpl_no_verify');
        $param = [
            'code' => "{$random}"
        ];
        $result = $sms->sendSms($tpl, $mobile, $param);
        if ($result) {
            Cache::put($key, $random, 3);
            return response()->json([
                'errcode' => 0,
                'random' => $random,
                'left' => 180,
                'msg' => '发送成功',
                'debug' => var_export($result, true)
            ]);
        }
        return response()->json([
            'errcode' => 10001,
            'left' => 180,
            'msg' => '发送失败'
        ]);
    }

    public function sendFindPwdCode(Request $request)
    {
        $mobile = $request->input('phone');
        if (! $mobile) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '参数不正确'
            ]);
        }
        $count = User::where('mobile', $mobile)->count();
        if (! $count) {
            return response()->json([
                'errcode' => 10002,
                'msg' => '手机号不存在'
            ]);
        }
        $key = "verify_" . $mobile . ":resetpass";
        $verify = Cache::get($key);
        if ($verify) {
            return response()->json([
                'errcode' => 10002,
                'msg' => '你发送的太快'
            ]);
        }
        $random = rand(100000, 999999);
        $conf = config('global.sms');
        $appId = $conf['sms_app_id'];
        $appKey = $conf['sms_app_key'];
        $appSign = $conf['sms_sign'];
        $sms = new SSms($appId, $appKey, $appSign);
        $tpl = data_get($conf, 'sms_tpl_no_verify');
        $param = [
            'code' => "{$random}"
        ];
        $result = $sms->sendSms($tpl, $mobile, $param);
        if ($result) {
            Cache::put($key, $random, 3);
            return response()->json([
                'errcode' => 0,
                'random' => $random,
                'left' => 180,
                'msg' => '发送成功',
                'debug' => var_export($result, true)
            ]);
        }
        return response()->json([
            'errcode' => 10001,
            'left' => 180,
            'msg' => '发送失败'
        ]);
    }

    public function upload(Request $request)
    {
        $config = config('UEditorUpload.upload');
        $upConfig = array(
            "pathFormat" => $config['imagePathFormat'],
            "maxSize" => $config['imageMaxSize'],
            "allowFiles" => $config['imageAllowFiles'],
            'fieldName' => 'file'
        );
        $result = with(new UploadFile($upConfig, $request))->upload();
        if ('SUCCESS' == data_get($result, 'state')) {
            return response()->json([
                'errcode' => 0,
                'data' => data_get($result, 'url'),
                'msg' => '上传完成'
            ]);
        }
        return response()->json([
            'errcode' => 10002,
        	'msg' => data_get( $result , 'state')
        ]);
    }

    /**
     * 广告详情
     * 
     * @param unknown $id
     */
    public function post($id)
    {
        $adv = AdvPosts::findOrFail($id);
        $minSecond = (int) config("ADV_MIN_SECONDS");
        // 如果用户登录了
        $count = 0;
        if (auth()->guard('wap')->check()) {
            $user = auth()->guard('wap')->user();
            $count = ClickLogs::where('user_id', $user->id)->where('post_id', $adv->id)->count();
        }
        if ($count) {
            $count = AdvPosts::where('id', $id)->whereRaw('has_click_times >= click_times')->count();
        }
        $data = [
            'adv' => $adv,
            'min_second' => $minSecond,
            'count' => $count
        ];
        return view('home.post', $data);
    }


    public function aboutus()
    {
        $id = config('ABOUT_US');
        $id = $id ? (int) $id : 5;
        $page = Single::findOrFail($id);
        return view('home.single' , ['page' => $page ]);
    }

    public function help()
    {
        $id = config('HELP');
        $id = $id ? (int) $id : 5;
        $page = Single::findOrFail($id);
        return view('home.single' , ['page' => $page ]);
    }

    public function feedback() {
        $user = auth()->guard('wap')->user();
        return view('home.feedback' , ['user' => $user ]) ;
    }

    public function feedbackStore(Request $request)
    {
        $user = auth()->guard('wap')->user();
        $userId = data_get($user, 'id', 0);
        $mobile = $request->input('mobile');
        $content = $request->input('content');
        if (! $mobile) {
            return response()->json([
                'errorcode' => 10001,
                'msg' => '请填写手机号码'
            ]);
        }
        if (! $content) {
            return response()->json([
                'errorcode' => 10001,
                'msg' => '请填写反馈内容'
            ]);
        }
        $feedback = new Feedback();
        $feedback->user_id = $userId;
        $feedback->mobile = $mobile;
        $feedback->content = $content;
        if ($feedback->save()) {
            return response()->json([
                'errcode' => 0,
                'msg' => '反馈成功'
            ]);
        }
        return response()->json([
            'errcode' => 10002,
            'msg' => '反馈失败'
        ]);
    }
   
    /**
     * 支付回调通知
     */
   public function notify() {
   		$payment = \EasyAlipay::wap_payment();
   		$post = request()->all() ;
   		\Log::info( $post );
   		$result = $payment->check( $post )  ;
   		\Log::info( var_export( $result , true ) ) ;
   		if( $result ) {
   			$out_trade_no = data_get( $post , 'out_trade_no');
   			$tradeNo = data_get( $post , 'trade_no') ;
   			$tradeStatus = data_get( $post , 'trade_status' );
   			$totalAmount = data_get( $post , 'total_amount' , 0 );
   			if( 'TRADE_FINISHED' == $tradeStatus ) {
   				//交易关闭事件
   				
   			} else if( 'TRADE_SUCCESS' == $tradeStatus ) {
   				//支付成功事件
   				$log = ChargeLog::where('out_trade_no' , $out_trade_no )->where('status' , 'wait')->where('charge' , $totalAmount )->first();
   				if( $log ) {
   					DB::beginTransaction();
   					try {
   						$row = ChargeLog::where('out_trade_no' , $out_trade_no )->where('status' , 'wait')->where('charge' , $totalAmount )
   						->update([
   								'status' => 'success' ,
   								'notice_time' => time() ,
   								'trade_no' => $tradeNo
   						]);
   						if( !$row ) {
   							throw new \Exception("充值单状态更新错误");
   						}
   						$coinRate = config('RATE' , 1 );
   						$row = \DB::table('users')->where('id', $log->user_id)
   						->increment('cash_reward', $totalAmount * $coinRate );
   						if (! $row) {
   							throw new \Exception('余额更新出错');
   						}
   						$cash = \App\Models\UserCash::create([
   								'user_id' => $log->user_id ,
   								'event' => 'charge',
   								'target_id' => $log->id,
   								'target_desc' => '充值',
   								'act' => 'in',
   								'cash' => $totalAmount * $coinRate ,
   								'extra_cash' => 0
   						]);
   						if (empty($cash)) {
   							throw new \Exception('铜板流水写入错误');
   						}
   						$spreator = config('BECOME_SPREATOR_LOWEST_CHARGE' , 500 );
   						if( $totalAmount >= $spreator ) {
   							$user = User::where('id' , $log->user_id )->where('invite_code' , '' )->sharedLock()->first();
   							if( $user ) {
   								$row = User::where('id' , $log->user_id )->where('invite_code' , '' )->update([
   										'invite_code' => str_random( 8 ) 
								] );
								if (! $row) {
									throw new \Exception ( '推广者权限更新出错');
   								}
   							}
   						}
   						
   						DB::commit();
   						return 'success' ;
   					} catch( \Exception $e ) {
   						DB::rollback();
   						\Log::info( $e->getMessage() );
   						return 'fail' ;
   					}
   				} else {
   					\Log::info( "not find charget log" );
   					return 'fail' ;
   				}
   			}
   		}
   		//验签失败
   		return 'fail' ;
   }
   
   
   public function ret() {
   	$payment = \EasyAlipay::wap_payment();
   	$post = request()->all() ;
   	\Log::info( $post );
   	$result = $payment->check( $post )  ;
   	\Log::info( var_export( $result , true ) ) ;
   	if( $result ) {
   		$out_trade_no = data_get( $post , 'out_trade_no');
   		$tradeNo = data_get( $post , 'trade_no') ;
   		$tradeStatus = data_get( $post , 'trade_status' );
   		$totalAmount = data_get( $post , 'total_amount' , 0 );
   		if( 'TRADE_FINISHED' == $tradeStatus ) {
   			//交易关闭事件
   				
   		} else if( 'TRADE_SUCCESS' == $tradeStatus ) {
   			//支付成功事件
   			$log = ChargeLog::where('out_trade_no' , $out_trade_no )->where('status' , 'wait')->where('charge' , $totalAmount )->first();
   			if( $log ) {
   				DB::beginTransaction();
   				try {
   					$row = ChargeLog::where('out_trade_no' , $out_trade_no )->where('status' , 'wait')->where('charge' , $totalAmount )
   					->update([
   							'status' => 'success' ,
   							'notice_time' => time() ,
   							'trade_no' => $tradeNo
   					]);
   					if( !$row ) {
   						throw new \Exception("充值单状态更新错误");
   					}
   					$coinRate = config('RATE' , 1 );
   					$row = \DB::table('users')->where('id', $log->user_id)
   					->increment('cash_reward', $totalAmount * $coinRate );
   					if (! $row) {
   						throw new \Exception('余额更新出错');
   					}
   					$cash = \App\Models\UserCash::create([
   							'user_id' => $log->user_id ,
   							'event' => 'charge',
   							'target_id' => $log->id,
   							'target_desc' => '充值',
   							'act' => 'in',
   							'cash' => $totalAmount * $coinRate ,
   							'extra_cash' => 0
   					]);
   					if (empty($cash)) {
   						throw new \Exception('铜板流水写入错误');
   					}
   					$spreator = config('BECOME_SPREATOR_LOWEST_CHARGE' , 500 );
   					if( $totalAmount >= $spreator ) {
   						$user = User::where('id' , $log->user_id )->where('invite_code' , '' )->sharedLock()->first();
   						if( $user ) {
   							$row = User::where('id' , $log->user_id )->where('invite_code' , '' )->update([
   									'invite_code' => str_random( 8 )
   							]);
   							if (! $row) {
   								throw new \Exception('推广者权限更新出错');
   							}
   						}
   					}
   					DB::commit();
   					return redirect('/charges') ;
   				} catch( \Exception $e ) {
   					DB::rollback();
   					\Log::info( $e->getMessage() );
                    return redirect('/charges') ;
   				}
   			} else {
   				\Log::info( "not find charget log" );
                return redirect('/charges') ;
   			}
   		}
   	}
   	//验签失败
   	return redirect('/') ;
   }
}