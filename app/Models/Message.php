<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = "user_messages";

    protected $fillable = [
        "channel",
        "user_id",
        "content",
        "reply",
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
