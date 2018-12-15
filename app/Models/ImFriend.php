<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ImFriend extends Model
{

    protected $table = 'im_friends';

    protected $fillable = [
        'id',
        'user_id',
        'friend_user_id',
        'alias_name',
        'status',
        'is_one_way_friend'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_user_id', 'id')->withTrashed();
    }
    
}