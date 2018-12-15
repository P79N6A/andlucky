<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use function GuzzleHttp\json_encode;

class Microblog extends Model
{

    protected $table = 'microblog';

    protected $fillable = [
        'user_id',
        'title' ,
        'content',
        'cate_id',
        'extra',
        'views',
        'prase',
        'comment_count' ,
    	'allow_auth',
		'auth_status',
		'adv_link' , 
    	'adv_cover' , 
    	'adv_pos' ,
    		
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
    
    public function category() {
    	return $this->belongsTo( MicroblogCate::class , 'cate_id' );
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'target_id', 'id')->where('target_type', 'blog');
    }

    public function view()
    {
        return $this->hasMany(ViewLog::class, 'target_id', 'id')->where('type', 'microblog');
    }

    public function praises()
    {
        return $this->hasMany(PraiseLog::class, 'target_id', 'id')->where('type', 'microblog');
    }

    public function setExtraAttribute($v)
    {
        if (is_array($v)) {
            $this->attributes['extra'] = json_encode($v);
        }
    }

    public function getExtraAttribute($v)
    {
        return json_decode($v, true);
    }
}