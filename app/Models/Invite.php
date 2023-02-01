<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invite extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'code',
        'user_id'
    ];

    public function group(): HasOne
    {
        return $this->hasOne(Group::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
