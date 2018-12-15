<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class SmallBig extends Model
{

    protected $table = 'big_small';

    protected $fillable = [
        'id',
        'user_id',
        'invite_user_id',
        'cash_deposit',
        'deposit_status',
        'status',
        'user_num',
        'inviter_num'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invite_user_id', 'id')->withTrashed();
    }
}