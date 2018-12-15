<?php
/**
 * 用户的现金账户
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserCash extends Model
{

    protected $table = 'adm_user_cash';

    protected $fillable = [
        'id',
        'user_id',
        'event',
        'target_id',
        'target_desc',
        'act',
        'cash',
        'extra_cash'
    ];
    
    
    public function user() {
    	return $this->belongsTo( User::class , 'user_id' , 'id' )->withTrashed();
    }
}