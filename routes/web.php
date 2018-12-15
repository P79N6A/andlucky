<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test' , 'Controller@test') ;


Route::group( ['middleware' => ['web']  ] , function( $route ) {

	$route->get('/' , 'HomeController@home')->name('microblog.index');
    $route->get('gg' , 'AdvController@index')->name('adv.index');
	$route->post('charge/notify' , 'HomeController@notify');
	$route->get('charge/ret' , 'HomeController@ret');
	
	$route->get('im/isonline' , 'ImController@checkOnline');
	$route->get('pong' , 'ImController@pong');

	$route->any('longsearch' , 'BigSmallController@longsearch') ;
	$route->get('onlineuser' , 'BigSmallController@searchUser') ;
	//$route->get('advs' , 'HomeController@advs') ;
	$route->get('gg/{id}' , 'AdvController@show')->where('id' , '[0-9]+')->name('wap.adv.show');
	$route->get('microblog' , 'HomeController@index');
	$route->get('microblog/{id}' , 'MicroblogController@show')->where('id' , '[0-9]+')->name('microblog.show');
	$route->get('comments' , 'MicroblogController@comments')->name('comments') ;
	
	
	$route->get('aboutus' , 'HomeController@aboutus')->name('aboutus') ;
	$route->get('help' , 'HomeController@help')->name('help');
    $route->get('feedback' , 'HomeController@feedback')->name('feedback');
	$route->post('feedback' , 'HomeController@feedbackStore')->name('feedback');
	
	$route->get('notices' , 'HomeController@notices')->name('wap.notice.index');
	$route->get('notice/{id}' , 'HomeController@notice')->name('wap.notice.show') ;
		
    //old
	//$route->get('/' , 'HomeController@index')->name('wap.home');
    $route->get('login' , 'UserController@login')->name('login');
	$route->post('login' , 'UserController@dologin')->name('login');
	$route->get('logout' , 'UserController@logout')->name('logout');

	$route->get('register' , 'UserController@register')->name('register');
	$route->post('register' , 'UserController@doregister')->name('register');
	
	//发送验证码
	$route->post('sendmobilesms' , 'HomeController@sendVerifyCode')->name('wap.sendmobilesms');
	$route->post('sendfindpwdsms' , 'HomeController@sendFindPwdCode')->name('wap.sendfindpwdsms');

    $route->get('findpwd' , 'UserController@findpwd')->name('findpwd');
	$route->post('findpwd' , 'UserController@dofindpwd')->name('findpwd');
	
	$route->get('post/{id}' , 'HomeController@post' )->name('wap.post');
	
	
});

