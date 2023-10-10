<?php
namespace App\Models;

use Btx\Query\Model;
class UserToken extends Model
{
    protected $fillable = [
        'email',
        'token',
        'type',
        'expire_on',
        'is_accessed',
    ];

    public $timestamps = false;
    
    // protected $casts = [
    //     'is_accessed' => 'boolean'
    // ];
}