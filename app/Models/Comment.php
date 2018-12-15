<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Comment extends Model
{

    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'target_id',
        'target_type',
        'content',
        'prase',
        'comment_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function praise()
    {
        return $this->hasMany(PraiseLog::class, 'target_id', 'id')->where('type', 'comment');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'target_id', 'id')->where('target_type', 'comment');
    }

    public function scopeBlog($query)
    {
        return $query->where('target_type', 'blog');
    }

    public function scopeComment($query)
    {
        return $query->where('target_type', 'comment');
    }
}