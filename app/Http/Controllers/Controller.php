<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\ViewLog;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function test() {
    	$r =  dispatch( new \App\Jobs\BlogReward( date( 'Y-m-d 00:00:00') ) ) ;
    	var_dump( $r );
    	return [] ;
    	$time = strtotime( '-1 day') ;
    	$maxView = config('MICROBLOG_VIEW_MAX' , 10 );
    	$cash = config('MICROBLOG_VIEW_REWARD' , 0 );
    	ViewLog::where( 'type' , 'microblog' )
    	//->where('created_at' , '>=' , date('Y-m-d 00:00:00' , $time ))
    	//->where('created_at' , '<=' , date('Y-m-d 23:59:59') )
    	->select( \DB::raw('count(id) as num , user_id') )->groupBy('user_id')->chunk( 100 , function( $logs ) use( $maxView , $cash ) {
    		if( $logs->isNotEmpty() ) {
    			foreach( $logs as $log ) {
    				$num = data_get( $log , 'num' ) ;
    				$reward = $num > $maxView ? $maxView * $cash : $num * $cash ;
    				
	    			dispatch( new \App\Jobs\BlogViewReward( data_get( $log , 'user_id' ) , $num , $reward ) ) ;
    					
    			}
    		}
    	});
    }
}
