<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserRsync;
use App\Models\User;
use Cache ;
use App\Models\Doctor;

class AutoLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
    	//检查登录情况
        $user = auth()->guard('wap')->user();
        if( !$user ) {
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
                    return redirect()->back();
                }
            } else {
                //没有登录记录的
                return redirect()->back();
            }
        }
        return $next($request);
        
    }
}
