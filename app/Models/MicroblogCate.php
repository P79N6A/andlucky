<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicroblogCate extends Model
{

    protected $table = 'microblog_cate';

    protected $fillable = [
        'title',
        'keyword',
    	'description' ,
    	'cover' ,
        'sort',
        'display'
    ];


    public function getTagsAttribute( $v ) {
        if( $v ) {
            return explode(',' , $v );
        }
        return [] ;
    }
}