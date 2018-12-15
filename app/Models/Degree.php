<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{

    protected $table = 'sys_degrees';

    protected $fillable = [
        'title',
        'sort',
        'display'
    ];
}