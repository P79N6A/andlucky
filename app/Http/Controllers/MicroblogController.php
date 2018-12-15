<?php
namespace App\Http\Controllers;

use App\Models\MicroblogCate;
use Illuminate\Http\Request;
use App\Models\Microblog;
use App\Models\Comment;
use App\Models\ViewLog;
use App\Models\PraiseLog;
use App\User;
use App\Models\UserFocus;

class MicroblogController extends Controller
{

    
    public function userinfo( Request $request ) {
    	$userId = $request->input('user_id');
    	$loginUser = auth()->guard('wap')->user();
    	$hasCare = false ;
    	if( !$userId ) {
    		$userId = $loginUser->id ;
    	}
    	if( !$userId ) {
    		return response()->json([
    				'errcode' => 10001 ,
    				'msg' => '参数错误'
    		]);
    	}
    	$user = User::withCount(['blog' , 'fans' , 'cares' ])->findOrFail( $userId );
    	return response()->json([
    			'errcode' => 0 ,
    			'user' => $user ,
    			'has_care' => UserFocus::where('user_id' , data_get( $loginUser , 'id' ) )->where('focus_user_id' , $userId )->count()
    	]);
    }
    
    
    /**
     * 我发表的说说
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function mine( Request $request ) {
		$user = auth ()->guard ( )->user ();
		$blog = Microblog::withCount ( [ 
				'praises' => function ($query) use ($user) {
					return $query->where ( 'user_id', $user->id );
				} 
		] )->with('user')->where ( 'user_id', $user->id )->orderBy ( 'id', 'desc' )->paginate ( 10 );
        $data = [
            'list' => $blog
        ] ;
        if( $request->ajax() ) {
            return response()->json([
                'errcode' => 0 ,
                'html' => view('microblog.mineitem' , $data )->render()
            ]);
        }
		return view('microblog.mine' , $data );
	}
	public function usersblog( Request $request ) {
		$user = auth ()->guard ( 'api' )->user ();
		$userId = (int) $request->input('user_id');
		$blog = Microblog::withCount ( [ 
				'praises' => function ($query) use ($user) {
					return $query->where ( 'user_id', data_get( $user , 'id' ) );
				} 
		] )->with ( 'user' )->where ( 'user_id', $userId )->where('auth_status' , 1)->orderBy ( 'id', 'desc' )->paginate ( 10 );
    			 
    	return response()->json([
    			'errcode' => 0 ,
    			'data' => $blog
    	]);
    }
    


    public function create()
    {
        $cates = MicroblogCate::where('display' , 1 )->orderBy('sort' , 'asc' )->orderBy('id' , 'asc')->pluck('title' , 'id' );
        $data = [
            'foot' => 'form' ,
            'cates' => $cates ,
            'blog' => new Microblog()
        ] ;
        return view('microblog.create' , $data );
    }

    /**
     * 保存说说
     * 
     * @param Request $request
     */
    public function store(Request $request)
    {
        $user = auth()->guard('wap')->user();
        $title = $request->input('title');
        $info = $request->input('info');
        $extra = $request->input('uploaded');
        $covers = [] ;
        if( isset( $extra ) && is_array( $extra ) && !empty( $extra ) ) {
            foreach( $extra as $base64 ) {
                preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result);
                $type = $result[2];
                $file = str_replace($result[1], '', $base64);
                $name = 'microblog/' . date('Ymdhis' ) . str_random( 5 ) . "." . $type;

                $result = \Storage::disk('public')->put($name, base64_decode( $file ));
                if ($result) {
                    $covers[] = 'uploads/' . $name;
                }
            }
        }


