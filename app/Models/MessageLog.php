<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use function GuzzleHttp\json_encode;

class MessageLog extends Model
{

    protected $table = 'im_message_logs';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'type',
        'body',
        'send_time',
        'msg_id',
        'ext',
        'type'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function setBodyAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['body'] = json_encode($value);
        } else {
            $this->attributes['body'] = json_encode([]);
        }
    }

    public function getBodyAttribute()
    {
        return json_decode($this->attributes['body']);
    }
    
    public function setExtAttribute($value)
    {
    	if (is_array($value) || is_object($value)) {
    		$this->attributes['ext'] = json_encode($value);
    	} else {
    		$this->attributes['ext'] = json_encode([]);
    	}
    }
    
    public function getExtAttribute()
    {
    	return json_decode($this->attributes['ext']);
    }
}