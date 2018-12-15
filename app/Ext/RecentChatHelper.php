<?php
namespace App\Ext;

use Illuminate\Support\Facades\Cache;
use App\Models\Stolen;

class RecentStolenHelper {
	
	public function set( Stolen $log ) {
		//把最近的聊天信息 写到缓存中去  只保留100条记录 只记录偷过我的
		$listsA = Cache::get('recent_stolen:' . $log->from_user_id );
		if( !$listsA ) {
			$listsA = [] ;
		}
		$listsA[ $log->user_id ] = $log->id ;
		Cache::forever( 'recent_stolen:' . $log->from_user_id , $listsA );
	}
	
	
	public function get( $userId ) {
		$list = Cache::get('recent_stolen:' . $userId ) ;
		return $list ? $list : [];
	}
}