<?php
use Illuminate\Routing\Router;

Admin::registerAuthRoutes();
Cms::registerAdminRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware')
], function (Router $router) {
    
    $router->get('/', 'HomeController@index');
    
    /**
     * 广告分类的
     */
    $router->resource('category', 'CategoryController', [
        'names' => [
            'index' => 'admin.category.index',
            'create' => 'admin.category.create',
            'store' => 'admin.category.store',
            'edit' => 'admin.category.edit',
            'update' => 'admin.category.update',
            'destroy' => 'admin.category.delete'
        ],
        'middleware' => [
            'admin.permission:check,admin_category'
        ]
    ]);
    
    /**
     * 职业列表
     */
    $router->resource('career', 'CareerController', [
        'names' => [
            'index' => 'admin.career.index',
            'create' => 'admin.career.create',
            'store' => 'admin.career.store',
            'edit' => 'admin.career.edit',
            'update' => 'admin.career.update',
            'destroy' => 'admin.career.delete'
        ],
        'middleware' => [
            'admin.permission:check,admin_career'
        ]
    ]);
    
    /**
     * 学历管理
     */
    $router->resource('degree', 'DegreeController', [
        'names' => [
            'index' => 'admin.degree.index',
            'create' => 'admin.degree.create',
            'store' => 'admin.degree.store',
            'edit' => 'admin.degree.edit',
            'update' => 'admin.degree.update',
            'destroy' => 'admin.degree.delete'
        ],
        'middleware' => [
            'admin.permission:check,admin_degree'
        ]
    ]);
    
    /**
     * 广告内容管理
     */
    $router->resource('advposts', 'AdvPostsController', [
        'names' => [
            'index' => 'admin.advposts.index',
            'create' => 'admin.advposts.create',
            'store' => 'admin.advposts.store',
            'edit' => 'admin.advposts.edit',
            'update' => 'admin.advposts.update',
            'destroy' => 'admin.advposts.delete'
        ],
        'middleware' => [
            'admin.permission:check,admin_advposts'
        ]
    ]);
    
    /**
     * 会员管理
     */
    $router->resource('member', 'MemberController', [
        'names' => [
            'index' => 'admin.member.index',
            'create' => 'admin.member.create',
            'store' => 'admin.member.store',
            'edit' => 'admin.member.edit',
            'update' => 'admin.member.update',
            'destroy' => 'admin.member.delete'
        ],
        'middleware' => [
            'admin.permission:check,admin_member'
        ]
    ]);
    $router->get('member/{id}/restore' , 'MemberController@restore' )->name('admin.member.restore');
    
    /**
     * 充值记录
     */
    $router->resource('charge', 'ChargeController', [
        'names' => [
            'index' => 'admin.charge.index',
            'create' => 'admin.charge.create',
            'store' => 'admin.charge.store',
            'edit' => 'admin.charge.edit',
            'update' => 'admin.charge.update',
            'destroy' => 'admin.charge.delete'
        ],
        'middleware' => [
            'admin.permission:check,admin_charge'
        ]
    ]);
    
    /**
     * 提现记录
     */
    $router->resource('withdraw', 'WithdrawController', [
        'names' => [
            'index' => 'admin.withdraw.index',
            'create' => 'admin.withdraw.create',
            'store' => 'admin.withdraw.store',
            'edit' => 'admin.withdraw.edit',
            'update' => 'admin.withdraw.update',
            'destroy' => 'admin.withdraw.delete'
        ],
        'middleware' => [
            'admin.permission:check,admin_withdraw'
        ]
    ]);
    
    /**
     * 收益流水
     */
    $router->resource('reward', 'RewardController', [
        'names' => [
            'index' => 'admin.reward.index',
            'create' => 'admin.reward.create',
            'store' => 'admin.reward.store',
            'edit' => 'admin.reward.edit',
            'update' => 'admin.reward.update',
            'destroy' => 'admin.reward.delete'
        ],
        'middleware' => [
            'admin.permission:check,admin_reward'
        ]
    ]);
    
    /**
     * 发送信息
     */
    $router->resource('message', 'MessageController', [
        'names' => [
            'index' => 'admin.message.index',
            'create' => 'admin.message.create',
            'store' => 'admin.message.store',
            'edit' => 'admin.message.edit',
            'update' => 'admin.message.update',
            'destroy' => 'admin.message.delete'
        ],
        'only' => [
            'index',
            'create',
            'destroy',
            'store'
        ],
        'middleware' => [
            'admin.permission:check,admin_message'
        ]
    ]);
    
    /**
     * 提现记录
     */
    $router->resource( 'withdraw' , 'WithdrawController' , [
    		'names' => [
    				'index' => 'admin.withdraw.index' ,
    				'edit' => 'admin.withdraw.edit' ,
    				'update' => 'admin.withdraw.update' ,
    				'destroy' => 'admin.withdraw.destroy' ,
    				'show' => 'admin.withdraw.show' ,
    		] ,
    		//'only' => [] ,
    		'except' => [
    				'create', 'store'
    		] ,
    ] );
    
    /**
     * 说说
     */

    $router->resource( 'microblogcate' , 'MicroblogCateController' , [
    		'names' => [
    				'index' => 'admin.microblogcate.index' ,
    				'edit' => 'admin.microblogcate.edit' ,
    				'update' => 'admin.microblogcate.update' ,
    				'destroy' => 'admin.microblogcate.destroy' ,
    				'show' => 'admin.microblogcate.show' ,
    		] ,
    		//'only' => ['index' , 'destroy'] ,
    ] );
    $router->resource( 'microblog' , 'MicroblogController' , [
    		'names' => [
    				'index' => 'admin.microblog.index' ,
    				'edit' => 'admin.microblog.edit' ,
    				'update' => 'admin.microblog.update' ,
    				'destroy' => 'admin.microblog.destroy' ,
    				'show' => 'admin.microblog.show' ,
    		] ,
    		//'only' => ['index' , 'destroy'] ,
    ] );
    
    $router->resource( 'comment' , 'CommentController' , [
    		'names' => [
    				'index' => 'admin.comment.index' ,
    				'edit' => 'admin.comment.edit' ,
    				'update' => 'admin.comment.update' ,
    				'destroy' => 'admin.comment.destroy' ,
    				'show' => 'admin.comment.show' ,
    		] ,
    		//'only' => ['index' , 'destroy'] ,
    ] );
    
    /**
     * 充值记录
     */
    $router->get('finance' , 'FinanceController@index');
    /**
     * 用户流水
     */
    $router->get('usercash' , 'UserCashController@index');
    
    /**
     * 比大小记录
     */
    $router->get('bigsmall' , 'BigSmallController@index');
    
    /**
     * 押大小记录
     */
    $router->get('guess' , 'GuessController@index');
    $router->get('guess/{id}' , 'GuessController@show')->name('admin.guess.show');
});
