<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'channel_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