Route::group( ['middleware' => ['web' , 'auto.login' , 'auth:wap' , 'keeponline:wap' ]  ] , function( $route ) {

    $route->get('bigsmall/index' , 'BigSmallController@index')->name('wap.game.index');
    $route->get('guess/all' , 'GuessController@all')->name('wap.guess.index');


    $route->get('member' , 'UserController@index')->name('member.index');
    $route->get('member/avatar' , 'UserController@avatar')->name('wap.member.avatar');
	$route->post('member/avatar' , 'UserController@updateAvatar')->name('wap.member.avatar');

    $route->get('member/baseinfo' , 'UserController@baseinfo')->name('wap.member.baseinfo');
    $route->post('member/baseinfo' , 'UserController@doModBaseInfo')->name('wap.member.baseinfo');

	$route->get('member/cashlog' , 'UserController@cashlog')->name('member.income');
	$route->get('member/chargelog' , 'UserController@chargelog');
	$route->get('member/withdrawlog' , 'UserController@withdrawlog')->name('member.withdrawlog');
	$route->get('member/withdraw' , 'UserController@withdraw')->name('member.withdraw');
	$route->post('member/withdraw' , 'UserController@withdrawApply')->name('member.withdraw');
    $route->get('modpwd' , 'UserController@modpwd')->name('member.modpwd');
	$route->post('modpwd' , 'UserController@domodpwd')->name('member.modpwd');
	$route->get('invited' , 'UserController@invited');
	$route->get('messages' , 'UserController@messages')->name('wap.message.index');
	$route->get('message/{id}' , 'UserController@messageShow')->name('wap.message.show');
	$route->get('userinfo' , function( Request $request ){
		return response()->json([
				'errcode' => 0 ,
				'user' => auth()->guard('api')->user()
		]);
	});
	
	//押大小
    $route->get('guess/create' , "GuessController@create")->name('wap.guess.create');
	$route->post('guess/create' , 'GuessController@store')->name('wap.guess.create');
	$route->get('guess/mine' , 'GuessController@mine')->name('wap.guess.mine');
	$route->get('guess/joins' , 'GuessController@joins');
	$route->get('guess/{id}' , 'GuessController@show')->name('wap.guess.show');
	$route->get('guess/take/{id}' , 'GuessController@take');
	$route->post('guess/take/{id}' , 'GuessController@takeStore');
	$route->put('guess/reback/{id}' , 'GuessController@reback')->name('wap.guess.reback');
	
	//比大小
	$route->put('bigsmall/invite' , 'BigSmallController@invite' );

	$route->put('bigsmall/accept/{id}' , 'BigSmallController@accept' )->name('wap.game.accept');
	$route->put('bigsmall/cancel/{id}' , 'BigSmallController@cancel' )->name('wap.game.cancel');
	$route->put('bigsmall/reject/{id}' , 'BigSmallController@reject' )->name('wap.game.reject');
	$route->put('bigsmall/reback/{id}' , 'BigSmallController@reback' )->name('wap.game.reback');

	$route->get('bigsmall/help' , 'BigSmallController@help' )->name('wap.game.help');
    $route->get('bigsmall/invited/{id}' , 'BigSmallController@invited' );
	$route->get('bigsmall/detail/{id}' , 'BigSmallController@detail' )->name('wap.game.show');
	$route->get('bigsmall/mine' , 'BigSmallController@mine')->name('wap.game.mine');

	//摸一摸
	$route->put('stolen/stolen/{userId}' , 'StolenController@stolen');
	$route->get('stolen/thieves' , 'StolenController@thief');


	$route->get('im/searchfriend' , 'ImController@search')->name('wap.im.searchfriend');
    $route->get('im/recent' , 'ImController@recentChat')->name('im.recent');
    $route->get('im/addfriend/{id}' , 'ImController@addFriendShow')->name('wap.im.addfriend');
	$route->post('im/addfriend/{id}' , 'ImController@addFriend')->name('wap.im.addfriend');
	$route->get('im/friends' , 'ImController@friends')->name('friends');
	$route->get('im/chat/{id}' , 'ImController@chat')->name('wap.im.chat');
    $route->get('im/game/{id}' , 'ImController@game')->name('wap.im.game');
    $route->get('im/detail/{id}' , 'ImController@detail')->name('wap.im.detail');

	$route->get('im/userinfo/{id}' , 'ImController@userinfo');

	$route->post('im/sendtextmsg/{id}' , 'ImController@sendTextMsg');
	$route->post('im/record' , 'ImController@record');
	$route->get('im/history/{id}' , 'ImController@history');
	$route->post('im/accpet' , 'ImController@agreeFriend');
	$route->post('im/disaccpet' , 'ImController@disagreeFriend');



	//上传文件
	$route->any('uploadfile' , 'HomeController@upload');
	//充值
	$route->get('charges' , 'UserController@charges')->name('member.charge');
    $route->get('charge' , 'UserController@charge')->name('member.chargeform');
	$route->post('charge' , 'UserController@doCharge')->name('member.chargeform');

	//博客
    $route->get('microblog/create' , 'MicroblogController@create')->name('microblog.create');
	$route->post('microblog/createblog' , 'MicroblogController@store');
	$route->post('microblog/updateblog' , 'MicroblogController@update');
	$route->post('microblog/drop' , 'MicroblogController@drop')->name('wap.microblog.drop');
	$route->get('microblog/mine' , 'MicroblogController@mine')->name('wap.microblog.mine');
	$route->get('microblog/list' , 'MicroblogController@usersblog');
	$route->get('microblog/userinfo' , 'MicroblogController@userinfo');
	$route->post('microblog/gain/{id}' , 'MicroblogController@reward')->name('microblog.gain');
	$route->get('microblog/{id}/edit' , 'MicroblogController@edit')->name('wap.microblog.edit');

	//广告管理
	$route->get('gg/create' , 'AdvController@create')->name('adv.create');
	$route->post('gg/store' , 'AdvController@store');
	$route->get('gg/mine' , 'AdvController@mine')->name('wap.adv.mine');
	$route->get('gg/{id}/edit' , 'AdvController@edit')->name('wap.adv.edit');
	$route->post('gg/{id}/update' , 'AdvController@update');
	$route->get('gg/{id}/pay' , 'AdvController@pay')->name('wap.adv.pay');

	//关注
	$route->post('user/focus' , 'FocusController@create');
	$route->post('user/unfocus' , 'FocusController@destroy');


	//评论
	$route->post('comment/store' , 'MicroblogController@storecomment');
	$route->post('microblog/praise' , 'MicroblogController@praise')->name('microblog.praise');
	$route->get('comment/{id}' , 'MicroblogController@comment')->name('comment.show');
	$route->delete('comment/{id}' , 'MicroblogController@destroyComment')->name('comment.drop');

	//广告
	$route->post('gg/up' , 'AdvController@up');
	$route->post('gg/down' , 'AdvController@down');
	$route->post('gg/gain/{id}' , 'AdvController@gain')->name('wap.adv.gain');

});
