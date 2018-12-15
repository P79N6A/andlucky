<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Stolen as StolenModel;
use App\User;

class Stolen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stolen;

    protected $fromUser;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(StolenModel $stolen, User $fromUser, User $user)
    {
        $this->stolen = $stolen;
        $this->fromUser = $fromUser;
        $this->user = $user;
        // $easemob = new Easemob( config('global.easemob') );
        
        //
    }

    public function handle()
    {
        // TODO 给被偷用户发送通知
    }
}