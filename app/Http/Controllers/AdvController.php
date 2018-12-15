<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MicroblogCate ;
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
use App\Models\PraiseLog;
use App\Models\ViewLog;
use App\Models\Career;

class AdvController extends Controller {


    public function index(Request $request)
    {
        $priceFilter = config('PRICE_FILTER');
        $priceFilter = explode("\r\n", $priceFilter);
        $filter = [];
        if (! empty(is_array($priceFilter) && $priceFilter)) {
            foreach ($priceFilter as $f) {
                list ($k, $v) = explode('|', $f);
                $filter[$k] = $v;
            }
        }
        //$cates = Category::where('display', 1)->orderBy('sort', 'asc')->pluck('title', 'id');
        $query = AdvPosts::with('user')->where('display', 1)
            ->where('pay_status', 1)
            ->whereNotIn('id' , function( $query ){
                $user = auth()->guard('wap')->user();
                return $query->from('adm_post_click_log')->where('user_id' , data_get( $user , 'id' ) )->select('post_id');
            })
            ->orderBy('sort', 'asc');
        $price = $request->input('price');
        if ($price) {
            $price = explode(',', $price);
            if (data_get($price, 0)) {
                $query = $query->where('click_price', '>=', data_get($price, 0));
            }
            if (data_get($price, 1)) {
                $query = $query->where('click_price', '<', data_get($price, 1));
            }
        }
        $order = $request->input('order');
        if ($order) {
            $orderBy = explode('_', $order);
            if (data_get($orderBy, 0) == 'price') {
                $query = $query->orderBy('click_price', data_get($orderBy, 1));
            } else {
                $query = $query->orderBy(data_get($orderBy, 0), data_get($orderBy, 1));
            }
        }
        $cate = $request->input('cate');
        if ($cate) {
            $query = $query->where('category_id', $cate);
        }


        $user = auth()->guard('wap')->user();
        if( $user ) {
            $tags = explode( ',' , $user->tags ) ;
            if( is_array( $tags ) && !empty( $tags ) ) {
                $where = [] ;
                foreach( $tags as $tag ) {
                    if( $tag ) {
                        $where[] = "tags like '%{$tag}%'" ;
                    }
                }
                if( count( $where ) ) {
                    $where = implode(' or ' , $where );
                    $query = $query->where( function( $query ) use( $where ) {
                        return $query->whereRaw( \DB::raw( $where ) );
                    }) ;
                }
            }

        }

        $post = $query->paginate(10)->appends([
            'price' => $request->input('price'),
            'order' => $order,
            'cate' => $cate
        ]);

        $microblogCate = MicroblogCate::where('display' , 1 )->orderBy('sort' , 'asc' )->select('title' , 'id' )->get();
        $index = count( $microblogCate );
        $data = [
            'list' => $post ,
            'cate' =>$microblogCate ,
            'foot' => 'microblog' ,
            'index' => $index ,
        ] ;
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'html' => view( 'adv.index-item' , $data )->render()
            ]) ;
        }
        return view('adv.index' , $data );
    }
	/**
	 * 创建的conf
	 */
	public function create() {
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
				'cates' => $cates ,
                'foot' => 'form' ,
				'tags' => Career::where('display', 1)->orderBy('sort', 'asc')->pluck('title', 'id') ,
                'adv' => new AdvPosts()
		];

		return view('adv.create' , $data );
	}
	
	/**
	 * 保存发布广告
	 */
	
	public function store( Request $request ) {
	    \Log::info( json_encode( $request->all() ) );
		$topic = $request->input('topic');
		$cate = Category::where('display', 1)->where('id', $topic)->first();
		if (! $cate && false ) {
			return response()->json([
					'errcode' => 10001,
					'msg' => '您选择的广告分类没找到哦'
			]);
		}
		$user = auth()->guard('wap')->user();
		$post = new AdvPosts();
		$post->title = $request->input('title');
		$post->user_id = $user->id;
		$post->category_id = data_get( $cate , 'id' , 0 );
		$post->click_price = $request->input('price');
		$post->last_days = $request->input('day');

        $base64 = $request->input('cover');
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result);
        $type = $result[2];
        $cover = str_replace($result[1], '', $base64);
        $name = 'adv/' . date('Ymdhis' ) . str_random( 5 ) . "." . $type;

        $result = \Storage::disk('public')->put($name, base64_decode( $cover ));
        if ($result) {
            $post->cover = 'uploads/' . $name;
        }
		//$post->cover = $request->input('cover');
		$post->posts_content = $request->input('desc');
		$post->url = $request->input('url');
		$post->display = 0;
		$post->tags = $request->input('tags') ;
		$post->start_hour = 0;
		$post->end_hour = 24;
		$post->has_click_times = 0;
		$post->click_times = (int) $request->input('click_times');
		$post->total_price = $post->click_times * $post->click_price;
		$post->down_times = 0;
		$post->up_times = 0;
		if ($post->save() ) {
			// TODO 保存成功 去扣减用户的点数 并改为发布状态
			if ($user->cash_reward > $post->total_price) {
				DB::beginTransaction();
				try {
					$row = User::where('id', $user->id)->where('cash_reward', $user->cash_reward)->update([
							'cash_reward' => DB::raw('cash_reward - ' . $post->total_price)
					]);
					if ( !$row) {
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
					if ( !$cash->save()) {
						throw new \Exception("用户余额扣减记录写入失败");
					}
					$row = AdvPosts::where('id', $post->id)->where('pay_status', 0)->update([
							'display' => 1,
							'pay_status' => 1
		
					]);
					if (! $row) {
						throw new \Exception("支付状态修改失败");
					}
					DB::commit();
					return response()->json([
							'errcode' => 0,
							'msg' => '发布完成，为您跳到广告页',
							'url' => route('wap.adv.show' , ['id' => $post->id , 'from' => 'form'])
					]);
				} catch( \Exception $e ) {
					DB::rollBack();
					return response()->json([
							'errcode' => 10003,
							'msg' => $e->getMessage()
					]);
				}
			} else {
				return response()->json([
						'errcode' => 30000,
						'adv' => $post ,
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
	 * 我发布的广告
	 */
	public function mine( Request $request ) {
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
		    'list' => $posts
        ] ;
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'html' => view('adv.mineitem' , $data )->render()
            ]);
        }
		return view('adv.mine' , $data );

	}
	
	/**
	 * 编辑我发布的广告
	 */
	public function edit( $id , Request $request ) {
		$user = auth()->guard('wap')->user();
		$post = AdvPosts::findOrFail($id);
		if( $post->user_id != $user->id ) {
		    return back()->with('error' , '您没有权限编辑') ;
		}
		if( $post->pay_status == 1 ) {
            return back()->with('error' , '该广告暂不能编辑') ;
        }
		$price = config('CLICK_REWARD');
		$price = explode("\r\n", $price);
		$minPupCash = config('MIN_PUP_CASH');
		$minPupCash = (int) $minPupCash;
		// 获取广告主题内容
		$cates = Category::where('display', 1)->orderBy('sort', 'asc')->pluck('title', 'id');

        $data = [
            'min_pup_cash' => $minPupCash,
            'price' => $price,
            'cates' => $cates ,
            'foot' => 'form' ,
            'tags' => Career::where('display', 1)->orderBy('sort', 'asc')->pluck('title', 'id') ,
            'adv' => $post
        ];

        return view('adv.create' , $data );
	}
	
	/**
	 * 广告更新
	 * @param unknown $id
	 * @param Request $request
	 * @throws \Exception
	 */
	public function update($id, Request $request)
	{
        \Log::info( json_encode( $request->all() ) );
		$topic = $request->input('topic');
		$cate = Category::where('display', 1)->where('id', $topic)->first();
		if (false && ! $cate) {
			return response()->json([
					'errcode' => 10001,
					'msg' => '您选择的广告分类没找到哦'
			]);
		}
		$user = auth()->guard('wap')->user();
		$post = AdvPosts::findOrFail($id);
		if( $post->user_id != $user->id ) {
			return response()->json([
					'errcode' => 10001,
					'msg' => '您没有权限'
			]);
		}
		$post->title = $request->input('title');
		$post->user_id = $user->id;
		$post->category_id = 0 ;
		$post->click_price = $request->input('price');
		$post->last_days = $request->input('day');

        $base64 = $request->input('cover');
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result);
        if( $result && isset( $result[2] ) ) {
            $type = $result[2];
            $cover = str_replace($result[1], '', $base64);
            $name = 'adv/' . date('Ymdhis' ) . str_random( 5 ) . "." . $type;

            $result = \Storage::disk('public')->put($name, base64_decode( $cover ));
            if ($result) {
                $post->cover = 'uploads/' . $name;
            }
        } elseif( $base64 ) {
            $post->cover = $base64 ;
        } else {
            $post->cover = '' ;
        }


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
								'msg' => '保存完成，为您跳到广告页',
								'url' => route('wap.adv.show', [
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
						'errcode' => 30000,
						'url' => route('member.chargeform'),
						'adv' => $post ,
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
	 * 支付
	 */
	public function pay($id, Request $request)
	{
		$post = AdvPosts::findOrFail($id);
		$user = auth()->guard('wap')->user();
		if( $post->user_id != $user->id ) {
			return response()->json([
					'errcode' => 10001 ,
					'msg' => '无权支付'
			]);
		}
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
					'errcode' => 30000 ,
					'msg' => '您的余额不足，请先充值'
			]);
		}
	}
	
	public function show($id) {
		$adv = AdvPosts::with('user')->where('display', 1)
            ->where('pay_status', 1)->where('id' , $id )->first();
		$user = auth()->guard('wap')->user();
		$clickCount = 0 ;
		$praiseCount = 0 ;
		if( $user ) {
			//获取是不是已经查阅过了
			$clickCount = ClickLogs::where('post_id' , $id )->where('user_id' , $user->id )->count();
			$praiseCount = PraiseLog::adv()->where('user_id' , $user->id )->count();
			//如果用户登录了  则查看是否有增加 过次数
			$viewCount = ViewLog::adv()->where('target_id' , $id )->where('user_id' , $user->id )->count();
			if( !$viewCount ) {
				//AdvPosts::where('id' , $id )->increments('')
				//增加添加次数
				ViewLog::create([
						'type' => 'adv' ,
						'user_id' => $user->id ,
						'target_id' => $id 
				]);
			}
		}

        $data = [
            'adv' => $adv ,
            'has_click' => $clickCount ,
            'has_praise' => $praiseCount ,
        ] ;
		return view('adv.show' , $data );
	}
	

	public function gain($id) {
		$adv = AdvPosts::findOrFail($id);
		$count = 0;
		$user = auth()->guard('wap')->user();
		if ($user->id == $adv->user_id) {
			return response()->json([
					'errcode' => 10002,
					'msg' => '自己不能获取铜板'
			]);
		}
		$count = ClickLogs::where('user_id', $user->id)->where('post_id', $adv->id)->count();
		if ($count > 0) {
			return response()->json([
					'errcode' => 10001,
					'msg' => '您已获取赠送数据了'
			]);
		}
	
		DB::beginTransaction();
		try {
			$count = AdvPosts::where('id', $id)->whereRaw('has_click_times < click_times')->count();
			if ($count == 0) {
				throw new \Exception("该任务已经完成了点击次数");
			}
			// 更新点击次数
			$row = AdvPosts::where('id', $id)->increment('has_click_times');
			if (! $row) {
				throw new \Exception("点击次数更新失败");
			}
			$log = ClickLogs::create([
					'user_id' => $user->id,
					'post_id' => $adv->id,
					'click_reward' => $adv->price * 100 // 转化成分
			]);
			if (! $log) {
				throw new \Exception("点击记录创建失败");
			}
			// 创建加钱记录
			$cashLog = UserReward::create([
					'user_id' => $user->id,
					'event' => 'click_reward',
					'target_id' => $adv->id,
					'target_desc' => "查看{$adv->title}获取铜板",
					'act' => 'in',
					'cash' => $adv->price * 100
					]);
			if (! $cashLog) {
				throw new \Exception("创建用户铜板获取记录失败");
			}
			$row = User::where('id', $user->id)->where('cash_reward', $user->cash_reward)->update([
					'cash_reward' => DB::raw('cash_reward + ' . $adv->price * 100)
			]);
			if (! $row) {
				throw new \Exception("用户铜板增加失败");
			}
			DB::commit();
			if ($user->invite_by) {
				// TODO 为上级赠送分润
				// dispatch();
			}
			return response()->json([
					'errcode' => 0,
					'msg' => '您本次获得' . $adv->price . '铜板'
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json([
					'errcode' => 10002,
					'msg' => $e->getMessage()
	
			]);
		}
	}
	
	public function up( Request $request)
	{
		$id = $request->input('id');
		if( !$id ) {
			return response()->json([
					'errcode' => 10001 ,
					'msg' => '参数错误'
			]);
		}
		$adv = AdvPosts::findOrFail($id);
		$user = auth()->guard('wap')->user();
		$count = PraiseLog::adv()->where('user_id', $user->id)
		->where('target_id', $id)
		->count();
		if ($count) {
			return response()->json([
					'errcode' => 10001,
					'msg' => '您已经赞过了'
			]);
		}
		$row = AdvPosts::where('id', $id)->update([
				'up_times' => DB::raw('up_times + 1')
		]);
		PraiseLog::create([
				'user_id' => $user->id,
				'type' => 'adv',
				'target_id' => $id
		]);
	
		if ($row) {
			return response()->json([
					'errcode' => 0,
					'num' => $adv->up_times + 1
			]);
		}
	}
	
	public function down(Request $request)
	{	
		$id = $request->input('id');
		if( !$id ) {
			return response()->json([
					'errcode' => 10001 ,
					'msg' => '参数错误'
			]);
		}
		$adv = AdvPosts::findOrFail($id);
		$user = auth()->guard('wap')->user();
		$count = PraiseLog::adv()->where('user_id', $user->id)
		->where('target_id', $id)
		->count();
		if ($count) {
			return response()->json([
					'errcode' => 10001,
					'msg' => '您已经踩过了'
			]);
		}
		$row = AdvPosts::where('id', $id)->update([
				'down_times' => DB::raw('down_times + 1')
		]);
		PraiseLog::create([
				'user_id' => $user->id,
				'type' => 'adv',
				'target_id' => $id
		]);
	
		if ($row) {
			return response()->json([
					'errcode' => 0,
					'num' => $adv->down_times + 1
			]);
		}
	}
}