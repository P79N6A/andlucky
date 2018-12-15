<?php
namespace App\Ext;

use App\Models\MessageLog;
use Illuminate\Support\Facades\Cache;

class RecentChatHelper {
	
	public function set( MessageLog $log ) {
		//把最近的聊天信息 写到缓存中去  只保留100条记录
		$listsA = Cache::get('recent_chat:' . $log->sender_id );
		if( !$listsA ) {
			$listsA = [] ;
		}
		$listsA[ $log->receiver_id ] = $log->id ;
		Cache::forever( 'recent_chat:' . $log->sender_id , $listsA );
		
		$listB = Cache::get('recent_chat:' . $log->receiver_id );
		if( !$listB ) {
			$listB = [] ;
		}
		$listB[ $log->sender_id ] = $log->id ;
		Cache::forever( 'recent_chat:' . $log->receiver_id , $listB );
	}
	
	
	public function get( $userId ) {
		$list = Cache::get('recent_chat:' . $userId ) ;
		return $list ? $list : [];
	}
}