<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Message extends Model
{

    protected $table = 'messages';

    protected $fillable = [
        'user_id',
        'message',
        'read_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }
}