<?php
/**
 * 用户的现金账户
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{

    protected $table = 'adm_user_currency';

    protected $fillable = [
        'id',
        'user_id',
        'event',
        'target_id',
        'target_desc',
        'act',
        'cash'
    ];
}