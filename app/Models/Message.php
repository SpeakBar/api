<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperMessage
 */
class Message extends Model
{
    use HasFactory;

    protected $table = "user_messages";

    protected $fillable = [
        "sender_id",
        "receiver_id",
        "content",
        "reply",
        "encrypted",
        "encrypt_key",
    ];

    protected $hidden = [
        "encrypt_key",
    ];

    public function author(): HasOne
    {
        return $this->hasOne(User::class, "id", "sender_id");
    }
}