        $allowAuth = $request->input( 'allow_auth') ;
        if (empty($info) && empty($extra)) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '请不要发表空说说'
            ]);
        }
        $blog = new Microblog();
        $blog->title = $title ;
        $blog->user_id = $user->id;
        $blog->content = $info;
        $blog->cate_id = $request->input('cate');
        $blog->extra = [
            'type' => 'image',
            'data' => $covers
        ];
        $blog->allow_auth = $allowAuth ;
        $blog->auth_status = $allowAuth ? 0 : 1 ;
        $blog->views = 0;
        $blog->prase = 0;
        $blog->comment_count = 0;
        if ($blog->save()) {
            return response()->json([
                'errcode' => 0,
                'url' => route('microblog.index' , ['cate_id' => $blog->cate_id ]) ,
                'msg' => '发表成功'
            ]);
        }
        return response()->json([
            'errcode' => 10002,
            'msg' => '发表失败'
        ]);
    }
    
    /**
     * 保存说说
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
    	$user = auth()->guard('wap')->user();
    	$title = $request->input('title');
    	$info = $request->input('info');
        $covers = [] ;
        $extra = $request->input('uploaded');
        if( isset( $extra ) && is_array( $extra ) && !empty( $extra ) ) {
            foreach( $extra as $base64 ) {
                preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result);
                if ($result && isset( $result[2] )) {
                    $type = $result[2];
                    $file = str_replace($result[1], '', $base64);
                    $name = 'microblog/' . date('Ymdhis') . str_random(5) . "." . $type;

                    $result = \Storage::disk('public')->put($name, base64_decode($file));
                    if ($result) {
                        $covers[] = 'uploads/' . $name;
                    }
                } else {
                    $covers[] = $base64 ;
                }
            }
        }
    	$allowAuth = $request->input( 'allow_auth') ;
    	if (empty($info) && empty($extra)) {
    		return response()->json([
    				'errcode' => 10001,
    				'msg' => '请不要发表空说说'
    		]);
    	}
    	$blog = Microblog::where('id' , $request->input('id') )->where('user_id' , $user->id )->first();
    	if( empty( $blog ) ) {
    		return response()->json([
    				'errcode' => 10002 ,
    				'msg' => '您没有权限修改'
    		]);
    	}
    	$blog->title = $title ;
    	$blog->user_id = $user->id;
    	$blog->content = $info;
    	$blog->cate_id = $request->input('cate');
    	$blog->extra = [
    			'type' => 'image',
    			'data' => $covers
    	];
    	$blog->allow_auth = $allowAuth ;
    	$blog->auth_status = $allowAuth ? 0 : 1 ;
    	$blog->views = 0;
    	$blog->prase = 0;
    	$blog->comment_count = 0;
    	if ($blog->save()) {
    		return response()->json([
    				'errcode' => 0,
                'url' => route('microblog.index' , ['cate_id' => $blog->cate_id ]) ,
    				'msg' => '修改成功'
    		]);
    	}
    	return response()->json([
    			'errcode' => 10002,
    			'msg' => '修改失败'
    	]);
    }

    public function drop( Request $request ) {
        $id = $request->input('id');
        if( !$id ) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '请指定要删除的说说'
            ]);       
        }
        $user = auth()->guard('wap')->user();
        if( empty( $user ) ) {
            return response()->json([
                'errcode' => 10002,
                'msg' => '请选登录'
            ]);   
        }
        if( Microblog::where('id' , $id )->where('user_id' , $user->id )->delete() ) {
            return response()->json([
                'errcode' => 0,
                'msg' => '删除成功'
            ]);      
        }
        return response()->json([
            'errcode' => 10003,
            'msg' => '删除失败'
        ]);   
    }

    /**
     * 显示blog
     * 
     * @param unknown $id
     */
    public function show($id) {
        $blog = Microblog::with('user')->withCount('comments', 'praises')->findOrFail($id);
        // 获取登录状态
        $user = auth()->guard('wap')->user();
        if( data_get( $user , 'id' ) != $blog->user_id && $blog->auth_status != 1 ) {
            return response()->json([
                'errcode' => 10001 ,
                'data' => [] ,
                'msg' => '信息不存在'
            ]) ;
        }
        $hasPraise = 0;
        $hasView = 0 ;
        // 查看次数+1
        if ($user) {
            $hasView = ViewLog::blog()->where('user_id', $user->id)->where('target_id', $id)->count();
            $hasPraise = PraiseLog::blog()->where('user_id', $user->id)->count();
        }
        $blog->adv_cover = $blog->adv_cover ? \Storage::disk('admin')->url( $blog->adv_cover ) : '' ;
        
        $data = [
            'blog' => $blog,
            'user' => $user,
            'has_praise' => $hasPraise,
        	'has_view' => $hasView ,
            'foot' => 'microblog' ,
        	'countdown' => config('MICROBLOG_VIEW_DELAY' , 30 )
        ];

        return view('microblog.show' , $data ) ;
        return response()->json([
            'errcode' => 0,
            'data' => $data
        ]);
    }
    
    public function edit($id) {
    	$user = auth()->guard('wap')->user();
    	$blog = Microblog::with('user')->where('id' , $id )->where('user_id' , $user->id )->first();
    	if( empty( $blog ) ) {
    		return back()->with('error' , '您没有权限');
    	}
        $cates = MicroblogCate::where('display' , 1 )->orderBy('sort' , 'asc' )->orderBy('id' , 'asc')->pluck('title' , 'id' );
        $data = [
            'foot' => 'form' ,
            'cates' => $cates ,
            'blog' => $blog
        ] ;
        return view('microblog.create' , $data );
    }

    /**
     * 评论列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(Request $request )
    {
        $id = $request->input('id');
        $parent_id = $request->input('parent_id' , 0 );
        $user = auth()->guard('wap')->user();
        $type = $request->input( 'type' , 'blog' );
        $list = Comment::with([
            'comments' => function ($query) {
                return $query->take(5);
            } ,
            'user' ,
            'comments.user'
        ])->where('target_type' , $type )
        ->withCount([
            'praise' => function ($query) use ($user) {
                return $query->where('user_id', data_get( $user , 'id' ) );
            }
        ])
        ->where('target_id', $id)
        ->paginate(10);

        $blog = Microblog::with('user')->withCount('comments', 'praises')->findOrFail( $parent_id );

        $canDrop = $blog->user->id == data_get( $user  , 'id' , 0 ) ;
        return response()->json([
            'errcode' => 0 ,
            'html' => view('comment.item' , ['list' => $list , 'parent_id' => $parent_id , 'can_drop' => $canDrop ])->render() ,
            'totalPage' => $list->lastPage() ,
            'currentPage' => $list->currentPage() ,
            'total' => $list->total()
        ]);
    }

    public function comment($id , Request $request )
    {
        $user = auth()->guard('wap')->user();
        $parent_id = $request->input('parent_id' , 0 );
        $comment = Comment::with('user')->withCount([
            'praise' => function ($query) use ($user) {
                return $query->where('user_id', data_get($user, 'id'));
            }
        ])
            ->find($id);

        return view('comment.show' , ['comment' => $comment , 'parent_id' => $parent_id ]) ;
        return response()->json([
        		'errcode' => 0 ,
        		'data' => $comment ,
        		'visitor' => $user 
        ]);
        
    }
    
    /**
     * 删除评论  如果是贴主则可以删除 如果是评论本人也可以删除
     * @param unknown $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyComment($id) {
		$user = auth ()->guard ( 'wap' )->user ();
		$comment = Comment::with ( 'user' )->find ( $id );
		if( empty( $comment ) ) {
			return response()->json([
					'errcode' => 10001 , 
					'msg' => '删除数据错误'
			]);
		}
		if( $comment->target_type == 'comment' ) {
			$pComment = Comment::find( $comment->target_id ) ;
			if( empty( $pComment ) ) {
				return response()->json([
						'errcode' => 10002 , 
						'msg' => '数据错误'
				]);
			}
			if( $pComment->user_id != $user->id && $comment->user_id != $user->id ) {
				return response()->json([
						'errcode' => 10003 ,
						'msg' => '您没有权限'
				]);
			}
		}
		//如果本条数据为博客的则博客博主  和个人都可以删除
		if( $comment->target_type == 'blog' ) {
			$blog = Microblog::find( $comment->target_id ) ;
			if( empty( $blog ) ) {
				return response()->json([
						'errcode' => 10002 , 
						'msg' => '数据错误'
				]);
			}
			if( $blog->user_id != $user->id && $comment->user_id != $user->id ) {
				return response()->json([
						'errcode' => 10003 , 
						'msg' => '您没有权限'
				]);
			}
		}
		
		//删除数据
		if( $comment->target_type == 'blog' ) {
			return $this->_dropBlogComment( $comment ) ;
		}
		if( $comment->target_type == 'comment' ) {
			return $this->_dropCommentComment( $comment ) ;
		}
		
		return response ()->json ( [ 
				'errcode' => 0,
				'data' => $comment ,
				'msg' => '删除成功' 
		]);
    }

    /**
     * 保存评论
     */
    public function storecomment(Request $request)
    {
        $user = auth()->guard('wap')->user();
        $id = $request->input('id');
        $type = $request->input('type');
        switch ($type) {
            case 'blog':
                return $this->_commentBlog($id, $user);
                break;
            case 'comment':
                return $this->_commentComment($id, $user);
                break;
        }
    }

    protected function _commentBlog($id, $user)
    {
        \DB::beginTransaction();
        try {
            $row = Microblog::where('id', $id)->increment('comment_count');
            if (! $row) {
                throw new \Exception("评论出错");
            }
            $comment = Comment::create([
                'target_id' => $id,
                'target_type' => 'blog',
                'user_id' => $user->id,
                'content' => request()->input('content'),
                'status' => 0,
                'parse' => 0,
                'comment_count' => 0
            ]);
            if (! $comment) {
                throw new \Exception("评论出错");
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'errcode' => 10001,
                'msg' => $e->getMessage()
            ]);
        }
        return response()->json([
            'errcode' => 0,
            'msg' => '评论完成',
            'html' => view('comment.single' , ['val' =>  Comment::with('user')->find( $comment->id ) ] )->render()
        ]);
    }
    
    protected function _dropBlogComment( $comment )
    {
    	$id = $comment->target_id ;
    	\DB::beginTransaction();
    	try {
    		$row = Microblog::where('id', $id)->decrement('comment_count');
    		if (! $row) {
    			throw new \Exception("修改评论数据出错2");
    		}
    		$row = Comment::destroy( $comment->id );
    		if (!$row) {
    			throw new \Exception("删除评论出错");
    		}
    		\DB::commit();
    	} catch (\Exception $e) {
    		\DB::rollBack();
    		return response()->json([
    				'errcode' => 10001,
    				'msg' => $e->getMessage()
    		]);
    	}
    	return response()->json([
    			'errcode' => 0,
    			'msg' => '删除完成'
    	]);
    }

    protected function _commentComment($id, $user)
    {
        \DB::beginTransaction();
        try {
            $row = Comment::where('id', $id)->increment('comment_count');
            if (! $row) {
                throw new \Exception("评论出错");
            }
            $comment = Comment::create([
                'target_id' => $id,
                'target_type' => 'comment',
                'user_id' => $user->id,
                'content' => request()->input('content'),
                'status' => 0,
                'parse' => 0,
                'comment_count' => 0
            ]);
            if (! $comment) {
                throw new \Exception("评论出错");
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'errcode' => 10001,
                'msg' => $e->getMessage()
            ]);
        }
        return response()->json([
            'errcode' => 0,
            'msg' => '评论完成',
            'html' => view('comment.single' , ['val' =>  Comment::with('user')->find( $comment->id ) ] )->render()
        ]);
    }
    
    protected function _dropCommentComment( $comment )
    {
    	$id = $comment->target_id ;
    	\DB::beginTransaction();
    	try {
    		$row = Comment::where('id', $id)->decrement('comment_count');
    		if (! $row) {
    			throw new \Exception("修改评论数据出错");
    		}
    		$row = Comment::destroy( $comment->id );
    		if (!$row) {
    			throw new \Exception("删除评论出错");
    		}
    		\DB::commit();
    	} catch (\Exception $e) {
    		\DB::rollBack();
    		return response()->json([
    				'errcode' => 10001,
    				'msg' => $e->getMessage()
    		]);
    	}
    	return response()->json([
    			'errcode' => 0,
    			'msg' => '删除完成'
    	]);
    }

    /**
     * 点赞
     */
    public function praise(Request $request)
    {
        $type = $request->input('type');
        $name = $type == 'comment' ? '点赞' : 'Diss' ;
        $id = $request->input('id');
        $user = auth()->guard('wap')->user();
        if (! $user) {
            return response()->json([
                'errcode' => 10001,
                'msg' => '请先登录'
            ]);
        }
        $praiseLog = PraiseLog::where('type', $type)->where('target_id', $id)
            ->where('user_id', $user->id)
            ->first();
        if ($praiseLog) {
            // 找到了点赞记录 删除这个记录
            $method = '_decrement' . ucfirst($type);
            if (is_callable([
                $this,
                $method
            ])) {
                call_user_func([
                    $this,
                    $method
                ], $id);
            }
            $praiseLog->delete();
            return response()->json([
                'errcode' => 0,
                'act' => 'dec',
                'msg' => '取消'. $name .'成功'
            ]);
        } else {
            // 没有找到点赞记录 增加这个记录
            $method = '_increment' . ucfirst($type);
            if (is_callable([
                $this,
                $method
            ])) {
                call_user_func([
                    $this,
                    $method
                ], $id);
            }
            PraiseLog::create([
                'user_id' => $user->id,
                'type' => $type,
                'target_id' => $id
            ]);
            return response()->json([
                'errcode' => 0,
                'act' => 'inc',
                'msg' => $name . '成功'
            ]);
        }
    }

    protected function _decrementMicroblog($id)
    {
        \DB::table('microblog')->where('id', $id)->decrement('prase', 1);
    }

    protected function _decrementComment($id)
    {
        \DB::table('comments')->where('id', $id)->decrement('prase', 1);
    }

    protected function _incrementMicroblog($id)
    {
        \DB::table('microblog')->where('id', $id)->increment('prase', 1);
    }

    protected function _incrementComment($id)
    {
        \DB::table('comments')->where('id', $id)->increment('prase', 1);
    }
    
    
    public function reward( $id ) {
    	// 查看次数+1
    	$blog = Microblog::find( $id );
    	$user = auth()->guard('wap')->user();
    	if ($user && $user->id != $blog->user_id ) {
    		$count = ViewLog::blog()->where('user_id', $user->id)
    		->where('target_id', $id)
    		->count();
    		if ($count == 0 ) {
    			$blog->views += 1;
    			$blog->save();
    			$view = ViewLog::create([
						'user_id' => $user->id,
						'target_id' => $id,
    					'type' => 'microblog'
    			]);
    			return response()->json([
    					'errcode' => 0 ,
    					'msg' => "获得阅读收益"
    			]);
    			/**
    			if( $view ) {
	    			$viewCount = ViewLog::where('user_id' , $user->id )->where('created_at' , '>=' , date('Y-m-d 00:00:00') )
	    			->where('created_at' , '<=' , date('Y-m-d 23:59:59') )->count();
	    			//单日最大获取数
	    			$maxView = config('MICROBLOG_VIEW_MAX' , 10 );
	    			if( $viewCount <= $maxView ) {
	    				dispatch( new \App\Jobs\BlogViewReward(  $user , $view  )) ;
	    				$cash = config('MICROBLOG_VIEW_REWARD' , 0 );
	    				if( $user->invite_by ) {
	    					$parent = User::where('invite_code' , $user->invite_by)->first();
	    					if( $parent ) {
	    						//返佣比
	    						$rerundRate = config('REFUND_RATE' , 0 );
	    						$refund = sprintf("%.2f", $cash * $rerundRate / 100 );
	    						$cash = $cash - $refund ;
	    					}
	    				}
	    				if( $cash > 0 ) {
		    				return response()->json([
		    						'errcode' => 0 ,
		    						'msg' => "获得{$cash}铜板"
		    				]);
	    				}
	    				return response()->json([
	    						'errcode' => 20001 ,
	    						'msg' => '没有收益赠送'
	    				]);
	    			}
	    			return response()->json([
	    					'errcode' => 20001 ,
	    					'msg' => '当日到达最大获取上限'
	    			]);
    			}
    			**/
    			return response()->json([
    					'errcode' => 20001 ,
    					'msg' => '阅读记录写入错误'
    			]);
    		}
    		return response()->json([
    				'errcode' => 20001 ,
    				'msg' => '已经阅读过了'
    		]);
    		
    	} else {
    		return response()->json([
    				'errcode' => 20001 ,
    				'msg' => '不能获取'
    		]);
    	}
    }
}