<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserFocus extends Model
{

    protected $table = 'user_focus';

    protected $fillable = [
        'id',
        'user_id',
        'focus_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function fans()
    {
        return $this->belongsTo(User::class, 'focus_user_id', 'id')->withTrashed();
    }
}