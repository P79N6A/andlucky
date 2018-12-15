<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Stolen extends Model
{

    protected $table = 'stolen_logs';

    protected $fillable = [
        'user_id',
        'from_user_id',
        'cash'
    ];

    public function thief()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    /**
     * 受害者
     */
    public function victim()
    {
        return $this->belongsTo(User::class, 'from_user_id')->withTrashed();
    }
}