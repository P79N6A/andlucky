<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Models\Microblog;
use App\Models\UserFocus;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens , Notifiable , SoftDeletes ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'invite_code',
        'invite_by',
        'register_ip',
        'last_login_time',
        'last_login_ip',
        'last_update',
        'cash_recharge',
        'cash_reward',
        'cash_frozen',
        'city',
        'degree',
        'tags',
        'birth_day',
        'nickname',
        'avatar',
        'delete_at',
        'api_token',
        'total_big_small',
        'lose_big_small',
        'not_pay_big_small',
        'lose_big_small_cash',
        'not_pay_big_small_cash' ,
    	'alipay_account' ,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function getNicknameAttribute()
    {
        if ($this->attributes['nickname']) {
            return $this->attributes['nickname'];
        }
        return config('global.no_nickname');
    }
    
    public function blog() {
    	return $this->hasMany( Microblog::class , 'user_id' , 'id' );
    }
    
    public function fans() {
    	return $this->hasMany( UserFocus::class , 'focus_user_id' , 'id' );
    }
    
    public function cares() {
    	return $this->hasMany( UserFocus::class , 'user_id' , 'id' );
    }


    public function getCreditAttribute() {

        $a = $this->attributes['not_pay_big_small'] ;
        $b = $this->attributes['lose_big_small'] ;
        $c = $this->attributes['not_pay_big_small_cash'] ;
        $d = $this->attributes['lose_big_small_cash'] ;
        //user.not_pay_big_small ,user.lose_big_small , user.not_pay_big_small_cash , user.lose_big_small_cash
        $b = $b > 0 ? $b : 1 ;
        $d = $d > 0 ? $d : 1 ;
        $a = $a > 0 ? $a : 0  ;
        $c = $c > 0 ? $c : 0 ;

        return number_format(5 - ( 2 * $a / $b + 3 * $c / $d )  , 2 , '.' , '' ) ;
    }
    
}
