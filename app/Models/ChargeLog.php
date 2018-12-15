<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use function GuzzleHttp\json_encode;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargeLog extends Model
{
	use SoftDeletes ;

    protected $table = 'charge_log';

    protected $fillable = [
        'user_id',
        'out_trade_no',
        'trade_no' , 
    	'charge' , 
    	'status' ,
    	'notice_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}