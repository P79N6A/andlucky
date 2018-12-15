<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\GameFail;
use App\Jobs\Gameover;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        // ->hourly();
        $schedule->call( function(){
        	dispatch( new GameFail() );
        	dispatch(new Gameover() );
        })->everyMinute()->timezone('Asia/Shanghai');
        
        $schedule->call( function(){
        	dispatch( new \App\Jobs\GuessCron() ) ;
        	dispatch( new \App\Jobs\GuessFail() ) ;
        })->everyMinute()->timezone('Asia/Shanghai');
        
        $schedule->call( function(){
        	dispatch( new \App\Jobs\BlogReward( date( 'Y-m-d 00:00:00') ) ) ;
        })->dailyAt("01:00");
        
        $schedule->call( function(){
        	$time = strtotime( '-1 day') ;
        	$maxView = config('MICROBLOG_VIEW_MAX' , 10 );
        	$cash = config('MICROBLOG_VIEW_REWARD' , 0 );
	       \App\Models\ViewLog::where( 'type' , 'microblog' )
	        ->where('created_at' , '>=' , date('Y-m-d 00:00:00' , $time ))
	        ->where('created_at' , '<=' , date('Y-m-d 23:59:59') )
	        ->select( \DB::raw('count(id) as num , user_id') )->groupBy('user_id')->chunk( 100 , function( $logs ) use( $maxView , $cash ) {
	        	if( $logs->isNotEmpty() ) {
	        		foreach( $logs as $log ) {
	        			$num = data_get( $log , 'num' ) ;
	        			$reward = $num > $maxView ? $maxView * $cash : $num * $cash ;
	        
	        			dispatch( new \App\Jobs\BlogViewReward( data_get( $log , 'user_id' ) , $num , $reward ) ) ;
	        				
	        		}
	        	}
	        });
        })->dailyAt("01:00");
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        
        require base_path('routes/console.php');
    }
}
