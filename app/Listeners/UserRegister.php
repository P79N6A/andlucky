<?php
namespace App\Listeners;

use App\Events\UserRegister as EventUserRegister;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegister implements ShouldQueue
{
    use InteractsWithQueue ;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(EventUserRegister $event)
    {
        //
    }
}
