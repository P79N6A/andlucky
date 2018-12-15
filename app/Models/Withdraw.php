<?php
/**
 * 用户的现金账户
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Withdraw extends Model
{

    protected $table = 'user_withdraw';

    protected $fillable = [
        'id',
        'user_id',
        'used',
        'rate',
        'cash',
        'admin_id',
        'status',
        'remark' ,
    	'bank_name' ,
    	'bank_account' ,
    ];
    
    
    public function user() {
    	return $this->belongsTo( User::class , 'user_id' , 'id')->withTrashed();
    }
}