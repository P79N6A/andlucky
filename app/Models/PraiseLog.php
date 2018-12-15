<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use function GuzzleHttp\json_encode;

class PraiseLog extends Model
{

    protected $table = 'praise_logs';

    protected $fillable = [
        'user_id',
        'target_id',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    /**
     * 评论类型
     */
    public function comment()
    {
        return $this->hasMany(Comment::class, 'target_id', 'id')->where('type', 'comment');
    }

    /**
     * 微博类型
     */
    public function microblog()
    {
        return $this->hasMany(Microblog::class, 'target_id', 'id')->where('type', 'microblog');
    }

    public function scopeBlog($query)
    {
        return $query->where('type', 'microblog');
    }

    public function scopeAdv($query)
    {
        return $query->where('type', 'adv');
    }
    
}