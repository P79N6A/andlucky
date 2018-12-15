<?php
return [
    'supper_verify' => '888888',
    'easemob' => [
        'client_id' => 'YXA6YinSEOsCEeimiicmagcOpA',
        'client_secret' => 'YXA6hLh7kA9fAnoWXg8N0-gLfMflueg',
        'org_name' => '1101181117098231',
        'app_name' => 'andlucky'
    ],
    'charge_status' => [
        'success' => '充值完成' ,
        'wait' => '等待充值'
    ] ,
    'big_small_status' => [
        0 => '待接受',
        1 => '已接受', // 并支付
        2 => '用户退还',
        3 => '已关闭', // 拒绝
        4 => '用户撤销' ,
    	5 => '系统撤销' ,
    	6 => '系统退回'
    ],
	'guess_status' => [
		0 => '未开台' ,
		1 => '已开台' ,
		2 => '已失效'
	] ,
	'guess_seed' => [
			'max' => '大' ,
			'mid' => '中' ,
			'min' => '小'
	] ,
	'guess_join_status' => [
			0 => '未开台' ,
			1 => '已开台未退' ,
			2 => '已开台已退'
	] ,
    'no_nickname' => '狗运',
    'sms' => [
        'sms_app_id' => 'LTAI2caNWSy8Fqjl',
        'sms_app_key' => 'qPBdY6ek1oFlLmdARIHzkIVdqaeiHP',
        'sms_sign' => '阿里云短信测试专用',
        'sms_tpl_no_verify' => 'SMS_122110255'
    ] ,
	'withdraw_status' => [
			0 => '待付款' ,
			1 => '已付款' ,
			2 => '审核不通过' ,
	] ,
	'cash_event' => [
			'stolen' => '偷取' ,
			'create_post' => '发广告' ,
			'bigsmall' => '发起比大小' ,
			'bigsmallrefund' => '比大小退回' ,
			'refund' => '推广'
	] ,
	'microblog_cate' => [
			1 => '热点' ,
			2 => '科技' ,
			3 => '情感' ,
			4 => '搞笑' ,
			5 => '军事' ,
			6 => '富豪' ,
			7 => '美女' ,
			8 => '记录'
	] ,
	'microblog_auth_status' => [
			0 => '未审核' , 
			1 => '已审核'
	] ,
	'microblog_allow_auth' => [
			0 => '不送审' ,
			1 => '送审'
	] ,
	'microblog_adv_pos' => [
			'top' => '顶部' ,
			'bottom' => '底部'
	]
];
