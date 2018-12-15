<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class GuessJoin extends Model
{

    protected $table = 'guess_join';

    protected $fillable = [
        'id',
        'user_id',
        'guess_id',
        'cash',
        'seed',
        'is_win',
        'win_cash',
        'status' ,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function guess()
    {
        return $this->belongsTo(Guess::class, 'guess_id', 'id');
    }
}