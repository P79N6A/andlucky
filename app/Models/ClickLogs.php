<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClickLogs extends Model
{

    protected $table = 'adm_post_click_log';

    protected $fillable = [
        'id',
        'user_id',
        'post_id',
        'click_reward'
    ];

    public function task()
    {
        return $this->belongsTo(AdvPosts::class, 'post_id', 'id');
    }
}