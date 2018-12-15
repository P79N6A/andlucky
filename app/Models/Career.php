<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{

    protected $table = 'sys_careers';

    protected $fillable = [
        'title',
        'sort',
        'display'
    ];
}