<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class AdvPosts extends Model
{
	
// 	ALTER TABLE `adm`.`adm_posts` ADD COLUMN `tags` VARCHAR(255) NULL AFTER `display`;


    protected $table = 'adm_posts';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'category_id',
        'total_price',
        'click_price',
        'click_times',
        'has_click_times',
        'last_days',
        'start_date',
        'end_date',
        'start_hour',
        'end_hour',
        'cover',
        'posts_content',
        'url',
        'display',
        'sort',
        'up_times',
        'down_times' ,
    	'tags' 
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}