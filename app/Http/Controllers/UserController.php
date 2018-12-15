<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Cache;
use App\Models\Career;
use App\Models\Degree;
use Illuminate\Support\Facades\Storage;
use Sherrycin\Cms\Models\Posts;
use App\Models\AdvPosts;
use App\Models\UserCash;
use App\Models\ClickLogs;
use App\Models\UserReward;
use App\Models\Message;
use Illuminate\Support\Carbon;
use EasyAlipay\AlipayTrade\ContentBuilder\AlipayTradeWapPayContentBuilder;
use App\Ext\Easemob;
use App\Models\Microblog;
use App\Models\Withdraw ;
use App\Models\ChargeLog;

class UserController extends Controller
{

    public function login() {
        if( auth()->guard('wap')->check() ) {
            return redirect()->to( session('_from' , '/')) ;
        }
        return view('member.login');
    }

    public function dologin(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'password' => $request->input('password')
        ];
        validator($data, [
            'name' => 'required',
            // 'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required'
        ], [
            'name.required' => '请填写用户名',
            'password.required' => '请填写用户密码'
        ]);
        $limiter = app(RateLimiter::class);
        $key = Str::lower($request->input('name')) . '|' . $request->ip();
        
        if ($limiter->tooManyAttempts($key, 5, 1)) {
            $seconds = $limiter->availableIn($key);
            return response()->json([
                'errcode' => 10002,
                'msg' => '登录尝试次数过多，请过' . $seconds . '秒后再试'
            ], 200);
        }
        if (auth()->guard('wap')->attempt($data, true)) {
            //登录成功
            $request->session()->regenerate();
            $user = auth()->guard('wap')->user();
            $cookie = \Cookie::forever( 'login_token' , $user->api_token , '/' , null , null , false );
            \Cookie::queue( $cookie );
            $limiter->clear($key);
            
            $user->last_login_ip = ip2long($request->ip());
            $user->last_login_time = time();
            $user->save();
            return response()->json([
                'errcode' => 0 ,
                'msg' => '登录成功',
                'url' => session('_from' , '/')
            ]);
        }
        $limiter->hit($key);
        return response()->json([
            'errcode' => 10001,
            'msg' => '手机号码或密码错误'
        ]);
    }

    public function logout(Request $request)
    {
    	$cookie = \Cookie::forget('login_token');
    	\Cookie::queue( $cookie );
    	auth()->guard('wap')->logout();
        $request->session()->invalidate();
        
        return redirect('/');
    }


    public function register( Request $request ) {

        if( auth()->guard('wap')->check() ) {
            return redirect()->to( session('_from' , '/')) ;
        }
        return view('member.register');
    }

    public function doregister(Request $request)
    {
        // 检查用户是不是已经注册了
        $mobile = $request->input('name');
        $passwd = $request->input('password');
        
        $data = $request->all();
        
        validator($data, [
            'name' => 'required|string|max:255|unique:users',
            // 'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);
        
        //检查用户手机是否存在
        if( User::where('name' , $mobile )->count() ) {
        	return response()->json([
        			'errcode' => 10002,
        			'msg' => '手机号已经注册'
        	]);
        }
        
        $code = $request->input('verify');
        $key = "verify_" . data_get($data, 'name') . ":reg";
        if (config('global.supper_verify') != $code) {
            $verify = Cache::get($key);
            if (! $verify) {
                return response()->json([
                    'errcode' => 10002,
                    'msg' => '验证码失效'
                ]);
            }
            if ($verify != $code) {
                return response()->json([
                    'errcode' => 10003,
                    'msg' => '验证码不正确'
                ]);
            }
        }
        $user = User::create([
            'name' => $data['name'],
            // 'email' => $data['email'],
            'nickname' => $data['nickname'],
            'mobile' => $data['name'],
            'invite_code' => '' ,
            'invite_by' => data_get($data, 'invite_by'),
            'password' => bcrypt($data['password']),
            'api_token' => str_random(60),
            'register_ip' => ip2long($request->ip()),
            'last_login_time' => time(),
            'last_login_ip' => ip2long($request->ip())
        ]);
        
        //TODO 赠送铜板
        $registerReward = (int) config('REGISTER_REWARD');
        if( $registerReward ) {
        	$row = \DB::table('users')->where('id', $user->id )
        	//->where('cash_reward', $user->cash_reward)
        	->increment('cash_reward', $registerReward );
        	if (!$row) {
        		throw new \Exception('分润者铜板更新出错');
        	}
        	$cashLog = \App\Models\UserCash::create([
        			'user_id' => $user->id ,
        			'event' => 'register',
        			'target_id' => $user->id ,
        			'target_desc' => '邀请注册',
        			'act' => 'in',
        			'cash' => $registerReward ,
        			'extra_cash' => 0
        	]);
        	if (empty($cashLog)) {
        		throw new \Exception('注册赠送铜板流水写入错误');
        	}
        }
        
        //这里可以进行积分赠送
        if( $user && $user->invite_by ) {
        	$reward = (int) config('INVITE_REWARD');
        	if( $reward > 0 ) {
        			$parent = User::where('invite_code' , $user->invite_by)->first();
        			if( $parent ) {
        				//写流水  和  写分润记录
        				$row = \DB::table('users')->where('id', $parent->id )
        				//->where('cash_reward', $user->cash_reward)
        				->increment('cash_reward', $reward );
        				if (!$row) {
        					throw new \Exception('分润者铜板更新出错');
        				}
        				$cashLog = \App\Models\UserCash::create([
        						'user_id' => $parent->id ,
        						'event' => 'refund',
        						'target_id' => $user->id ,
        						'target_desc' => '邀请注册',
        						'act' => 'in',
        						'cash' => $reward ,
        						'extra_cash' => 0
        				]);
        				if (empty($cashLog)) {
        					throw new \Exception('分润者铜板流水写入错误');
        				}
        			}
        	}
        }
        
        event(new \App\Events\UserRegister($user));
        // 注册环信用户
        $mob = new Easemob(config('global.easemob'));
        $mob->createUser($user->name, $user->api_token );
        auth()->guard('wap')->login($user , true );
        $cookie = \Cookie::forever( 'login_token' , $user->api_token , '/' , null , null , false );
        \Cookie::queue( $cookie );
        //$redirect = data_get($data, 'ret') ? data_get($data, 'ret') : route('wap.home');
        return response()->json([
            'errcode' => 0,
            'msg' => '注册成功',
            'url' => session('_from' , '/')
        ]);
    }

    public function findpwd( Request $request ) {

        return view('member.findpwd');
    }

    public function dofindpwd(Request $request)
    {
        $mobile = $request->input('name');
        $password = $request->input('password');
        $code = $request->input('verify');
        if (config('app.supper_verify') != $code) {
            $key = "verify_" . $mobile . ":resetpass";
            $verify = Cache::get($key);
            if ( !$verify) {
                return response()->json([
                    'errcode' => 10002,
                    'msg' => '验证码失效'
                ]);
            }
            if ($verify != $code) {
                return response()->json([
                    'errcode' => 10003,
                    'msg' => '验证码不正确'
                ]);
            }
        }
        
        $user = User::where('mobile', $mobile)->first();
        if (empty($user)) {
            return response()->json([
                'errcode' => 20001,
                'msg' => '手机号还没有注册'
            ]);
        }
        
        if ($user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60)
        ])->save()) {
        	$cookie = \Cookie::forever( 'login_token' , $user->api_token , '/' , null , null , false );
        	\Cookie::queue( $cookie );
            auth()->guard('wap')->loginUsingId($user->id , true );
            return response()->json([
                'errcode' => 0,
                'msg' => '修改成功' ,
                'url' => session('_from' , '/')
            ]);
        }
        return response()->json([
            'errcode' => 10004,
            'msg' => '修改失败'
        ]);
    }

    public function storefullinfo()
    {
        
    }

    
    public function invited ( Request $request ) {
        $user = auth()->guard('wap')->user();
        $invite = User::where('invite_by' , $user->invite_code )->orderBy('id' , 'desc' )
        ->select(['id' , 'nickname' , 'avatar'])->paginate( 10 );
        return response()->json([
            'errcode' => 0 ,
            'data' => $invite 
        ]); 
    }
    
    /**
     * 我的消息
     */
    public function messages(Request $request)
    {
        $user = auth()->guard('wap')->user();
        $list = Message::where('user_id', $user->id)->orderBy('read_at', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $data = [
            'list' => $list
        ] ;
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0,
                'html' => view('member.message' , $data )->render(),
            ]);
        }
        return view('member.message' , $data );

    }

    /**
     * 消息详情
     * 
     * @param unknown $id
     */
    public function messageShow($id)
    {
        $user = auth()->guard('wap')->user();
        $message = Message::where('id', $id)->where('user_id', $user->id)->first();
        if (empty($message)) {
            return back()->with('error' , '您没有本条消息') ;
        }
        if (! $message->read_at) {
            $message->read_at = Carbon::now();
            $message->save();
        }
        return view('member.messageshow' , ['data' => $message ]) ;
    }

    /**
     * 会员中心
     */
    public function index()
    {
        $user = auth()->guard('wap')->user();
        $count = Message::where('user_id', $user->id)->whereNull('read_at')->count();
        $microblogCount = Microblog::where('user_id' , $user->id )->count();
        $data = [
            'user' => $user,
            'msg_count' => $count ,
            'microblog_count' => $microblogCount ,
            'foot' => 'member'
        ];
        return view('member.index' , $data );
        return response()->json([
            'errcode' => 0 ,
            'data' => $data
        ]);
    }

    public function modpwd() {
        return view('member.modpwd' );
    }


    public function domodpwd(Request $request)
    {
        $oldPwd = $request->input('oldpwd');
        $newPwd = $request->input('password');
        $user = auth()->guard('wap')->user();
        
        if (! auth()->guard('wap')
            ->getProvider()
            ->getHasher()
            ->check($oldPwd, $user->getAuthPassword())) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '旧密码不正确'
            ]);
        }
        
        if ($user->forceFill([
            'password' => bcrypt($newPwd),
            'remember_token' => Str::random(60)
        ])->save()) {
            auth()->guard('wap')->loginUsingId($user->id);
            //这里可以更新用户的token
            $mob = new Easemob(config('global.easemob'));
            $mob->resetPassword($user->name, $user->api_token );
            return response()->json([
                'errcode' => 0,
                'msg' => '修改成功'
            ]);
        }
        return response()->json([
            'errcode' => 10004,
            'msg' => '修改失败'
        ]);
    }

    /**
     * 修改会员基本信息
     */
    public function baseinfo()
    {
        $minBaseEdit = config('MIN_BASEINFO_EDIT');
        
        $user = auth()->guard('wap')->user();
        $lastUpdate = $user->last_update;
        $editEnable = true;
        $limitTimer = time() - ($lastUpdate + $minBaseEdit * 86400);
        if ($limitTimer <= 0 && $user->last_update != 0) {
            $editEnable = false;
        }
        $data = [
            'edit_enable' => $editEnable,
            'limit_time' => abs($limitTimer),
            'user' => $user,
            'tags' => Career::where('display', 1)->orderBy('sort', 'asc')->pluck('title', 'id'),
            'degree' => Degree::where('display', 1)->orderBy('sort', 'asc')->pluck('title', 'id')
        ];
        return view('member.baseinfo' , $data );
    }

    /**
     * 修改基本信息
     */
    public function doModBaseInfo(Request $request)
    {
    	//ALTER TABLE `adm`.`users` CHANGE COLUMN `career_id` `tags` VARCHAR(255) NULL DEFAULT '0' ;

        $minBaseEdit = config('MIN_BASEINFO_EDIT');
        $user = auth()->guard('wap')->user();
        $lastUpdate = $user->last_update;
        $editEnable = true;
        $limitTimer = time() - ($lastUpdate + $minBaseEdit * 86400);
        if ($limitTimer >= 0 && $user->last_update > 0 && false ) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '您的基本资料修改过于频繁'
            ]);
        }
        $alipayAccount = $request->input('alipay_account');
        if( !$alipayAccount ) {
        	return response()->json([
        			'errcode' => 10001,
        			'msg' => '请填写支付宝收款账号'
        	]);
        }
        $count = User::where('id' , '<>' , $user->id )->where('alipay_account' , $alipayAccount )->count();
        if( $count ) {
        	return response()->json([
        			'errcode' => 10001,
        			'msg' => '支付宝账已经被占用'
        	]);
        }
        $updateTime = false;
        $nickname = $request->input('nickname');
        $user->nickname = $nickname ;
        $gender = $request->input('gender');
        $gender = $gender == '男' ? 1 : ($gender == '女' ? 2 : 0);
        if ($user->gender != $gender) {
            $updateTime = true;
            $user->gender = $gender;
        }
        $birthDay = $request->input('birth_day');
        if ($birthDay) {
            $birthDay = strtotime($birthDay . ' 00:00:00');
        } else {
            $birthDay = 0;
        }
        if ($user->birth_day != $birthDay) {
            $updateTime = true;
            $user->birth_day = $birthDay;
        }
        $city = $request->input('city');
        if ($user->city != $city) {
            $updateTime = true;
            $user->city = $city;
        }
        $isMarry = $request->input('is_marry');
        $degree = $request->input('degree');
        $degree = Degree::where('title', $degree)->first();
        $degreeId = 0;
        if ($degree) {
            $degreeId = $degree->id;
        }
        if ($user->degree != $degreeId) {
            $updateTime = true;
            $user->degree = $degreeId;
        }
        $tags = $request->input('tags');
        if ($user->tags != $tags) {
            $updateTime = true;
            $user->tags = $tags;
        }
        
        $alipay = $request->input('alipay_account');
        if ($user->alipay_account != $alipay) {
        	$updateTime = true;
        	$user->alipay_account = $alipay;
        }
        
        if ($updateTime) {
            $user->last_update = time();
        }
        if ($user->save()) {
            return response()->json([
                'errcode' => 0,
                'msg' => '修改成功'
            ]);
        }
        return response()->json([
            'errcode' => 10002,
            'msg' => '修改失败'
        ]);
    }

    /**
     * 修改昵称
     */
    public function info()
    {
        $user = auth()->guard('wap')->user();
        return view('user.info', [
            'user' => $user
        ]);
    }

    public function doModInfo(Request $request)
    {
        $nickname = $request->input('nickname');
        $user = auth()->guard('wap')->user();
        if (! $nickname) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '请填写昵称'
            ]);
        }
        if ($user->nickname != $nickname) {
            $user->nickname = $nickname;
            $user->save();
            return response()->json([
                'errcode' => 0,
                'msg' => '修改完成'
            ]);
        }
        return response()->json([
            'errcode' => 0,
            'msg' => '修改完成'
        ]);
    }

    public function avatar()
    {
        $user = auth()->guard('wap')->user();
        return view('member.avatar', [
            'user' => $user
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $user = auth()->guard('wap')->user();
        $base64 = $request->input('avatar');
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result);
        $type = $result[2];
        $avatar = str_replace($result[1], '', $base64);
        $avatarId = $user->id;
        $name = 'avatar/' . $avatarId . str_random( 8 ) . "." . $type;
        $result = Storage::disk('public')->put($name, base64_decode($avatar));
        if ($result) {
            $user->avatar = 'uploads/' . $name;
            if ($user->save()) {
                return response()->json([
                    'errcode' => 0,
                    'data' => $result,
                    'msg' => '上传完成'
                ]);
            } else {
                return response()->json([
                    'errcode' => 10002,
                    'msg' => '保存失败'
                ]);
            }
        }
        return response()->json([
            'errcode' => 10001,
            'msg' => '上传失败'
        ]);
    }

    public function posts(Request $request)
    {
        $user = auth()->guard('wap')->user();
        $query = AdvPosts::where("user_id", $user->id)->orderBy('id', 'desc');
        $status = $request->input('status', 'all');
        if ($status == 'notpay') {
            $query->where('pay_status', 0);
        } elseif ($status == 'haspay') {
            $query->where('pay_status', 1);
        }
        
        $posts = $query->paginate(10);
        $data = [
            'posts' => $posts
        ];
        if ($request->input('_only_') == 'json') {
            return response()->json([
                'errcode' => 0,
                'post' => $posts,
                'view' => view('user.post_item', $data)->render()
            ]);
        }
        return view('user.posts', $data);
    }

    /**
     * 支付
     */
    public function payPost($id, Request $request)
    {
        $post = AdvPosts::findOrFail($id);
        $user = auth()->guard('wap')->user();
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
                    'msg' => '支付完成',
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
                'msg' => '您的余额不足，请先充值'
            ]);
        }
    }

    /**
     * 我参与的活动
     */
    public function joinPosts(Request $request)
    {
        $user = auth()->guard('wap')->user();
        $log = ClickLogs::with('task')->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data = [
            'data' => $log
        ];
        if ($request->input('_only_')) {
            return response()->json([
                'errcode' => 0,
                'data' => $log,
                'view' => view('user.join_posts_item', $data)->render()
            ]);
        }
        return view('user.join_posts', $data);
    }

    /**
     * 我的充值记录
     */
    public function charges(Request $request)
    {
        $user = auth()->guard('wap')->user();
        $log = ChargeLog::where('user_id' , $user->id )->where('status' , 'success')->orderBy('id' , 'desc')->paginate( 10 );
        $data = [
            'list' => $log
        ];
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'html' =>  view('member.chargelogitem', $data)->render()
            ]);
        }
        return view('member.charge', $data);
    }

    /**
     * 充值
     */
    public function charge(Request $request)
    {
        $chargeRules = config('CHARGE_RULES' , '' );
        //$chargeRules = explode(PHP_EOL, $chargeRules);
        /**
        $rules = [];
        if( is_array( $chargeRules ) && !empty( $chargeRules ) ) {
	        foreach ($chargeRules as $v) {
	        	
	            list ($p, $k) = explode('|', $v);
	            list ($p1, $p2) = explode(',', $p);
	            if( !$p1 && $p2 ) {
	            	$rules[] = "充值小于 {$p2}送{$k}%";
	            } elseif( $p1 && !$p2 ) {
	            	$rules[] = "充值大于 {$p1}送{$k}%";
	            } else {
	            	if( $p1 == $p2 ) {
	            		$k = $p1 * $k / 100 ;
	            		$rules[] = "充值{$p1}送{$k}";
	            	} else {
	            		$rules[] = "充值大于 {$p1},小于大于 {$p2}送{$k}%";
	            	}
	            }
	        }
        }
        **/
        $data = [
            'desc' => $chargeRules
        ] ;
        return view('member.chargeform' , $data );
    }

    /**
     * 充值
     * 
     * @param Request $request
     */
    public function doCharge(Request $request)
    {
        $cash = $request->input('cash');
        $user = auth()->guard('wap')->user();
        // 生成充值单号
        $out_trade_no = str_pad( $user->id , 7 , STR_PAD_LEFT , '0' ) . str_random(9);
        ChargeLog::create([
        		'user_id' => $user->id ,
        		'out_trade_no' => $out_trade_no ,
        		'trade_no' => '' ,
        		'status' => 'wait' ,
        		'charge' => $cash 
        ]);
        $builder = new AlipayTradeWapPayContentBuilder();
        $builder->setBody('余额充值');
        $builder->setSubject('余额充值');
        $builder->setOutTradeNo($out_trade_no);
        $builder->setTotalAmount($cash);
        $builder->setTimeExpress(500);
        $payment = \EasyAlipay::wap_payment();
        $payConf = config('alipay.payment');
        $result = $payment->pay($builder , data_get( $payConf , 'return_url') , data_get( $payConf , 'notify_url') );
        return response()->json([
        		'errcode' => 0 ,
        		'url' => $result 
        ]);
    }
	
    
    public function cashlog(Request $request) {
    	$user = auth()->guard('wap')->user();
    	$log = UserCash::where('user_id' , $user->id )->orderBy('id' , 'desc')->paginate( 10 );
    	$data = [
            'list' => $log
        ];
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'html' =>  view('member.cashlogitem', $data )->render()
            ]);
        }
        return view('member.cashlog', $data);
    }


    public function withdrawlog(Request $request) {
    	$user = auth()->guard('wap')->user();
    	$log = Withdraw::where('user_id' , $user->id )->orderBy('id' , 'desc')->paginate( 10 );
    	$data = [
    	    'list' => $log
        ] ;
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'html' =>  view('member.withdrawlogitem', $data)->render()
            ]);
        }
        return view('member.withdrawlog', $data);
    }
    
    public function withdraw( Request $request ) {
    	$min = config('MIN_WITHDRAW' , 0 );
    	$rate = config('WITHDRAW_MAX_RATE' , 100 );
    	$user = auth()->guard('wap')->user();

    	$data = [
    	    'user' => $user ,
            'rate' => $rate ,
            'min' => $min ,
            'max' => number_format( $user->cash_reward * $rate / 100 , 2 , '.' , '' )
        ] ;
    	return view('member.withdrawform' , $data );
    }
    
    public function withdrawApply( Request $request ) {
    	$user = auth()->guard('wap')->user();
        if( !$user->alipay_account ) {
            return response()->json([
                'errcode' => 10006,
                'msg' => '您没有填写支付宝账号，请先去填写支付宝账号吧'
            ]);
        }
    	$cash = $request->input('cash');
    	if( !$cash ) {
    		return response()->json([
    				'errcode' => 10001 ,
    				'msg' => '参数错误'
    		]);
    	}
    	$min = config('MIN_WITHDRAW' , 0 );
    	$rate = config('WITHDRAW_MAX_RATE' , 100 );
    	$coinRate = config('RATE' , 1 );
    	if( $cash < $min ) {
    		return response()->json([
    				'errcode' => 10001 ,
    				'msg' => '提现铜板低于最小值'
    		]);
    	}
    	if( $cash > floor( $user->cash_reward * $rate / 100 ) ) {
    		return response()->json([
    				'errcode' => 10001 ,
    				'msg' => '提现铜板超出最大值'
    		]);
    	}
    	if( $cash > $user->cash_reward ) {
    		return response()->json([
    				'errcode' => 10001 ,
    				'msg' => '提现铜板超出最大值'
    		]);
    	}
    	if( $user->invite_code ) {
	    	$spreatorCount = config('MIN_SPREATOR_CASH' , 0 );
	    	if( $user->cash_reward - $cash <= $spreatorCount ) {
	    		return response()->json([
	    				'errcode' => 10001 ,
	    				'msg' => '由于您是推广人员需要保留最少'. $spreatorCount .'铜板'
	    		]);
	    	}
    	}
    	\DB::beginTransaction();
    	try {
    		$withdraw = Withdraw::create([
    				'user_id' => $user->id ,
    				'admin_id' => 0 ,
    				'status' => 0 ,
    				'used' => $cash ,
    				'rate' => $coinRate ,
    				'cash' => $cash / $coinRate ,
    				'bank_name' => 'alipay' ,
    				'bank_account' => $user->alipay_account 
    		]);
    		if( !$withdraw ) {
    			throw new \Exception("提现记录出错");
    		}
    		$row = \DB::table('users')->where('id', $user->id)
    		->where('cash_reward', $user->cash_reward)
    		->decrement('cash_reward', $cash);
    		if (! $row) {
    			throw new Exception('余额不足');
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
    			'data' => $withdraw,
    			'msg' => '提现申请成功等待后台审核'
    	]);
    	
    	
    }
}
