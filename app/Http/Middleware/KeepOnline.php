<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class KeepOnline
{
	public function handle($request, Closure $next, $guard = null) {
		$user = auth()->guard( $guard)->user();
		if( $user ) {
			\Cache::put( 'online:' . $user->name , 1 , 5 );
    		$online = \Cache::get('all_online_users' ) ;
    		if( !$online ) {
    			$online = [] ;
    		}
    		$online[ $user->name ] = time() ;
    		\Cache::forever( 'all_online_users' , $online );
			return $next( $request ) ;
		} else {
			return $next( $request ) ;
		}
	}
}