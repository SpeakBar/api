<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Message
 *
 * @mixin IdeHelperMessage
 * @property int $id
 * @property string|null $content
 * @property int $sender_id
 * @property int $receiver_id
 * @property int|null $reply
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $encrypted
 * @property string|null $encrypt_key
 * @property-read \App\Models\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reaction> $reaction
 * @property-read int|null $reaction_count
 * @method static \Database\Factories\MessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereEncryptKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereEncrypted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reaction> $reaction
 * @mixin \Eloquent
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

    public function reaction(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }
}
