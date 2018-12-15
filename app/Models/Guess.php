<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Guess extends Model
{

    protected $table = 'guess';

    protected $fillable = [
        'id',
        'user_id',
        'cash',
        'rate',
        'seed',
        'max_join',
        'has_join',
        'occupy_cash' ,
    	'win_cash' ,
    	'lose_cash' ,
    	'status' ,
    	'end_time' ,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function join()
    {
        return $this->hasMany(GuessJoin::class, 'guess_id', 'id');
    }
    
    public function getRateAttribute( $v ) {
    	return round( $v , 2 );
    }
}